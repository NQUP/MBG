<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = ['nama_supplier', 'kontak', 'alamat', 'keterangan', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
