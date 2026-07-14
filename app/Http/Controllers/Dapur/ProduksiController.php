<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\BahanBaku;
use App\Models\ProduksiHarian;
use App\Models\DetailProduksi;
use App\Models\RiwayatStok;
use App\Models\PermintaanPembelian;
use App\Models\PermintaanPembelianDetail;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah', 'user'])
            ->where('user_id', auth()->id());

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_produksi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_produksi', '<=', $request->tanggal_sampai);
        }

        $produksis = $query->latest()->paginate(15)->withQueryString();
        return view('dapur.produksi.index', compact('produksis'));
    }

    public function create()
    {
        $menuMakanan = Menu::where('jenis_menu', 'makanan')->where('is_active', true)->orderBy('nama_menu')->get();
        $menuMinuman = Menu::where('jenis_menu', 'minuman')->where('is_active', true)->orderBy('nama_menu')->get();
        $menuBuah    = Menu::where('jenis_menu', 'buah')->where('is_active', true)->orderBy('nama_menu')->get();

        return view('dapur.produksi.create', compact('menuMakanan', 'menuMinuman', 'menuBuah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_produksi' => 'required|date',
            'menu_makanan_id'  => 'required|exists:menu,id',
            'menu_minuman_id'  => 'required|exists:menu,id',
            'menu_buah_id'     => 'required|exists:menu,id',
            'jumlah_siswa'     => 'required|integer|min:1',
            'keterangan'       => 'nullable|string',
        ]);

        $jumlahSiswa  = $request->jumlah_siswa;
        $menuMakanan  = Menu::with('resep.bahanBaku')->find($request->menu_makanan_id);
        $menuMinuman  = Menu::find($request->menu_minuman_id);
        $menuBuah     = Menu::find($request->menu_buah_id);

        // Validasi ketersediaan stok
        $kekurangan = [];

        foreach ($menuMakanan->resep as $resep) {
            $kebutuhan = $resep->jumlah_per_porsi * $jumlahSiswa;
            $stokTersedia = $resep->bahanBaku->stok_saat_ini;
            if ($stokTersedia < $kebutuhan) {
                $kekurangan[] = [
                    'bahan_baku_id' => $resep->bahan_baku_id,
                    'satuan_id'     => $resep->satuan_id,
                    'nama_bahan'    => $resep->bahanBaku->nama_bahan,
                    'kebutuhan'     => (float) $kebutuhan,
                    'stok'          => (float) $stokTersedia,
                    'kekurangan'    => (float) ($kebutuhan - $stokTersedia),
                    'satuan'        => $resep->bahanBaku->satuan->singkatan,
                ];
            }
        }

        if (!empty($kekurangan)) {
            return back()
                ->withInput()
                ->with('error_stok', $kekurangan);
        }

        DB::transaction(function () use ($request, $menuMakanan, $menuMinuman, $menuBuah, $jumlahSiswa) {
            $produksi = ProduksiHarian::create([
                'tanggal_produksi' => $request->tanggal_produksi,
                'menu_makanan_id'  => $menuMakanan->id,
                'menu_minuman_id'  => $menuMinuman->id,
                'menu_buah_id'     => $menuBuah->id,
                'jumlah_siswa'     => $jumlahSiswa,
                'user_id'          => auth()->id(),
                'status'           => 'dikonfirmasi',
                'keterangan'       => $request->keterangan,
            ]);

            // Potong stok bahan baku dari resep makanan
            foreach ($menuMakanan->resep as $resep) {
                $kebutuhan = $resep->jumlah_per_porsi * $jumlahSiswa;

                DetailProduksi::create([
                    'produksi_harian_id' => $produksi->id,
                    'bahan_baku_id'      => $resep->bahan_baku_id,
                    'jumlah_dibutuhkan'  => $kebutuhan,
                    'satuan_id'          => $resep->satuan_id,
                ]);

                $bahan = $resep->bahanBaku;
                $stokSebelum = $bahan->stok_saat_ini;
                $stokSesudah = $stokSebelum - $kebutuhan;

                $bahan->update(['stok_saat_ini' => $stokSesudah]);

                RiwayatStok::catat(
                    $bahan->id,
                    'keluar_produksi',
                    $produksi->id,
                    'produksi_harian',
                    $kebutuhan,
                    $stokSebelum,
                    $stokSesudah,
                    'Produksi: ' . $menuMakanan->nama_menu . ' untuk ' . $jumlahSiswa . ' siswa'
                );
            }
        });

        return redirect()->route('dapur.produksi.index')->with('success', 'Produksi berhasil dicatat dan stok bahan baku telah dikurangi.');
    }

    public function show(ProduksiHarian $produksi)
    {
        $produksi->load(['menuMakanan.resep.bahanBaku.satuan', 'menuMinuman', 'menuBuah', 'detail.bahanBaku.satuan', 'user']);
        return view('dapur.produksi.show', compact('produksi'));
    }

    public function getResep(Request $request)
    {
        $menu = Menu::with('resep.bahanBaku.satuan')->find($request->menu_id);
        if (!$menu) return response()->json([]);

        return response()->json($menu->resep->map(fn($r) => [
            'nama_bahan'       => $r->bahanBaku->nama_bahan,
            'jumlah_per_porsi' => $r->jumlah_per_porsi,
            'satuan'           => $r->satuan->singkatan,
            'stok'             => $r->bahanBaku->stok_saat_ini,
        ]));
    }

    public function ajukanPermintaan(Request $request)
    {
        $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.bahan_baku_id'   => 'required|exists:bahan_baku,id',
            'items.*.jumlah'          => 'required|numeric|min:0.01',
            'items.*.satuan_id'       => 'required|exists:satuan,id',
        ]);

        $pr = DB::transaction(function () use ($request) {
            $pr = PermintaanPembelian::create([
                'nomor_permintaan' => PermintaanPembelian::generateNomor(),
                'tanggal'          => now()->format('Y-m-d'),
                'user_id'          => auth()->id(),
                'status'           => 'Diajukan',
                'keterangan'       => 'Diajukan oleh Dapur (otomatis dari kekurangan stok produksi)',
            ]);

            foreach ($request->items as $item) {
                PermintaanPembelianDetail::create([
                    'permintaan_pembelian_id' => $pr->id,
                    'bahan_baku_id'           => $item['bahan_baku_id'],
                    'jumlah'                  => $item['jumlah'],
                    'satuan_id'               => $item['satuan_id'],
                    'catatan'                 => 'Kekurangan stok produksi',
                ]);
            }

            return $pr;
        });

        return response()->json([
            'success' => true,
            'message' => 'Permintaan Pembelian ' . $pr->nomor_permintaan . ' berhasil dikirim ke Admin/Petugas Gudang.',
        ]);
    }
}