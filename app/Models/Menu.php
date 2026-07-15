<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = ['kode_menu', 'nama_menu', 'jenis_menu', 'deskripsi', 'stok_saat_ini', 'stok_minimum', 'satuan_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'stok_saat_ini' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
    ];

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function produksiSebagaiMakanan()
    {
        return $this->hasMany(ProduksiHarian::class, 'menu_makanan_id');
    }

    public function produksiSebagaiMinuman()
    {
        return $this->hasMany(ProduksiHarian::class, 'menu_minuman_id');
    }

    public function produksiSebagaiBuah()
    {
        return $this->hasMany(ProduksiHarian::class, 'menu_buah_id');
    }

    public static function generateKode(string $jenis): string
    {
        $prefix = match($jenis) {
            'makanan' => 'MKN',
            'minuman' => 'MIN',
            'buah'    => 'BUH',
            default   => 'MNU',
        };
        $last = static::where('jenis_menu', $jenis)->orderByDesc('id')->first();
        $number = $last ? ((int) substr($last->kode_menu, 3)) + 1 : 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}