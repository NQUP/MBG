<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px; font-size: 18px; }
        .header p { margin: 2px 0; color: #555; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 5px 8px; text-align: left; font-size: 11px; }
        th { background: #f0f0f0; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
        @media print { body { margin: 15px; } .no-print { display: none; } }
        .btn-print { display: inline-block; padding: 8px 16px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 4px; margin-bottom: 15px; }
    </style>
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
        <thead><tr><th>Tanggal</th><th>Bahan Baku</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
        <tbody>
        @foreach($riwayats as $r)
        <tr>
            <td>{{ $r->tanggal->format('d/m/Y') }}</td><td>{{ $r->bahanBaku->nama_bahan ?? '-' }}</td>
            <td>{{ $r->bahanBaku->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ number_format($r->jumlah, 2) }}</td><td>{{ $r->keterangan ?? '-' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">Halaman <span class="pageNumber"></span></div>
</body></html>