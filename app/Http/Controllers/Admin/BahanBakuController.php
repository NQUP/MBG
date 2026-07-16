<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\KategoriBahan;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::with(['kategori', 'satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_bahan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_bahan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $bahanBakus = $query->latest()->get();
        $kategoris  = KategoriBahan::orderBy('nama_kategori')->get();

        return view('admin.bahan-baku.index', compact('bahanBakus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBahan::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        return view('admin.bahan-baku.create', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan'   => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori_bahan,id',
            'satuan_id'    => 'required|exists:satuan,id',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli'   => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        BahanBaku::create([
            'kode_bahan'   => BahanBaku::generateKode(),
            'nama_bahan'   => $request->nama_bahan,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'stok_saat_ini'=> 0,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli'   => $request->harga_beli ?? 0,
            'keterangan'   => $request->keterangan,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        $kategoris = KategoriBahan::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        return view('admin.bahan-baku.edit', compact('bahanBaku', 'kategoris', 'satuans'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'nama_bahan'   => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori_bahan,id',
            'satuan_id'    => 'required|exists:satuan,id',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli'   => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        $bahanBaku->update([
            'nama_bahan'   => $request->nama_bahan,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli'   => $request->harga_beli ?? 0,
            'keterangan'   => $request->keterangan,
            'is_active'    => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        if ($bahanBaku->resep()->exists() || $bahanBaku->detailBarangMasuk()->exists()) {
            return back()->with('error', 'Bahan baku tidak dapat dihapus karena memiliki riwayat penggunaan.');
        }

        $bahanBaku->delete();
        return redirect()->route('admin.bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}