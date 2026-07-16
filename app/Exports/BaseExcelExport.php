<?php

namespace App\Exports;

use ZipArchive;

/**
 * ============================================================
 * ADAPTER SEMENTARA � akan diganti Maatwebsite\Excel
 * ============================================================
 * Saat library sudah ter-install (composer require maatwebsite/excel),
 * lakukan langkah berikut:
 *
 * 1. Hapus file ini (BaseExcelExport.php).
 * 2. Di setiap *Export class, ganti extends BaseExcelExport dengan:
 *      implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
 * 3. Di LaporanController, ganti:
 *      (new StokExport($rows))->download('nama.xlsx')
 *    menjadi:
 *      return Excel::download(new StokExport($bahanBakus), 'nama.xlsx');
 *
 * Tidak ada logika query atau data yang perlu diubah.
 * ============================================================
 */
abstract class BaseExcelExport
{
    abstract public function headings(): array;
    abstract public function collection(): array;

    public function download(string $filename): never
    {
        $temp = tempnam(sys_get_temp_dir(), 'xlsx');
        $this->build($temp);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($temp));
        readfile($temp);
        unlink($temp);
        exit;
    }

    private function build(string $path): void
    {
        $headings   = $this->headings();
        $rows       = $this->collection();
        $allStrings = array_unique(array_merge($headings, ...array_map('array_values', $rows)));
        $strMap     = array_flip(array_values(array_map('strval', $allStrings)));

        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create xlsx file');
        }

        $zip->addFromString('[Content_Types].xml',       $this->contentTypesXml());
        $zip->addFromString('_rels/.rels',               $this->relsXml());
        $zip->addFromString('xl/workbook.xml',           $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels',$this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml',             $this->stylesXml());
        $zip->addFromString('xl/sharedStrings.xml',      $this->sharedStringsXml($allStrings));
        $zip->addFromString('xl/worksheets/sheet1.xml',  $this->sheetXml($headings, $rows, $strMap));
        $zip->close();
    }

    private function sharedStringsXml(array $strings): string
    {
        $cnt = count($strings);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $xml .= "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$cnt}\" uniqueCount=\"{$cnt}\">";
        foreach ($strings as $s) {
            $xml .= '<si><t>' . htmlspecialchars((string)($s ?? ''), ENT_XML1, 'UTF-8') . '</t></si>';
        }
        return $xml . '</sst>';
    }

    private function sheetXml(array $headings, array $rows, array $strMap): string
    {
        $colWidths = array_fill(0, count($headings), 18);
        foreach ($headings as $i => $h) {
            $colWidths[$i] = max($colWidths[$i], mb_strlen((string)$h) + 4);
        }
        foreach ($rows as $row) {
            foreach (array_values($row) as $i => $val) {
                if (isset($colWidths[$i])) {
                    $colWidths[$i] = max($colWidths[$i], mb_strlen((string)($val ?? '')) + 2);
                }
            }
        }

        $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><cols>';
        foreach ($colWidths as $i => $w) {
            $xml .= sprintf('<col min="%d" max="%d" width="%d" bestFit="1" customWidth="1"/>', $i+1, $i+1, min($w, 60));
        }
        $xml .= '</cols><sheetData>';

        // Header row � style index 1 (bold)
        $xml .= '<row r="1">';
        foreach ($headings as $ci => $h) {
            $ref = $this->colRef($ci) . '1';
            $xml .= sprintf('<c r="%s" t="s" s="1"><v>%d</v></c>', $ref, $strMap[$h]);
        }
        $xml .= '</row>';

        // Data rows
        foreach ($rows as $ri => $row) {
            $rowNum = $ri + 2;
            $xml .= "<row r=\"{$rowNum}\">";
            foreach (array_values($row) as $ci => $val) {
                $ref = $this->colRef($ci) . $rowNum;
                if (is_int($val) || is_float($val)) {
                    $xml .= sprintf('<c r="%s" t="n"><v>%s</v></c>', $ref, $val);
                } else {
                    $idx = $strMap[(string)($val ?? '')] ?? 0;
                    $xml .= sprintf('<c r="%s" t="s"><v>%d</v></c>', $ref, $idx);
                }
            }
            $xml .= '</row>';
        }

        return $xml . '</sheetData></worksheet>';
    }

    private function colRef(int $index): string
    {
        $s = '';
        for ($n = $index; $n >= 0; $n = intdiv($n, 26) - 1) {
            $s = chr(65 + $n % 26) . $s;
        }
        return $s;
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets><sheet name="Laporan" sheetId="1" r:id="rId1"/></sheets>
</workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="2">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><name val="Calibri"/></font>
  </fonts>
  <fills count="2">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
  </fills>
  <borders count="1">
    <border><left/><right/><top/><bottom/><diagonal/></border>
  </borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <cellXfs count="2">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
  </cellXfs>
</styleSheet>';
    }
}