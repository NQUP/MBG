@extends('layouts.app')
@section('title', 'Stock Opname')
@section('page_title', 'Stock Opname')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-clipboard-check me-2"></i>Stock Opname</h4>
    <a href="{{ route('gudang.stock-opname.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Buat Opname Baru</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Tanggal</th><th>Petugas</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($opnames as $o)
                <tr>
                    <td><code>{{ $o->kode_opname }}</code></td>
                    <td>{{ $o->tanggal_opname->format('d/m/Y') }}</td>
                    <td>{{ $o->user->name }}</td>
                    <td>
                        @if($o->status === 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-warning text-dark">Draft</span>
                        @endif
                    </td>
                    <td><a href="{{ route('gudang.stock-opname.show', $o) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-eye"></i> Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada stock opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($opnames->hasPages())<div class="card-footer">{{ $opnames->links() }}</div>@endif
</div>
@endsection



