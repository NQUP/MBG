<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBahan extends Model
{
    protected $table = 'kategori_bahan';

    protected $fillable = ['nama_kategori', 'keterangan'];

    public function bahanBaku()
    {
        return $this->hasMany(BahanBaku::class, 'kategori_id');
    }
}
