<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Satuan;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->paginate(15);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('admin.menu.create', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'jenis_menu' => 'required|in:makanan,minuman,buah',
            'deskripsi'  => 'nullable|string',
            'stok_saat_ini' => 'nullable|numeric|min:0',
            'stok_minimum'  => 'nullable|numeric|min:0',
            'satuan_id'     => 'nullable|exists:satuan,id',
        ]);

        Menu::create([
            'kode_menu'  => Menu::generateKode($request->jenis_menu),
            'nama_menu'  => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => $request->boolean('is_active', true),
            'stok_saat_ini' => $request->stok_saat_ini ?? 0,
            'stok_minimum'  => $request->stok_minimum ?? 0,
            'satuan_id'     => $request->satuan_id,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $satuans = Satuan::orderBy('nama_satuan')->get();
        return view('admin.menu.edit', compact('menu', 'satuans'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'jenis_menu' => 'required|in:makanan,minuman,buah',
            'deskripsi'  => 'nullable|string',
            'stok_saat_ini' => 'nullable|numeric|min:0',
            'stok_minimum'  => 'nullable|numeric|min:0',
            'satuan_id'     => 'nullable|exists:satuan,id',
        ]);

        $menu->update([
            'nama_menu'  => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => $request->boolean('is_active'),
            'stok_saat_ini' => $request->stok_saat_ini ?? 0,
            'stok_minimum'  => $request->stok_minimum ?? 0,
            'satuan_id'     => $request->satuan_id,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->resep()->exists()) {
            return back()->with('error', 'Menu tidak dapat dihapus karena memiliki resep terdaftar.');
        }

        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}