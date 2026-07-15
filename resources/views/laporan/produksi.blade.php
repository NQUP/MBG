@extends('layouts.app')
@section('title', 'Laporan Produksi')
@section('page_title', 'Laporan Produksi')
@section('content')
<div class="page-header"><h4><i class="bi bi-graph-up me-2"></i>Laporan Produksi</h4></div>
<div class="card mb-3"><div class="card-body py-2"><form method="GET" class="row g-2"><div class="col-md-4"><input type="date" name="dari" class="form-control" value="{{ $dari }}"></div><div class="col-md-4"><input type="date" name="sampai" class="form-control" value="{{ $sampai }}"></div><div class="col-md-4"><button class="btn btn-primary w-100">Filter</button></div></form></div></div>
<div class="alert alert-success"><strong>Total siswa:</strong> {{ number_format($totalSiswa) }} porsi</div>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Tanggal</th><th>Makanan</th><th>Minuman</th><th>Buah</th><th>Siswa</th><th>Petugas</th></tr></thead><tbody>@forelse($produksis as $p)<tr><td>{{ $p->tanggal_produksi->format('d/m/Y') }}</td><td>{{ $p->menuMakanan->nama_menu }}</td><td>{{ $p->menuMinuman->nama_menu }}</td><td>{{ $p->menuBuah->nama_menu }}</td><td>{{ number_format($p->jumlah_siswa) }}</td><td>{{ $p->user->name }}</td></tr>@empty<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>@endforelse</tbody></table></div></div>
@endsection


