<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    protected $table = 'permintaan_pembelian';

    protected $fillable = ['nomor_permintaan', 'tanggal', 'status', 'keterangan', 'user_id'];

    protected $casts = ['tanggal' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PermintaanPembelianDetail::class);
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public static function generateNomor(): string
    {
        $prefix = 'PR-' . now()->format('Ymd') . '-';
        $last = static::where('nomor_permintaan', 'like', $prefix . '%')->orderByDesc('id')->first();
        $number = $last ? ((int) substr($last->nomor_permintaan, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function canTransitionTo(string $targetStatus): bool
    {
        $allowed = [
            'Draft'          => ['Diajukan'],
            'Diajukan'       => ['Disetujui', 'Ditolak'],
            'Disetujui'      => ['Barang Diterima'],
            'Barang Diterima' => ['Selesai'],
            'Selesai'        => [],
            'Ditolak'        => ['Draft'],
        ];
        return in_array($targetStatus, $allowed[$this->status] ?? []);
    }

    public function transitionTo(string $targetStatus): bool
    {
        if (!$this->canTransitionTo($targetStatus)) {
            return false;
        }
        return $this->update(['status' => $targetStatus]);
    }

    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'Draft'          => '<span class="badge bg-secondary">Draft</span>',
            'Diajukan'       => '<span class="badge bg-warning text-dark">Diajukan</span>',
            'Disetujui'      => '<span class="badge bg-success">Disetujui</span>',
            'Barang Diterima' => '<span class="badge bg-primary">Barang Diterima</span>',
            'Selesai'        => '<span class="badge bg-info text-dark">Selesai</span>',
            'Ditolak'        => '<span class="badge bg-danger">Ditolak</span>',
            default          => '<span class="badge bg-secondary">' . $status . '</span>',
        };
    }
}