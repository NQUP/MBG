@extends('layouts.app')
@section('title', 'Kelola Menu')
@section('page_title', 'Kelola Menu')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-journal-richtext me-2"></i>Kelola Menu</h4>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Tambah
            Menu</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Menu</th>
                        <th>Jenis</th>
                        <th>Deskripsi</th>
                        <th>Stok</th><th>Resep</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $m)
                        <tr>
                            <td><code>{{ $m->kode_menu }}</code></td>
                            <td class="fw-semibold">{{ $m->nama_menu }}</td>
                            <td>
                                @if ($m->jenis_menu === 'makanan')
                                    <span class="badge bg-success">
                                        <i class="fas fa-utensils me-1"></i> Makanan
                                    </span>
                                @elseif($m->jenis_menu === 'minuman')
                                    <span class="badge bg-info text-dark">
                                        <i class="fa-solid fa-glass-water me-1"></i> Minuman
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-apple-alt me-1"></i> Buah
                                    </span>
                                @endif
                            </td>
                            <td>{{ Str::limit($m->deskripsi, 40) ?? '-' }}</td>
                            <td>
                                @if ($m->jenis_menu === 'makanan')
                                    <a href="{{ route('admin.resep.show', $m) }}"
                                        class="btn btn-sm btn-outline-success btn-action">
                                        <i class="bi bi-card-list me-1"></i>Resep
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td><span
                                    class="badge {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $m->is_active ? 'Aktif' : 'Non-aktif' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.menu.edit', $m) }}"
                                    class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $m) }}" class="d-inline" <button
                                    type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i
                                        class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($menus->hasPages())
            <div class="card-footer">{{ $menus->links() }}</div>
        @endif
    </div>
@endsection



