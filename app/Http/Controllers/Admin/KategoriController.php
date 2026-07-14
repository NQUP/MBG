<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBahan;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBahan::withCount('bahanBaku')->latest()->paginate(15);
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_bahan',
            'keterangan'    => 'nullable|string',
        ]);

        KategoriBahan::create($request->only(['nama_kategori', 'keterangan']));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriBahan $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_bahan,nama_kategori,' . $kategori->id,
            'keterangan'    => 'nullable|string',
        ]);

        $kategori->update($request->only(['nama_kategori', 'keterangan']));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBahan $kategori)
    {
        if ($kategori->bahanBaku()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh bahan baku.');
        }

        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}