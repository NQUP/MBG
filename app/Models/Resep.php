<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';

    protected $fillable = ['menu_id', 'bahan_baku_id', 'jumlah_per_porsi', 'satuan_id', 'keterangan'];

    protected $casts = ['jumlah_per_porsi' => 'decimal:3'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
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
