@extends('layouts.app')
@section('title', 'Laporan Barang Masuk')
@section('page_title', 'Laporan Barang Masuk')
@section('content')
<div class="page-header"><h4><i class="bi bi-arrow-down-circle me-2"></i>Laporan Barang Masuk</h4></div>
<div class="card mb-3"><div class="card-body py-2"><form method="GET" class="row g-2"><div class="col-md-4"><input type="date" name="dari" class="form-control" value="{{ $dari }}"></div><div class="col-md-4"><input type="date" name="sampai" class="form-control" value="{{ $sampai }}"></div><div class="col-md-4"><button class="btn btn-primary w-100">Filter</button></div></form></div></div>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Kode</th><th>Tanggal</th><th>Supplier</th><th>Item</th><th>Petugas</th></tr></thead><tbody>@forelse($barangMasuks as $bm)<tr><td><code>{{ $bm->kode_transaksi }}</code></td><td>{{ $bm->tanggal_masuk->format('d/m/Y') }}</td><td>{{ $bm->supplier->nama_supplier }}</td><td>{{ $bm->detail->count() }}</td><td>{{ $bm->user->name }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>@endforelse</tbody></table></div></div>
@endsection


