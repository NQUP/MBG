<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ResepController;
use App\Http\Controllers\Gudang\BarangMasukController;
use App\Http\Controllers\Gudang\StokController;
use App\Http\Controllers\Gudang\StockOpnameController;
use App\Http\Controllers\Dapur\ProduksiController;
use App\Http\Controllers\PermintaanPembelianController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show', 'create', 'edit']);
        Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        Route::get('satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::post('satuan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::put('satuan/{satuan}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('satuan/{satuan}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
        Route::resource('bahan-baku', BahanBakuController::class)->parameters(['bahan-baku' => 'bahanBaku'])->except(['show']);
        Route::resource('menu', MenuController::class)->except(['show']);
        Route::get('resep', [ResepController::class, 'index'])->name('resep.index');
        Route::get('resep/{menu}', [ResepController::class, 'show'])->name('resep.show');
        Route::post('resep/{menu}', [ResepController::class, 'store'])->name('resep.store');
        Route::put('resep/detail/{resep}', [ResepController::class, 'update'])->name('resep.update');
        Route::delete('resep/detail/{resep}', [ResepController::class, 'destroy'])->name('resep.destroy');
        Route::get('laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok.admin');
    });

    // LAPORAN (admin)
    Route::middleware('role:admin')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('produksi', [LaporanController::class, 'produksi'])->name('produksi');
        Route::get('barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
    });

    // GUDANG
    Route::middleware('role:petugas_gudang')->prefix('gudang')->name('gudang.')->group(function () {
        Route::get('stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('stok/{bahanBaku}/riwayat', [StokController::class, 'riwayat'])->name('stok.riwayat');
        Route::get('barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
        Route::get('barang-masuk/create', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
        Route::post('barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
        Route::get('barang-masuk/{barangMasuk}', [BarangMasukController::class, 'show'])->name('barang-masuk.show');
        Route::get('stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::get('stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
        Route::post('stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
        Route::get('stock-opname/{stockOpname}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
        Route::post('stock-opname/{stockOpname}/update-detail', [StockOpnameController::class, 'updateDetail'])->name('stock-opname.update-detail');
        Route::post('stock-opname/{stockOpname}/selesaikan', [StockOpnameController::class, 'selesaikan'])->name('stock-opname.selesaikan');
        // Permintaan Pembelian
    });

    // PERMINTAAN PEMBELIAN (admin & gudang: kelola)
    Route::middleware('role:admin,petugas_gudang')->prefix('permintaan-pembelian')->name('permintaan-pembelian.')->group(function () {
        Route::get('/', [PermintaanPembelianController::class, 'index'])->name('index');
        Route::get('{permintaanPembelian}', [PermintaanPembelianController::class, 'show'])->name('show');
        Route::get('{permintaanPembelian}/edit', [PermintaanPembelianController::class, 'edit'])->name('edit');
        Route::put('{permintaanPembelian}', [PermintaanPembelianController::class, 'update'])->name('update');
        Route::delete('{permintaanPembelian}', [PermintaanPembelianController::class, 'destroy'])->name('destroy');
        Route::post('{permintaanPembelian}/ubah-status', [PermintaanPembelianController::class, 'ubahStatus'])->name('ubah-status');
    });

    // PERMINTAAN PEMBELIAN (gudang & dapur: buat baru)
    Route::middleware('role:petugas_gudang,petugas_dapur')->prefix('permintaan-pembelian')->name('permintaan-pembelian.')->group(function () {
        Route::get('create', [PermintaanPembelianController::class, 'create'])->name('create');
        Route::post('/', [PermintaanPembelianController::class, 'store'])->name('store');
    });

    // API routes (any auth user)
    Route::get('api/permintaan-pembelian/bahan-info', [PermintaanPembelianController::class, 'getBahanInfo'])->name('permintaan-pembelian.bahan-info');
    Route::get('api/barang-masuk/pr-detail/{id}', [BarangMasukController::class, 'getPrDetail'])->name('barang-masuk.pr-detail');

    // DAPUR
    Route::middleware('role:petugas_dapur')->prefix('dapur')->name('dapur.')->group(function () {
        Route::get('produksi', [ProduksiController::class, 'index'])->name('produksi.index');
        Route::get('produksi/create', [ProduksiController::class, 'create'])->name('produksi.create');
        Route::post('produksi', [ProduksiController::class, 'store'])->name('produksi.store');
        Route::get('produksi/{produksi}', [ProduksiController::class, 'show'])->name('produksi.show');
        Route::get('api/resep', [ProduksiController::class, 'getResep'])->name('produksi.resep');
        Route::post('produksi/ajukan-permintaan', [ProduksiController::class, 'ajukanPermintaan'])->name('produksi.ajukan-permintaan');
    });
});
