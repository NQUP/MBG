<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Resep;
use App\Models\BahanBaku;
use App\Models\Satuan;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function index()
    {
        $menus = Menu::where('jenis_menu', 'makanan')->with('resep.bahanBaku.satuan')->where('is_active', true)->get();
        return view('admin.resep.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        $menu->load('resep.bahanBaku', 'resep.satuan');
        $bahanBakus = BahanBaku::where('is_active', true)->with('satuan')->orderBy('nama_bahan')->get();
        $satuans    = Satuan::orderBy('nama_satuan')->get();
        return view('admin.resep.show', compact('menu', 'bahanBakus', 'satuans'));
    }

    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'bahan_baku_id'   => 'required|exists:bahan_baku,id',
            'jumlah_per_porsi'=> 'required|numeric|min:0.001',
            'satuan_id'       => 'required|exists:satuan,id',
            'keterangan'      => 'nullable|string',
        ]);

        // Cek apakah bahan sudah ada di resep ini
        $exists = Resep::where('menu_id', $menu->id)
            ->where('bahan_baku_id', $request->bahan_baku_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bahan baku tersebut sudah ada dalam resep ini.');
        }

        Resep::create([
            'menu_id'          => $menu->id,
            'bahan_baku_id'    => $request->bahan_baku_id,
            'jumlah_per_porsi' => $request->jumlah_per_porsi,
            'satuan_id'        => $request->satuan_id,
            'keterangan'       => $request->keterangan,
        ]);

        return back()->with('success', 'Bahan berhasil ditambahkan ke resep.');
    }

    public function update(Request $request, Resep $resep)
    {
        $request->validate([
            'jumlah_per_porsi' => 'required|numeric|min:0.001',
            'satuan_id'        => 'required|exists:satuan,id',
            'keterangan'       => 'nullable|string',
        ]);

        $resep->update($request->only(['jumlah_per_porsi', 'satuan_id', 'keterangan']));

        return back()->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(Resep $resep)
    {
        $menuId = $resep->menu_id;
        $resep->delete();
        return back()->with('success', 'Bahan berhasil dihapus dari resep.');
    }
}