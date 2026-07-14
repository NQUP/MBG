@extends('layouts.app')
@section('title', 'Riwayat Stok')
@section('page_title', 'Riwayat Stok')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-clock-history me-2"></i>Riwayat Stok: {{ $bahanBaku->nama_bahan }}</h4>
        <small class="text-muted">Stok saat ini: <strong>{{ number_format($bahanBaku->stok_saat_ini, 2) }} {{ $bahanBaku->satuan->singkatan }}</strong></small>
    </div>
    <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label small mb-1">Dari Tanggal</label><input type="date" name="tanggal_dari" class="form-control form-control-sm" value="{{ request('tanggal_dari') }}"></div>
            <div class="col-md-4"><label class="form-label small mb-1">Sampai Tanggal</label><input type="date" name="tanggal_sampai" class="form-control form-control-sm" value="{{ request('tanggal_sampai') }}"></div>
            <div class="col-md-4"><button type="submit" class="btn btn-sm btn-primary w-100 mt-3">Filter</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Jenis</th><th>Referensi</th><th>Jumlah</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Keterangan</th></tr></thead>
            <tbody>
                @forelse($riwayat as $r)
                <tr>
                    <td>{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td>
                        @if($r->jenis_transaksi === 'masuk')
                            <span class="badge bg-success"><i class="bi bi-arrow-down-circle me-1"></i>Masuk</span>
                        @elseif($r->jenis_transaksi === 'keluar_produksi')
                            <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Produksi</span>
                        @elseif($r->jenis_transaksi === 'penyesuaian')
                            <span class="badge bg-warning text-dark"><i class="bi bi-arrows-angle-expand me-1"></i>Penyesuaian</span>
                        @else
                            <span class="badge bg-secondary">{{ $r->jenis_transaksi }}</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $r->referensi_tabel }} #{{ $r->referensi_id }}</small></td>
                    <td class="fw-bold">{{ number_format($r->jumlah, 2) }} {{ $bahanBaku->satuan->singkatan }}</td>
                    <td>{{ number_format($r->stok_sebelum, 2) }}</td>
                    <td class="{{ $r->stok_sesudah > $r->stok_sebelum ? 'stok-ok' : 'stok-warning' }}">
                        {{ number_format($r->stok_sesudah, 2) }}
                    </td>
                    <td><small>{{ $r->keterangan ?? '-' }}</small></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayat->hasPages())<div class="card-footer">{{ $riwayat->links() }}</div>@endif
</div>
@endsection