<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProduksiHarian extends Model
{
    protected $table = 'produksi_harian';

    protected $fillable = [
        'tanggal_produksi', 'menu_makanan_id', 'menu_minuman_id', 'menu_buah_id',
        'jumlah_siswa', 'user_id', 'status', 'keterangan'
    ];

    protected $casts = ['tanggal_produksi' => 'date'];

    public function menuMakanan()
    {
        return $this->belongsTo(Menu::class, 'menu_makanan_id');
    }

public function menuMinuman()
    {
        return $this->belongsTo(BahanBaku::class, 'menu_minuman_id');
    }

    public function menuBuah()
    {
        return $this->belongsTo(BahanBaku::class, 'menu_buah_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailProduksi::class);
    }

    public static function generateKode(): string
    {
        $prefix = 'PRD' . date('Ymd');
        $last = static::where('id', 'like', $prefix . '%')->orderByDesc('id')->first();
        $count = static::whereDate('created_at', today())->count();
        return $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
