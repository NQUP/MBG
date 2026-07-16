<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class PermintaanBahanExport extends BaseExcelExport
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Tanggal', 'Makanan', 'Minuman', 'Buah', 'Jumlah Siswa', 'Petugas'];
    }

    public function collection(): array
    {
        return $this->rows->map(fn ($p) => [
            $p->tanggal_produksi->format('d/m/Y'),
            $p->menuMakanan->nama_menu ?? '-',
            $p->menuMinuman->nama_bahan ?? '-',
            $p->menuBuah->nama_bahan ?? '-',
            (int) $p->jumlah_siswa,
            $p->user->name ?? '-',
        ])->all();
    }
}