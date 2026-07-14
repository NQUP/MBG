<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';

    protected $fillable = ['nama_satuan', 'singkatan'];

    public function bahanBaku()
    {
        return $this->hasMany(BahanBaku::class);
    }

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }
}
