<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::with(['kategori', 'satuan'])->where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_bahan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_bahan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filter') && $request->filter === 'minimum') {
            $query->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
        }

        $bahanBakus   = $query->orderBy('nama_bahan')->paginate(20)->withQueryString();
        $totalMinimum = BahanBaku::where('is_active', true)->whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();

        return view('gudang.stok.index', compact('bahanBakus', 'totalMinimum'));
    }

    public function riwayat(BahanBaku $bahanBaku, Request $request)
    {
        $riwayat = $bahanBaku->riwayatStok()
            ->when($request->filled('tanggal_dari'), fn($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai))
            ->latest()
            ->paginate(20);

        return view('gudang.stok.riwayat', compact('bahanBaku', 'riwayat'));
    }
}