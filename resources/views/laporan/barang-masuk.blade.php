@extends('layouts.app')
@section('title', 'Laporan Barang Masuk')
@section('page_title', 'Laporan Barang Masuk')
@section('content')
<div class="page-header"><h4><i class="bi bi-arrow-down-circle me-2"></i>Laporan Penerimaan Barang</h4></div>

<div class="card mb-3"><div class="card-body py-2">
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
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-select">
            <option value="">-- Semua --</option>
            @foreach($suppliers as $sup)
            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->nama_supplier }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="{{ route('laporan.barang-masuk') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>
<div class="row g-2 mt-2">
    <div class="col-md-12 d-flex gap-2">
        <a href="{{ route('laporan.barang-masuk.pdf', request()->query()) }}" class="btn btn-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
        <a href="{{ route('laporan.barang-masuk.excel', request()->query()) }}" class="btn btn-success"><i class="bi bi-file-excel"></i> Export Excel</a>
    </div>
</div>
</div></div>

<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead><tr><th>Kode</th><th>Tanggal</th><th>Supplier</th><th>Item</th><th>Keterangan</th><th>Petugas</th></tr></thead>
    <tbody>
    @forelse($barangMasuks as $bm)
    <tr>
        <td><code>{{ $bm->kode_transaksi }}</code></td>
        <td>{{ $bm->tanggal_masuk->format('d/m/Y') }}</td>
        <td>{{ $bm->supplier->nama_supplier ?? '-' }}</td>
        <td>{{ $bm->detail->count() }}</td>
        <td>{{ $bm->keterangan ?? '-' }}</td>
        <td>{{ $bm->user->name }}</td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
@endsection