<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    protected $table = 'detail_barang_masuk';

    protected $fillable = ['barang_masuk_id', 'bahan_baku_id', 'jumlah', 'satuan_id', 'harga_satuan'];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
