@extends('layouts.app')
@section('title', 'Permintaan Pembelian')
@section('page_title', 'Permintaan Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-cart-plus me-2"></i>Permintaan Pembelian</h4>
    <a href="{{ route('permintaan-pembelian.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>Buat Permintaan
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Jumlah Item</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $pr)
                <tr>
                    <td><code>{{ $pr->nomor_permintaan }}</code></td>
                    <td>{{ $pr->tanggal->format('d/m/Y') }}</td>
                    <td>{!! \App\Models\PermintaanPembelian::statusBadge($pr->status) !!}</td>
                    <td><span class="badge bg-secondary">{{ $pr->detail->count() }} item</span></td>
                    <td>{{ $pr->user->name ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('permintaan-pembelian.show', $pr) }}" class="btn btn-sm btn-outline-primary btn-action" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(in_array($pr->status, ['Draft', 'Ditolak']))
                        <a href="{{ route('permintaan-pembelian.edit', $pr) }}" class="btn btn-sm btn-outline-info btn-action" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endif
                        @if($pr->status === 'Draft')
                        <form method="POST" action="{{ route('permintaan-pembelian.destroy', $pr) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus" data-label="PR {{ $pr->nomor_permintaan }}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada permintaan pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())<div class="card-footer">{{ $data->links() }}</div>@endif
</div>
@endsection
