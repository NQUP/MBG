<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPembelianDetail extends Model
{
    protected $table = 'permintaan_pembelian_detail';

    protected $fillable = ['permintaan_pembelian_id', 'bahan_baku_id', 'jumlah', 'satuan_id', 'catatan'];

    protected $casts = ['jumlah' => 'decimal:2'];

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class);
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