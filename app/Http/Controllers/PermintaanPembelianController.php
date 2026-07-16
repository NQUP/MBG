<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPembelian;
use App\Models\PermintaanPembelianDetail;
use App\Models\BahanBaku;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermintaanPembelianController extends Controller
{
    public function index()
    {
        $data = PermintaanPembelian::with(['user', 'detail.bahanBaku'])
            ->latest()
            ->get();
        return view('gudang.permintaan-pembelian.index', compact('data'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::with(['kategori', 'satuan'])
            ->where('is_active', true)
            ->orderBy('nama_bahan')
            ->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('gudang.permintaan-pembelian.create', compact('bahanBakus', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'keterangan'  => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.jumlah'        => 'required|numeric|min:0.01',
            'items.*.satuan_id'     => 'required|exists:satuan,id',
            'items.*.catatan'       => 'nullable|string',
        ]);

        $pr = DB::transaction(function () use ($request) {
            $pr = PermintaanPembelian::create([
                'nomor_permintaan' => PermintaanPembelian::generateNomor(),
                'tanggal'          => $request->tanggal,
                'user_id'          => auth()->id(),
                'keterangan'       => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                PermintaanPembelianDetail::create([
                    'permintaan_pembelian_id' => $pr->id,
                    'bahan_baku_id'           => $item['bahan_baku_id'],
                    'jumlah'                  => $item['jumlah'],
                    'satuan_id'               => $item['satuan_id'],
                    'catatan'                 => $item['catatan'] ?? null,
                ]);
            }

            return $pr;
        });

        return redirect()->route('permintaan-pembelian.index')
            ->with('success', 'Permintaan Pembelian ' . $pr->nomor_permintaan . ' berhasil dibuat.');
    }

    public function show(PermintaanPembelian $permintaanPembelian)
    {
        $permintaanPembelian->load(['user', 'detail.bahanBaku.satuan']);
        return view('gudang.permintaan-pembelian.show', compact('permintaanPembelian'));
    }

    public function edit(PermintaanPembelian $permintaanPembelian)
    {
        if (!in_array($permintaanPembelian->status, ['Draft', 'Ditolak'])) {
            return back()->with('error', 'Hanya permintaan dengan status Draft/Ditolak yang dapat diedit.');
        }

        $permintaanPembelian->load('detail.bahanBaku');
        $bahanBakus = BahanBaku::with(['kategori', 'satuan'])
            ->where('is_active', true)
            ->orderBy('nama_bahan')
            ->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('gudang.permintaan-pembelian.edit', compact('permintaanPembelian', 'bahanBakus', 'satuans'));
    }

    public function update(Request $request, PermintaanPembelian $permintaanPembelian)
    {
        if (!in_array($permintaanPembelian->status, ['Draft', 'Ditolak'])) {
            return back()->with('error', 'Hanya permintaan dengan status Draft/Ditolak yang dapat diedit.');
        }

        $request->validate([
            'tanggal'     => 'required|date',
            'keterangan'  => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.id'            => 'nullable|exists:permintaan_pembelian_detail,id',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.jumlah'        => 'required|numeric|min:0.01',
            'items.*.satuan_id'     => 'required|exists:satuan,id',
            'items.*.catatan'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $permintaanPembelian) {
            $permintaanPembelian->update([
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            $existingIds = $permintaanPembelian->detail()->pluck('id')->toArray();
            $submittedIds = collect($request->items)->pluck('id')->filter()->toArray();
            $toDelete = array_diff($existingIds, $submittedIds);
            if (!empty($toDelete)) {
                PermintaanPembelianDetail::whereIn('id', $toDelete)->delete();
            }

            foreach ($request->items as $item) {
                if (!empty($item['id'])) {
                    $detail = PermintaanPembelianDetail::find($item['id']);
                    if ($detail && $detail->permintaan_pembelian_id === $permintaanPembelian->id) {
                        $detail->update([
                            'bahan_baku_id' => $item['bahan_baku_id'],
                            'jumlah'        => $item['jumlah'],
                            'satuan_id'     => $item['satuan_id'],
                            'catatan'       => $item['catatan'] ?? null,
                        ]);
                    }
                } else {
                    PermintaanPembelianDetail::create([
                        'permintaan_pembelian_id' => $permintaanPembelian->id,
                        'bahan_baku_id'           => $item['bahan_baku_id'],
                        'jumlah'                  => $item['jumlah'],
                        'satuan_id'               => $item['satuan_id'],
                        'catatan'                 => $item['catatan'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('permintaan-pembelian.index')
            ->with('success', 'Permintaan Pembelian berhasil diperbarui.');
    }

    public function destroy(PermintaanPembelian $permintaanPembelian)
    {
        if ($permintaanPembelian->status !== 'Draft') {
            return back()->with('error', 'Hanya permintaan dengan status Draft yang dapat dihapus.');
        }

        $nomor = $permintaanPembelian->nomor_permintaan;
        $permintaanPembelian->delete();

        return redirect()->route('permintaan-pembelian.index')
            ->with('success', 'Permintaan Pembelian ' . $nomor . ' berhasil dihapus.');
    }

    public function ubahStatus(Request $request, PermintaanPembelian $permintaanPembelian)
    {
        $request->validate([
            'status_baru' => 'required|in:Draft,Diajukan,Disetujui,Barang Diterima,Selesai,Ditolak',
        ]);

        $statusBaru = $request->status_baru;
        $role = auth()->user()->role;

        // Petugas Gudang: Draft?Diajukan, Disetujui?Barang Diterima, Barang Diterima?Selesai, Ditolak?Draft
        if ($role === 'petugas_gudang') {
            if ($permintaanPembelian->status === 'Draft' && $statusBaru !== 'Diajukan') {
                return back()->with('error', 'Draft hanya dapat diajukan ke Admin.');
            }
            if ($permintaanPembelian->status === 'Disetujui' && $statusBaru !== 'Barang Diterima') {
                return back()->with('error', 'Permintaan disetujui harus menunggu barang diterima.');
            }
            if ($permintaanPembelian->status === 'Barang Diterima' && $statusBaru !== 'Selesai') {
                return back()->with('error', 'Barang sudah diterima, silakan selesaikan permintaan.');
            }
            if ($permintaanPembelian->status === 'Ditolak' && $statusBaru !== 'Draft') {
                return back()->with('error', 'Permintaan ditolak hanya dapat diajukan ulang.');
            }
        }

        // Admin: Diajukan?Disetujui, Diajukan?Ditolak
        if ($role === 'admin') {
            if ($permintaanPembelian->status === 'Diajukan' && !in_array($statusBaru, ['Disetujui', 'Ditolak'])) {
                return back()->with('error', 'Admin hanya dapat menyetujui atau menolak permintaan yang diajukan.');
            }
        }

        $permintaanPembelian->update(['status' => $statusBaru]);

        return redirect()->route('permintaan-pembelian.index')
            ->with('success', 'Status berubah menjadi <strong>' . $statusBaru . '</strong>.');
    }

    public function getBahanInfo(Request $request)
    {
        $bahan = BahanBaku::with(['kategori', 'satuan'])
            ->where('is_active', true)
            ->orderBy('nama_bahan')
            ->get()
            ->map(fn($b) => [
                'id'            => $b->id,
                'nama_bahan'    => $b->nama_bahan,
                'kode_bahan'    => $b->kode_bahan,
                'kategori'      => $b->kategori->nama_kategori ?? '-',
                'stok_saat_ini' => (float) $b->stok_saat_ini,
                'stok_minimum'  => (float) $b->stok_minimum,
                'satuan'        => $b->satuan->singkatan ?? '',
                'perlu_beli'    => $b->stok_saat_ini <= $b->stok_minimum,
            ]);

        return response()->json($bahan);
    }
}