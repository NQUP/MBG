@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('page_title', 'Barang Masuk')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-arrow-down-circle me-2"></i>Barang Masuk</h4>
    <a href="{{ route('gudang.barang-masuk.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Input Barang Masuk</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label mb-1">Dari</label><input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}"></div>
            <div class="col-md-4"><label class="form-label mb-1">Sampai</label><input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}"></div>
            <div class="col-md-4"><button type="submit" class="btn btn-primary w-100 mt-3">Filter</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Tanggal</th><th>Supplier</th><th>Diinput</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($barangMasuks as $bm)
                <tr>
                    <td><code>{{ $bm->kode_transaksi }}</code></td>
                    <td>{{ $bm->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>{{ $bm->supplier->nama_supplier }}</td>
                    <td>{{ $bm->user->name }}</td>
                    <td>
                        <a href="{{ route('gudang.barang-masuk.show', $bm) }}" class="btn btn-sm btn-outline-primary btn-action">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi barang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangMasuks->hasPages())<div class="card-footer">{{ $barangMasuks->links() }}</div>@endif
</div>
@endsection



