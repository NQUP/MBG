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
        <thead><tr><th>Tanggal</th><th>Makanan</th><th>Minuman</th><th>Buah</th><th>Jumlah Siswa</th><th>Petugas</th></tr></thead>
        <tbody>
        @php $totalSiswa = $produksis->sum('jumlah_siswa') @endphp
        @foreach($produksis as $p)
        <tr>
            <td>{{ $p->tanggal_produksi->format('d/m/Y') }}</td><td>{{ $p->menuMakanan->nama_menu ?? '-' }}</td>
            <td>{{ $p->menuMinuman->nama_menu ?? '-' }}</td><td>{{ $p->menuBuah->nama_menu ?? '-' }}</td>
            <td>{{ number_format($p->jumlah_siswa) }}</td><td>{{ $p->user->name ?? '-' }}</td>
        </tr>
        @endforeach
        </tbody>
        <tfoot><tr><th colspan="4">Total Siswa</th><th>{{ number_format($totalSiswa) }}</th><th></th></tr></tfoot>
    </table>
    <div class="footer">Dicetak dari Sistem Inventory MBG - {{ now()->format('d/m/Y H:i') }}</div>
</body></html>
