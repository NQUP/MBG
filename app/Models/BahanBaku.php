<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'kode_bahan', 'nama_bahan', 'kategori_id', 'satuan_id',
        'stok_saat_ini', 'stok_minimum', 'harga_beli', 'keterangan', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'stok_saat_ini' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'harga_beli' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBahan::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }

    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    public function detailProduksi()
    {
        return $this->hasMany(DetailProduksi::class);
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class);
    }

    public function isStokMinimum(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    public static function generateKode(): string
    {
        $last = static::orderByDesc('id')->first();
        $number = $last ? ((int) substr($last->kode_bahan, 3)) + 1 : 1;
        return 'BHN' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
