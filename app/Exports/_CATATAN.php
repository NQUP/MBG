<?php
/**
 * Adapter ini mengabstraksi Maatwebsite\Excel.
 * Ketika library sudah ter-install via:
 *   composer require maatwebsite/excel
 *
 * Hapus class ini dan gunakan facade Excel::download() langsung.
 * Tiap *Export class di namespace App\Exports sudah implement
 * interface FromCollection + WithHeadings + WithStyles + ShouldAutoSize,
 * sehingga tidak ada kode yang perlu diubah di controller.
 */