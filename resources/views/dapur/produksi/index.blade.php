@extends('layouts.app')
@section('title', 'Produksi Harian')
@section('page_title', 'Produksi Harian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-fire me-2"></i>Riwayat Produksi</h4>
    <a href="{{ route('dapur.produksi.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Input Produksi</a>
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
            <thead><tr><th>Tanggal</th><th>Makanan</th><th>Minuman</th><th>Buah</th><th>Siswa</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($produksis as $p)
                <tr>
                    <td>{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                    <td>{{ $p->menuMakanan->nama_menu }}</td>
                    <td>{{ $p->menuMinuman->nama_menu }}</td>
                    <td>{{ $p->menuBuah->nama_menu }}</td>
                    <td><strong>{{ number_format($p->jumlah_siswa) }}</strong></td>
                    <td>
                        <span class="badge {{ $p->status === 'dikonfirmasi' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $p->status === 'dikonfirmasi' ? 'Dikonfirmasi' : 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('dapur.produksi.show', $p) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada produksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produksis->hasPages())<div class="card-footer">{{ $produksis->links() }}</div>@endif
</div>
@endsection




