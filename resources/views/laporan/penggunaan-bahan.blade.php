@extends('layouts.app')
@section('title', 'Laporan Penggunaan Bahan')
@section('page_title', 'Laporan Penggunaan Bahan')
@section('content')
<div class="page-header"><h4><i class="bi bi-gear me-2"></i>Laporan Penggunaan Bahan Baku</h4></div>

<div class="card mb-3"><div class="card-body py-3">
<form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="dari" class="form-control" value="{{ $dari }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Bahan Baku</label>
        <select name="bahan_baku_id" class="form-select">
            <option value="">-- Semua --</option>
            @foreach($bahanBakus as $b)
            <option value="{{ $b->id }}" {{ request('bahan_baku_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bahan }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="{{ route('laporan.penggunaan-bahan') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>
<div class="row g-2 mt-2">
    <div class="col-md-12 d-flex gap-2">
        <a href="{{ route('laporan.penggunaan-bahan.pdf', request()->query()) }}" class="btn btn-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
        <a href="{{ route('laporan.penggunaan-bahan.excel', request()->query()) }}" class="btn btn-success"><i class="bi bi-file-excel"></i> Export Excel</a>
    </div>
</div>
</div></div>

<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Tanggal</th><th>Bahan Baku</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
    <tbody>
    @forelse($riwayats as $r)
    <tr>
        <td>{{ $r->tanggal->format('d/m/Y') }}</td>
        <td class="fw-semibold">{{ $r->bahanBaku->nama_bahan ?? '-' }}</td>
        <td>{{ $r->bahanBaku->kategori->nama_kategori ?? '-' }}</td>
        <td>{{ number_format($r->jumlah, 2) }}</td>
        <td>{{ $r->keterangan ?? '-' }}</td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
@endsection