<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = ['kode_transaksi', 'supplier_id', 'permintaan_pembelian_id', 'user_id', 'tanggal_masuk', 'keterangan'];

    protected $casts = ['tanggal_masuk' => 'date'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    public static function generateKode(): string
    {
        $prefix = 'BM' . date('Ymd');
        $last = static::where('kode_transaksi', 'like', $prefix . '%')->orderByDesc('id')->first();
        $number = $last ? ((int) substr($last->kode_transaksi, -3)) + 1 : 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
