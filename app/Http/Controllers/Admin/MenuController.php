<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
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
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'jenis_menu' => 'required|in:makanan,minuman,buah',
            'deskripsi'  => 'nullable|string',
        ]);

        Menu::create([
            'kode_menu'  => Menu::generateKode($request->jenis_menu),
            'nama_menu'  => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'jenis_menu' => 'required|in:makanan,minuman,buah',
            'deskripsi'  => 'nullable|string',
        ]);

        $menu->update([
            'nama_menu'  => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => $request->boolean('is_active'),
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