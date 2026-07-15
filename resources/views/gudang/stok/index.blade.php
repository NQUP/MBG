@extends('layouts.app')
@section('title', 'Stok Bahan Baku')
@section('page_title', 'Stok Bahan Baku')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-box-seam me-2"></i>Stok Bahan Baku</h4>
    @if($totalMinimum > 0)
        <span class="badge bg-danger fs-6"><i class="bi bi-exclamation-triangle me-1"></i>{{ $totalMinimum }} Stok Minimum</span>
    @endif
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6"><input type="text" name="search" class="form-control" placeholder="Cari nama / kode bahan..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="filter" class="form-select">
                    <option value="">-- Semua Stok --</option>
                    <option value="minimum" {{ request('filter') === 'minimum' ? 'selected' : '' }}>Stok Minimum</option>
                </select>
            </div>
            <div class="col-md-3"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Nama Bahan</th><th>Kategori</th><th>Stok Saat Ini</th><th>Stok Min.</th><th>Satuan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($bahanBakus as $b)
                <tr class="{{ $b->stok_saat_ini <= $b->stok_minimum ? 'table-danger' : '' }}">
                    <td><code>{{ $b->kode_bahan }}</code></td>
                    <td class="fw-semibold">{{ $b->nama_bahan }}</td>
                    <td><span class="badge bg-secondary">{{ $b->kategori->nama_kategori }}</span></td>
                    <td class="{{ $b->stok_saat_ini <= $b->stok_minimum ? 'stok-warning' : 'stok-ok' }} fw-bold">
                        {{ number_format($b->stok_saat_ini, 2) }}
                    </td>
                    <td>{{ number_format($b->stok_minimum, 2) }}</td>
                    <td>{{ $b->satuan->singkatan }}</td>
                    <td>
                        @if($b->stok_saat_ini <= $b->stok_minimum)
                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Di Bawah Min.</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('gudang.stok.riwayat', $b) }}" class="btn btn-sm btn-outline-primary btn-action">
                            <i class="bi bi-clock-history"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bahanBakus->hasPages())<div class="card-footer">{{ $bahanBakus->links() }}</div>@endif
</div>
@endsection


