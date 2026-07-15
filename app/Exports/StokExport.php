<?php

namespace App\Exports;

use Illuminate\Support\Collection;

/**
 * Tulis ulang class ini menjadi:
 *
 *   use Maatwebsite\Excel\Concerns\FromCollection;
 *   use Maatwebsite\Excel\Concerns\WithHeadings;
 *   use Maatwebsite\Excel\Concerns\WithStyles;
 *   use Maatwebsite\Excel\Concerns\ShouldAutoSize;
 *   use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
 *
 * class StokExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
 *
 * setelah menjalankan: composer require maatwebsite/excel
 */
class StokExport extends BaseExcelExport
{
    public function __construct(private Collection $rows) {}

    public function headings(): array
    {
        return ['Kode', 'Bahan', 'Kategori', 'Stok Saat Ini', 'Stok Minimum', 'Satuan', 'Status'];
    }

    public function collection(): array
    {
        return $this->rows->map(fn ($b) => [
            $b->kode_bahan,
            $b->nama_bahan,
            $b->kategori->nama_kategori ?? '-',
            (float) $b->stok_saat_ini,
            (float) $b->stok_minimum,
            $b->satuan->singkatan ?? '',
            $b->stok_saat_ini <= $b->stok_minimum ? 'Minimum' : 'Aman',
        ])->all();
    }
}