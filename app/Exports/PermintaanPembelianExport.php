<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class PermintaanPembelianExport extends BaseExcelExport
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Nomor', 'Tanggal', 'Status', 'Jumlah Item', 'Keterangan', 'Petugas'];
    }

    public function collection(): array
    {
        return $this->rows->map(fn ($d) => [
            $d->nomor_permintaan,
            $d->tanggal->format('d/m/Y'),
            $d->status,
            $d->detail->count(),
            $d->keterangan ?? '-',
            $d->user->name ?? '-',
        ])->all();
    }
}