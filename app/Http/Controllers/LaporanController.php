<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\ProduksiHarian;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $bahanBakus = BahanBaku::with(['kategori', 'satuan'])
            ->where('is_active', true)
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori_id', $request->kategori))
            ->orderBy('nama_bahan')
            ->get();

        $kategoris = \App\Models\KategoriBahan::orderBy('nama_kategori')->get();

        return view('laporan.stok', compact('bahanBakus', 'kategoris'));
    }

    public function produksi(Request $request)
    {
        $dari    = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai  = $request->get('sampai', now()->format('Y-m-d'));

        $produksis = ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah', 'user'])
            ->whereBetween('tanggal_produksi', [$dari, $sampai])
            ->orderBy('tanggal_produksi')
            ->get();

        $totalSiswa = $produksis->sum('jumlah_siswa');

        return view('laporan.produksi', compact('produksis', 'totalSiswa', 'dari', 'sampai'));
    }

    public function barangMasuk(Request $request)
    {
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        $barangMasuks = BarangMasuk::with(['supplier', 'user', 'detail.bahanBaku'])
            ->whereBetween('tanggal_masuk', [$dari, $sampai])
            ->orderBy('tanggal_masuk')
            ->get();

        return view('laporan.barang-masuk', compact('barangMasuks', 'dari', 'sampai'));
    }
}