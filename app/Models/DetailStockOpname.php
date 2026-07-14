<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailStockOpname extends Model
{
    protected $table = 'detail_stock_opname';

    protected $fillable = [
        'stock_opname_id', 'bahan_baku_id', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan'
    ];

    protected $casts = [
        'stok_sistem' => 'decimal:2',
        'stok_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
