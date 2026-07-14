@extends('layouts.app')
@section('title', 'Bahan Baku')
@section('page_title', 'Kelola Bahan Baku')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-box-seam me-2"></i>Kelola Bahan Baku</h4>
    <a href="{{ route('admin.bahan-baku.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>Tambah Bahan
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode / nama bahan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Kode</th><th>Nama Bahan</th><th>Kategori</th><th>Stok Saat Ini</th><th>Stok Min.</th><th>Satuan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($bahanBakus as $b)
                <tr>
                    <td><code>{{ $b->kode_bahan }}</code></td>
                    <td class="fw-semibold">{{ $b->nama_bahan }}</td>
                    <td><span class="badge bg-secondary">{{ $b->kategori->nama_kategori }}</span></td>
                    <td class="{{ $b->stok_saat_ini <= $b->stok_minimum ? 'stok-warning' : 'stok-ok' }}">
                        {{ number_format($b->stok_saat_ini, 2) }}
                        @if($b->stok_saat_ini <= $b->stok_minimum)
                            <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Stok di bawah minimum!"></i>
                        @endif
                    </td>
                    <td>{{ number_format($b->stok_minimum, 2) }}</td>
                    <td>{{ $b->satuan->singkatan }}</td>
                    <td><span class="badge {{ $b->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $b->is_active ? 'Aktif' : 'Non-aktif' }}</span></td>
                    <td>
                        <a href="{{ route('admin.bahan-baku.edit', $b) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.bahan-baku.destroy', $b) }}" class="d-inline"
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada bahan baku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bahanBakus->hasPages())<div class="card-footer">{{ $bahanBakus->links() }}</div>@endif
</div>
@endsection
