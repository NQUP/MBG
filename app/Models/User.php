<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugasGudang(): bool
    {
        return $this->role === 'petugas_gudang';
    }

    public function isPetugasDapur(): bool
    {
        return $this->role === 'petugas_dapur';
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function produksiHarian()
    {
        return $this->hasMany(ProduksiHarian::class);
    }

    public function stockOpname()
    {
        return $this->hasMany(StockOpname::class);
    }
}
