<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class PenggunaanBahanExport extends BaseExcelExport
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Tanggal', 'Bahan Baku', 'Kategori', 'Jumlah', 'Keterangan'];
    }

    public function collection(): array
    {
        return $this->rows->map(fn ($r) => [
            $r->tanggal->format('d/m/Y'),
            $r->bahanBaku->nama_bahan ?? '-',
            $r->bahanBaku->kategori->nama_kategori ?? '-',
            (float) $r->jumlah,
            $r->keterangan ?? '-',
        ])->all();
    }
}