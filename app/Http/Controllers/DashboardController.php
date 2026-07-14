<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BarangMasuk;
use App\Models\ProduksiHarian;
use App\Models\Menu;
use App\Models\User;
use App\Models\PermintaanPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin()) {
            $data['total_bahan']    = BahanBaku::where('is_active', true)->count();
            $data['total_menu']     = Menu::where('is_active', true)->count();
            $data['total_user']     = User::where('is_active', true)->count();
            $data['total_produksi'] = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->count();
            $data['stok_minimum']   = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
            $data['produksi_bulanan'] = ProduksiHarian::selectRaw('DATE(tanggal_produksi) as tanggal, SUM(jumlah_siswa) as total')
                ->whereMonth('tanggal_produksi', now()->month)
                ->groupBy('tanggal_produksi')
                ->orderBy('tanggal_produksi')
                ->get();
            $data['pr_draft']       = PermintaanPembelian::where('status', 'Draft')->count();
            $data['pr_diajukan']    = PermintaanPembelian::where('status', 'Diajukan')->count();
            $data['pr_selesai']     = PermintaanPembelian::where('status', 'Selesai')->whereMonth('created_at', now()->month)->count();
        } elseif ($user->isPetugasGudang()) {
            $data['stok_bahan']     = BahanBaku::with(['kategori', 'satuan'])->where('is_active', true)->get();
            $data['stok_minimum']   = BahanBaku::where('is_active', true)->whereColumn('stok_saat_ini', '<=', 'stok_minimum')->with(['kategori', 'satuan'])->get();
            $data['barang_masuk_hari_ini'] = BarangMasuk::whereDate('tanggal_masuk', today())->count();
            $data['barang_masuk_bulan']    = BarangMasuk::whereMonth('tanggal_masuk', now()->month)->count();
        } elseif ($user->isPetugasDapur()) {
            $data['produksi_hari_ini'] = ProduksiHarian::with(['menuMakanan', 'menuMinuman', 'menuBuah'])->whereDate('tanggal_produksi', today())->get();
            $data['produksi_minggu']   = ProduksiHarian::whereBetween('tanggal_produksi', [now()->startOfWeek(), now()->endOfWeek()])->sum('jumlah_siswa');
            $data['total_siswa_bulan'] = ProduksiHarian::whereMonth('tanggal_produksi', now()->month)->sum('jumlah_siswa');
        }

        return view('dashboard.index', compact('data', 'user'));
    }
}