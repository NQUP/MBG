<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\RiwayatStok;
use App\Models\Supplier;
use App\Models\Satuan;
use App\Models\PermintaanPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['supplier', 'user'])->latest();
        if ($request->filled('tanggal_dari')) $query->whereDate('tanggal_masuk', '>=', $request->tanggal_dari);
        if ($request->filled('tanggal_sampai')) $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
        $barangMasuks = $query->paginate(15)->withQueryString();
        return view('gudang.barang-masuk.index', compact('barangMasuks'));
    }

    public function create()
    {
        $suppliers  = Supplier::where('is_active', true)->orderBy('nama_supplier')->get();
        $bahanBakus = BahanBaku::where('is_active', true)->with(['satuan', 'kategori'])->orderBy('nama_bahan')->get();
        $satuans    = Satuan::orderBy('nama_satuan')->get();
        $prList     = PermintaanPembelian::with('detail.bahanBaku')
                        ->where('status', 'Disetujui')->latest()->get();
        return view('gudang.barang-masuk.create', compact('suppliers', 'bahanBakus', 'satuans', 'prList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'              => 'required|exists:suppliers,id',
            'permintaan_pembelian_id'  => 'nullable|exists:permintaan_pembelian,id',
            'tanggal_masuk'            => 'required|date',
            'keterangan'               => 'nullable|string',
            'items'                    => 'required|array|min:1',
            'items.*.bahan_baku_id'    => 'required|exists:bahan_baku,id',
            'items.*.jumlah'           => 'required|numeric|min:0.01',
            'items.*.satuan_id'        => 'required|exists:satuan,id',
            'items.*.harga_satuan'     => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $barangMasuk = BarangMasuk::create([
                'kode_transaksi'           => BarangMasuk::generateKode(),
                'supplier_id'              => $request->supplier_id,
                'permintaan_pembelian_id'  => $request->permintaan_pembelian_id,
                'user_id'                  => auth()->id(),
                'tanggal_masuk'            => $request->tanggal_masuk,
                'keterangan'               => $request->keterangan,
            ]);
            foreach ($request->items as $item) {
                DetailBarangMasuk::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'bahan_baku_id'   => $item['bahan_baku_id'],
                    'jumlah'          => $item['jumlah'],
                    'satuan_id'       => $item['satuan_id'],
                    'harga_satuan'    => $item['harga_satuan'] ?? 0,
                ]);
                $bahan      = BahanBaku::find($item['bahan_baku_id']);
                $stokLama   = $bahan->stok_saat_ini;
                $stokBaru   = $stokLama + $item['jumlah'];
                $bahan->update(['stok_saat_ini' => $stokBaru]);
                RiwayatStok::catat(
                    $bahan->id, 'masuk', $barangMasuk->id, 'barang_masuk',
                    $item['jumlah'], $stokLama, $stokBaru,
                    'Barang masuk: ' . $barangMasuk->kode_transaksi
                );
            }
            // Auto Selesai PR jika semua item sudah diterima
            if ($request->permintaan_pembelian_id) {
                $pr = PermintaanPembelian::with('detail')->find($request->permintaan_pembelian_id);
                if ($pr) {
                    $totalItem    = $pr->detail->count();
                    $totalDiterima = DetailBarangMasuk::whereHas(
                        'barangMasuk',
                        fn($q) => $q->where('permintaan_pembelian_id', $pr->id)
                    )->count();
                    if ($totalItem > 0 && $totalDiterima >= $totalItem) {
                        $pr->update(['status' => 'Selesai']);
                    }
                }
            }
        });
        return redirect()->route('gudang.barang-masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat dan stok telah diperbarui.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load('detail.bahanBaku.satuan', 'supplier', 'user', 'permintaanPembelian');
        return view('gudang.barang-masuk.show', compact('barangMasuk'));
    }

    public function getPrDetail($id)
    {
        $pr = PermintaanPembelian::with('detail.bahanBaku.satuan')->findOrFail($id);
        return response()->json($pr->detail->map(fn($d) => [
            'bahan_baku_id'  => $d->bahan_baku_id,
            'nama_bahan'     => $d->bahanBaku->nama_bahan,
            'jumlah'         => $d->jumlah,
            'satuan_id'      => $d->satuan_id,
            'satuan'         => $d->satuan->singkatan,
            'stok_saat_ini'  => $d->bahanBaku->stok_saat_ini,
        ]));
    }
}
