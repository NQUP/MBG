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
        <thead><tr><th>Kode</th><th>Tanggal</th><th>Supplier</th><th>Jumlah Item</th><th>Keterangan</th><th>Petugas</th></tr></thead>
        <tbody>
        @foreach($barangMasuks as $bm)
        <tr>
            <td>{{ $bm->kode_transaksi }}</td><td>{{ $bm->tanggal_masuk->format('d/m/Y') }}</td>
            <td>{{ $bm->supplier->nama_supplier ?? '-' }}</td><td>{{ $bm->detail->count() }}</td>
            <td>{{ $bm->keterangan ?? '-' }}</td><td>{{ $bm->user->name ?? '-' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">Halaman <span class="pageNumber"></span></div>
</body></html>
