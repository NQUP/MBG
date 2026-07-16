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
        $bahanMinuman = BahanBaku::with('satuan')
            ->where('is_active', true)
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', 'Minuman'))
            ->orderBy('nama_bahan')->get();
        $bahanBuah    = BahanBaku::with('satuan')
            ->where('is_active', true)
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', 'Buah'))
            ->orderBy('nama_bahan')->get();

        return view('dapur.produksi.create', compact('menuMakanan', 'bahanMinuman', 'bahanBuah'));
    }

public function store(Request $request)
    {
        $request->validate([
            'tanggal_produksi'   => 'required|date',
            'menu_makanan_id'    => 'required|exists:menu,id',
            'bahan_minuman_id'   => 'required|exists:bahan_baku,id',
            'bahan_buah_id'      => 'required|exists:bahan_baku,id',
            'jumlah_siswa'       => 'required|integer|min:1',
            'keterangan'         => 'nullable|string',
        ]);

        $jumlahSiswa  = $request->jumlah_siswa;
        $menuMakanan  = Menu::with('resep.bahanBaku.satuan')->find($request->menu_makanan_id);
        $bahanMinuman = BahanBaku::with('satuan')->find($request->bahan_minuman_id);
        $bahanBuah    = BahanBaku::with('satuan')->find($request->bahan_buah_id);

        $kekurangan = [];

        // Cek stok minuman
        if ($bahanMinuman && $bahanMinuman->stok_saat_ini < $jumlahSiswa) {
            $kekurangan[] = [
                'bahan_baku_id' => $bahanMinuman->id,
                'satuan_id'     => $bahanMinuman->satuan_id,
                'nama_bahan'    => $bahanMinuman->nama_bahan . ' (Minuman)',
                'kebutuhan'     => (float) $jumlahSiswa,
                'stok'          => (float) $bahanMinuman->stok_saat_ini,
                'kekurangan'    => (float) ($jumlahSiswa - $bahanMinuman->stok_saat_ini),
                'satuan'        => $bahanMinuman->satuan->singkatan ?? '',
                'tipe'          => 'bahan_baku',
            ];
        }

        // Cek stok buah
        if ($bahanBuah && $bahanBuah->stok_saat_ini < $jumlahSiswa) {
            $kekurangan[] = [
                'bahan_baku_id' => $bahanBuah->id,
                'satuan_id'     => $bahanBuah->satuan_id,
                'nama_bahan'    => $bahanBuah->nama_bahan . ' (Buah)',
                'kebutuhan'     => (float) $jumlahSiswa,
                'stok'          => (float) $bahanBuah->stok_saat_ini,
                'kekurangan'    => (float) ($jumlahSiswa - $bahanBuah->stok_saat_ini),
                'satuan'        => $bahanBuah->satuan->singkatan ?? '',
                'tipe'          => 'bahan_baku',
            ];
        }

        // Cek stok bahan baku makanan
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
                    'tipe'          => 'bahan_baku',
                ];
            }
        }

        if (!empty($kekurangan)) {
            return back()
                ->withInput()
                ->with('error_stok', $kekurangan);
        }

        DB::transaction(function () use ($request, $menuMakanan, $bahanMinuman, $bahanBuah, $jumlahSiswa) {
            $produksi = ProduksiHarian::create([
                'tanggal_produksi' => $request->tanggal_produksi,
                'menu_makanan_id'  => $menuMakanan->id,
                'menu_minuman_id'  => $bahanMinuman->id,
                'menu_buah_id'     => $bahanBuah->id,
                'jumlah_siswa'     => $jumlahSiswa,
                'user_id'          => auth()->id(),
                'status'           => 'dikonfirmasi',
                'keterangan'       => $request->keterangan,
            ]);

            $potongStokBahan = function (BahanBaku $bahan, float $jumlah, string $label) use ($produksi) {
                $stokSebelum = $bahan->stok_saat_ini;
                $stokSesudah = $stokSebelum - $jumlah;
                $bahan->update(['stok_saat_ini' => $stokSesudah]);
                RiwayatStok::catat(
                    $bahan->id,
                    'keluar_produksi',
                    $produksi->id,
                    'produksi_harian',
                    $jumlah,
                    $stokSebelum,
                    $stokSesudah,
                    $label,
                );
            };

            // Potong stok minuman
            $potongStokBahan($bahanMinuman, $jumlahSiswa, 'Produksi minuman: ' . $bahanMinuman->nama_bahan . ' untuk ' . $jumlahSiswa . ' siswa');

            // Potong stok buah
            $potongStokBahan($bahanBuah, $jumlahSiswa, 'Produksi buah: ' . $bahanBuah->nama_bahan . ' untuk ' . $jumlahSiswa . ' siswa');

            // Potong stok bahan baku dari resep makanan
            foreach ($menuMakanan->resep as $resep) {
                $kebutuhan = $resep->jumlah_per_porsi * $jumlahSiswa;

                DetailProduksi::create([
                    'produksi_harian_id' => $produksi->id,
                    'bahan_baku_id'      => $resep->bahan_baku_id,
                    'jumlah_dibutuhkan'  => $kebutuhan,
                    'satuan_id'          => $resep->satuan_id,
                ]);

                $potongStokBahan($resep->bahanBaku, $kebutuhan, 'Produksi: ' . $menuMakanan->nama_menu . ' untuk ' . $jumlahSiswa . ' siswa');
            }
        });

        return redirect()->route('dapur.produksi.index')->with('success', 'Produksi berhasil dicatat dan stok bahan baku telah dikurangi.');
    }

public function show(ProduksiHarian $produksi)
    {
        $produksi->load([
            'menuMakanan.resep.bahanBaku.satuan',
            'menuMinuman.satuan',
            'menuBuah.satuan',
            'detail.bahanBaku.satuan',
            'user',
        ]);
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
            'items.*.catatan'         => 'nullable|string',
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
                    'catatan'                 => $item['catatan'] ?? 'Kekurangan stok produksi',
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
