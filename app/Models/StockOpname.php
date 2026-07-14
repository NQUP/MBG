<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opname';

    protected $fillable = ['kode_opname', 'user_id', 'tanggal_opname', 'keterangan', 'status'];

    protected $casts = ['tanggal_opname' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailStockOpname::class);
    }

    public static function generateKode(): string
    {
        $prefix = 'SO' . date('Ymd');
        $count = static::whereDate('created_at', today())->count();
        return $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
