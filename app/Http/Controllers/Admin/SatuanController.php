<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::withCount('bahanBaku')->latest()->paginate(15);
        return view('admin.satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:100|unique:satuan',
            'singkatan'   => 'required|string|max:20',
        ]);

        Satuan::create($request->only(['nama_satuan', 'singkatan']));

        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:100|unique:satuan,nama_satuan,' . $satuan->id,
            'singkatan'   => 'required|string|max:20',
        ]);

        $satuan->update($request->only(['nama_satuan', 'singkatan']));

        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->bahanBaku()->exists() || $satuan->resep()->exists()) {
            return back()->with('error', 'Satuan tidak dapat dihapus karena masih digunakan.');
        }

        $satuan->delete();
        return redirect()->route('admin.satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}