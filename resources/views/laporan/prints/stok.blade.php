<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
        <link href="/css/print.css" rel="stylesheet">
</head>
<body>
    <div class="no-print" style="margin-bottom:10px">
        <button onclick="window.print()" class="btn-print"> Cetak / Simpan PDF</button>
        <a href="{{ URL::previous() }}" style="margin-left:8px;padding:8px 16px;background:#6c757d;color:#fff;text-decoration:none;border-radius:4px;">Kembali</a>
    </div>
    <div class="header">
        <h2>{{ config('app.name') }}</h2>
        <h3>{{ $title }}</h3>
        @if(isset($dari) && isset($sampai))
        <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
        @endif
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }} | Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>    <table>
        <thead><tr><th>Kode</th><th>Bahan</th><th>Kategori</th><th>Stok Saat Ini</th><th>Stok Minimum</th><th>Satuan</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($bahanBakus as $b)
        <tr>
            <td>{{ $b->kode_bahan }}</td><td>{{ $b->nama_bahan }}</td><td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ number_format($b->stok_saat_ini, 2) }}</td><td>{{ number_format($b->stok_minimum, 2) }}</td>
            <td>{{ $b->satuan->singkatan ?? '' }}</td>
            <td>{{ $b->stok_saat_ini <= $b->stok_minimum ? 'Minimum' : 'Aman' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">Halaman <span class="pageNumber"></span></div>
</body></html>
