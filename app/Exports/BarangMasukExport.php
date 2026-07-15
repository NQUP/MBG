<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class BarangMasukExport extends BaseExcelExport
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Kode', 'Tanggal', 'Supplier', 'Jumlah Item', 'Keterangan', 'Petugas'];
    }

    public function collection(): array
    {
        return $this->rows->map(fn ($bm) => [
            $bm->kode_transaksi,
            $bm->tanggal_masuk->format('d/m/Y'),
            $bm->supplier->nama_supplier ?? '-',
            $bm->detail->count(),
            $bm->keterangan ?? '-',
            $bm->user->name ?? '-',
        ])->all();
    }
}