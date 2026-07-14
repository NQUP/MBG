<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\ProduksiHarian;
use App\Models\Menu;
use App\Models\User;
use App\Models\PermintaanPembelian;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin()) {
            // Stats
            $data['total_bahan']    = BahanBaku::where('is_active', true)->count();
            $data['total_menu']     = Menu::where('is_active', true)->count();
            $data['total_user']     = User::where('is_active', true)->count();
            $data['total_produksi'] = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->count();
            $data['stok_minimum']   = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
            $data['total_siswa']    = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->sum('jumlah_siswa');

            // Chart: Produksi harian bulan ini
            $data['produksi_bulanan'] = ProduksiHarian::selectRaw('DATE(tanggal_produksi) as tanggal, SUM(jumlah_siswa) as total')
                ->whereMonth('tanggal_produksi', now()->month)
                ->groupBy('tanggal_produksi')
                ->orderBy('tanggal_produksi')
                ->get();

            // Chart: PR status distribution
            $data['pr_stats'] = [
                'Draft'    => PermintaanPembelian::where('status', 'Draft')->count(),
                'Diajukan' => PermintaanPembelian::where('status', 'Diajukan')->count(),
                'Disetujui'=> PermintaanPembelian::where('status', 'Disetujui')->count(),
                'Selesai'  => PermintaanPembelian::where('status', 'Selesai')->count(),
                'Ditolak'  => PermintaanPembelian::where('status', 'Ditolak')->count(),
            ];

            // Chart: Stok bahan baku vs minimum
            $data['stok_chart'] = BahanBaku::where('is_active', true)
                ->select('nama_bahan', 'stok_saat_ini', 'stok_minimum')
                ->orderBy('nama_bahan')
                ->get();

            // Recent PR
            $data['pr_recent'] = PermintaanPembelian::with('user')
                ->latest()->limit(5)->get();

        } elseif ($user->isPetugasGudang()) {
            // Stats
            $data['total_bahan']       = BahanBaku::where('is_active', true)->count();
            $data['stok_minimum_count'] = BahanBaku::where('is_active', true)->whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
            $data['barang_masuk_hari_ini'] = BarangMasuk::whereDate('tanggal_masuk', today())->count();
            $data['barang_masuk_bulan']    = BarangMasuk::whereMonth('tanggal_masuk', now()->month)->count();

            // Table: Bahan stok minimum
            $data['stok_minimum'] = BahanBaku::where('is_active', true)
                ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
                ->with(['kategori', 'satuan'])
                ->get();

            // Chart: Stok semua bahan baku
            $data['stok_chart'] = BahanBaku::where('is_active', true)
                ->select('nama_bahan', 'stok_saat_ini', 'stok_minimum')
                ->orderBy('stok_saat_ini', 'asc')
                ->get();

            // Chart: Barang masuk 6 bulan terakhir
            $data['barang_masuk_chart'] = BarangMasuk::selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as bulan, COUNT(*) as jumlah")
                ->where('tanggal_masuk', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            // Chart: Riwayat stok 7 hari terakhir (pre-processed)
            $rawRiwayat = RiwayatStok::selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal, jenis_transaksi, SUM(jumlah) as total_jumlah")
                ->where('tanggal', '>=', now()->subDays(6))
                ->groupBy('tanggal', 'jenis_transaksi')
                ->orderBy('tanggal')
                ->get()
                ->groupBy('tanggal');

            $riwayatLabels = [];
            $riwayatMasuk = [];
            $riwayatKeluar = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i);
                $key = $d->format('Y-m-d');
                $riwayatLabels[] = $d->format('d M');
                $group = $rawRiwayat->get($key, collect());
                $m = 0;
                $k = 0;
                foreach ($group as $row) {
                    if (in_array($row->jenis_transaksi, ['masuk', 'masuk_opname'])) {
                        $m += (float) $row->total_jumlah;
                    } else {
                        $k += (float) $row->total_jumlah;
                    }
                }
                $riwayatMasuk[] = $m;
                $riwayatKeluar[] = $k;
            }
            $data['riwayat_labels'] = $riwayatLabels;
            $data['riwayat_masuk'] = $riwayatMasuk;
            $data['riwayat_keluar'] = $riwayatKeluar;

            // PR status
            $data['pr_stats'] = [
                'Diajukan' => PermintaanPembelian::where('status', 'Diajukan')->count(),
                'Disetujui'=> PermintaanPembelian::where('status', 'Disetujui')->count(),
                'Selesai'  => PermintaanPembelian::where('status', 'Selesai')->whereMonth('created_at', now()->month)->count(),
            ];

            // Recent PR
            $data['pr_recent'] = PermintaanPembelian::with('user')
                ->whereIn('status', ['Diajukan', 'Disetujui'])
                ->latest()->limit(5)->get();

        } elseif ($user->isPetugasDapur()) {
            // Stats
            $data['produksi_hari_ini'] = ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah'])
                ->whereDate('tanggal_produksi', today())->get();
            $data['produksi_minggu']   = ProduksiHarian::whereBetween('tanggal_produksi', [now()->startOfWeek(), now()->endOfWeek()])->sum('jumlah_siswa');
            $data['total_siswa_bulan'] = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->sum('jumlah_siswa');
            $data['total_sesi_bulan']  = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->count();
            $data['total_menu']        = Menu::where('is_active', true)->count();

            // Chart: Produksi 7 hari terakhir
            $data['produksi_mingguan'] = ProduksiHarian::selectRaw("DATE(tanggal_produksi) as tanggal, SUM(jumlah_siswa) as total")
                ->where('tanggal_produksi', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal_produksi')
                ->orderBy('tanggal_produksi')
                ->get();

            // Chart: Distribusi menu (makanan paling sering diproduksi)
            $data['menu_stats'] = ProduksiHarian::selectRaw('menu_makanan_id, COUNT(*) as jumlah, SUM(jumlah_siswa) as total_siswa')
                ->whereMonth('tanggal_produksi', now()->month)
                ->with('menuMakanan')
                ->groupBy('menu_makanan_id')
                ->orderByDesc('jumlah')
                ->get();

            // Recent produksi
            $data['produksi_recent'] = ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah'])
                ->latest()->limit(5)->get();
        }

        return view('dashboard.index', compact('data', 'user'));
    }
}