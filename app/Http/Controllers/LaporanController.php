<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\PermintaanPembelian;
use App\Models\ProduksiHarian;
use App\Models\RiwayatStok;
use App\Exports\StokExport;
use App\Exports\PermintaanBahanExport;
use App\Exports\PermintaanPembelianExport;
use App\Exports\BarangMasukExport;
use App\Exports\PenggunaanBahanExport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function dashboard()
    {
        $periode = request('periode', 'bulan');
        if ($periode === 'tahun') {
            $mulai  = now()->startOfYear();
            $sampai = now()->endOfYear();
        } elseif ($periode === 'minggu') {
            $mulai  = now()->startOfWeek();
            $sampai = now()->endOfWeek();
        } else {
            $mulai  = now()->startOfMonth();
            $sampai = now()->endOfMonth();
        }

        $total_permintaan_bahan      = ProduksiHarian::whereBetween('tanggal_produksi', [$mulai, $sampai])->sum('jumlah_siswa');
        $total_permintaan_pembelian  = PermintaanPembelian::whereBetween('tanggal', [$mulai, $sampai])->count();
        $total_barang_masuk          = BarangMasuk::whereBetween('tanggal_masuk', [$mulai, $sampai])->count();
        $total_penggunaan_bahan      = (float) RiwayatStok::where('jenis_transaksi', 'keluar_produksi')
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->sum('jumlah');

        $bahan_terpakai = RiwayatStok::selectRaw('bahan_baku_id, SUM(jumlah) as total')
            ->where('jenis_transaksi', 'keluar_produksi')
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->groupBy('bahan_baku_id')
            ->with('bahanBaku')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $permintaan_bulanan = ProduksiHarian::selectRaw("DATE_FORMAT(tanggal_produksi, '%Y-%m') as bulan, SUM(jumlah_siswa) as total")
            ->where('tanggal_produksi', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan');

        $pembelian_bulanan = PermintaanPembelian::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('tanggal', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan');

        $pr_stats = [
            'Draft'           => PermintaanPembelian::where('status', 'Draft')->count(),
            'Diajukan'        => PermintaanPembelian::where('status', 'Diajukan')->count(),
            'Disetujui'       => PermintaanPembelian::where('status', 'Disetujui')->count(),
            'Barang Diterima' => PermintaanPembelian::where('status', 'Barang Diterima')->count(),
            'Selesai'         => PermintaanPembelian::where('status', 'Selesai')->count(),
            'Ditolak'         => PermintaanPembelian::where('status', 'Ditolak')->count(),
        ];

        $bulanLabels = collect();
        $permintaanData = collect();
        $pembelianData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $b = now()->subMonths($i)->format('Y-m');
            $bulanLabels->push(now()->subMonths($i)->format('M Y'));
            $permintaanData->push((int) ($permintaan_bulanan[$b] ?? 0));
            $pembelianData->push((int) ($pembelian_bulanan[$b] ?? 0));
        }

        return view('laporan.dashboard', compact(
            'periode', 'mulai', 'sampai',
            'total_permintaan_bahan', 'total_permintaan_pembelian',
            'total_barang_masuk', 'total_penggunaan_bahan',
            'bahan_terpakai', 'pr_stats',
            'bulanLabels', 'permintaanData', 'pembelianData'
        ));
    }

    private function queryStok(Request $request)
    {
        return BahanBaku::with(['kategori', 'satuan'])
            ->where('is_active', true)
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori_id', $request->kategori))
            ->orderBy('nama_bahan');
    }

    public function stok(Request $request)
    {
        $bahanBakus = $this->queryStok($request)->get();
        $kategoris = \App\Models\KategoriBahan::orderBy('nama_kategori')->get();
        return view('laporan.stok', compact('bahanBakus', 'kategoris'));
    }

    public function stokPdf(Request $request)
    {
        $bahanBakus = $this->queryStok($request)->get();
        // Setelah: composer require barryvdh/laravel-dompdf
        // return Pdf::loadView('laporan.prints.stok', compact('bahanBakus'))->download('laporan-stok.pdf');
        return view('laporan.prints.stok', ['bahanBakus' => $bahanBakus, 'title' => 'Laporan Stok Bahan Baku']);
    }

    public function stokExcel(Request $request)
    {
        $bahanBakus = $this->queryStok($request)->get();
        // Setelah: composer require maatwebsite/excel
        // return Excel::download(new StokExport($bahanBakus), 'laporan-stok-' . now()->format('Y-m-d') . '.xlsx');
        (new StokExport($bahanBakus))->download('laporan-stok-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function queryPermintaanBahan(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        return ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah', 'user'])
            ->whereBetween('tanggal_produksi', [$dari, $sampai])
            ->when($request->filled('menu'), fn($q) => $q->where(function($q) use ($request) {
                $q->where('menu_makanan_id', $request->menu)
                   ->orWhere('menu_minuman_id', $request->menu)
                   ->orWhere('menu_buah_id', $request->menu);
            }))
            ->orderBy('tanggal_produksi');
    }

    public function permintaanBahan(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $produksis = $this->queryPermintaanBahan($request)->get();
        $totalSiswa = $produksis->sum('jumlah_siswa');
        $menus = \App\Models\Menu::where('is_active', true)->orderBy('nama_menu')->get();
        return view('laporan.permintaan-bahan', compact('produksis', 'totalSiswa', 'dari', 'sampai', 'menus'));
    }

    public function permintaanBahanPdf(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $produksis = $this->queryPermintaanBahan($request)->get();
        $totalSiswa = $produksis->sum('jumlah_siswa');
        return view('laporan.prints.permintaan-bahan', ['produksis' => $produksis, 'totalSiswa' => $totalSiswa, 'dari' => $dari, 'sampai' => $sampai, 'title' => 'Laporan Permintaan Bahan Baku']);
    }

    public function permintaanBahanExcel(Request $request)
    {
        $produksis = $this->queryPermintaanBahan($request)->get();
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $file   = "laporan-permintaan-bahan-{$dari}_sampai_{$sampai}.xlsx";
        // Setelah: composer require maatwebsite/excel
        // return Excel::download(new PermintaanBahanExport($produksis), $file);
        (new PermintaanBahanExport($produksis))->download($file);
    }

    private function queryPermintaanPembelian(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        return PermintaanPembelian::with(['user', 'detail.bahanBaku.satuan'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('supplier_id'), fn($q) => $q->whereHas('detail.bahanBaku', fn($sq) => $sq->where('supplier_id', $request->supplier_id)))
            ->orderBy('tanggal');
    }

    public function permintaanPembelian(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $data = $this->queryPermintaanPembelian($request)->get();
        $suppliers = \App\Models\Supplier::orderBy('nama_supplier')->get();
        $statuses = ['Draft', 'Diajukan', 'Disetujui', 'Barang Diterima', 'Selesai', 'Ditolak'];
        return view('laporan.permintaan-pembelian', compact('data', 'dari', 'sampai', 'suppliers', 'statuses'));
    }

    public function permintaanPembelianPdf(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $data = $this->queryPermintaanPembelian($request)->get();
        return view('laporan.prints.permintaan-pembelian', ['data' => $data, 'dari' => $dari, 'sampai' => $sampai, 'title' => 'Laporan Permintaan Pembelian']);
    }

    public function permintaanPembelianExcel(Request $request)
    {
        $data   = $this->queryPermintaanPembelian($request)->get();
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $file   = "laporan-permintaan-pembelian-{$dari}_sampai_{$sampai}.xlsx";
        // Setelah: composer require maatwebsite/excel
        // return Excel::download(new PermintaanPembelianExport($data), $file);
        (new PermintaanPembelianExport($data))->download($file);
    }

    private function queryBarangMasuk(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        return BarangMasuk::with(['supplier', 'user', 'detail.bahanBaku'])
            ->whereBetween('tanggal_masuk', [$dari, $sampai])
            ->when($request->filled('supplier_id'), fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->orderBy('tanggal_masuk');
    }

    public function barangMasuk(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $barangMasuks = $this->queryBarangMasuk($request)->get();
        $suppliers = \App\Models\Supplier::orderBy('nama_supplier')->get();
        return view('laporan.barang-masuk', compact('barangMasuks', 'dari', 'sampai', 'suppliers'));
    }

    public function barangMasukPdf(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $barangMasuks = $this->queryBarangMasuk($request)->get();
        return view('laporan.prints.barang-masuk', ['barangMasuks' => $barangMasuks, 'dari' => $dari, 'sampai' => $sampai, 'title' => 'Laporan Penerimaan Barang']);
    }

    public function barangMasukExcel(Request $request)
    {
        $barangMasuks = $this->queryBarangMasuk($request)->get();
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $file   = "laporan-penerimaan-barang-{$dari}_sampai_{$sampai}.xlsx";
        // Setelah: composer require maatwebsite/excel
        // return Excel::download(new BarangMasukExport($barangMasuks), $file);
        (new BarangMasukExport($barangMasuks))->download($file);
    }

    private function queryPenggunaanBahan(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        return RiwayatStok::with('bahanBaku.kategori')
            ->where('jenis_transaksi', 'keluar_produksi')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->when($request->filled('bahan_baku_id'), fn($q) => $q->where('bahan_baku_id', $request->bahan_baku_id))
            ->orderBy('tanggal');
    }

    public function penggunaanBahan(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $riwayats = $this->queryPenggunaanBahan($request)->get();
        $bahanBakus = BahanBaku::where('is_active', true)->orderBy('nama_bahan')->get();
        return view('laporan.penggunaan-bahan', compact('riwayats', 'dari', 'sampai', 'bahanBakus'));
    }

    public function penggunaanBahanPdf(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $riwayats = $this->queryPenggunaanBahan($request)->get();
        return view('laporan.prints.penggunaan-bahan', ['riwayats' => $riwayats, 'dari' => $dari, 'sampai' => $sampai, 'title' => 'Laporan Penggunaan Bahan Baku']);
    }

    public function penggunaanBahanExcel(Request $request)
    {
        $riwayats = $this->queryPenggunaanBahan($request)->get();
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $file   = "laporan-penggunaan-bahan-{$dari}_sampai_{$sampai}.xlsx";
        // Setelah: composer require maatwebsite/excel
        // return Excel::download(new PenggunaanBahanExport($riwayats), $file);
        (new PenggunaanBahanExport($riwayats))->download($file);
    }
}