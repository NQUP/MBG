<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stok';

    protected $fillable = [
        'bahan_baku_id', 'tanggal', 'jenis_transaksi',
        'referensi_id', 'referensi_tabel', 'jumlah',
        'stok_sebelum', 'stok_sesudah', 'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:3',
        'stok_sebelum' => 'decimal:2',
        'stok_sesudah' => 'decimal:2',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public static function catat(
        int $bahanBakuId,
        string $jenisTransaksi,
        int $referensiId,
        string $referensiTabel,
        float $jumlah,
        float $stokSebelum,
        float $stokSesudah,
        string $keterangan = ''
    ): static {
        return static::create([
            'bahan_baku_id'   => $bahanBakuId,
            'tanggal'         => today(),
            'jenis_transaksi' => $jenisTransaksi,
            'referensi_id'    => $referensiId,
            'referensi_tabel' => $referensiTabel,
            'jumlah'          => $jumlah,
            'stok_sebelum'    => $stokSebelum,
            'stok_sesudah'    => $stokSesudah,
            'keterangan'      => $keterangan,
        ]);
    }
}
