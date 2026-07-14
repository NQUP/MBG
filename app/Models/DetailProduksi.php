<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    protected $table = 'detail_produksi';

    protected $fillable = ['produksi_harian_id', 'bahan_baku_id', 'jumlah_dibutuhkan', 'satuan_id'];

    protected $casts = ['jumlah_dibutuhkan' => 'decimal:3'];

    public function produksiHarian()
    {
        return $this->belongsTo(ProduksiHarian::class);
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
