@extends('layouts.app')
@section('title', 'Resep (BOM)')
@section('page_title', 'Kelola Resep')

@section('content')
<div class="page-header"><h4><i class="bi bi-card-list me-2"></i>Resep (Bill of Materials)</h4></div>

<div class="row g-3">
    @forelse($menus as $menu)
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-journal-richtext me-2"></i>{{ $menu->nama_menu }}
                <span class="badge bg-white text-success ms-1">{{ $menu->resep->count() }} bahan</span>
            </div>
            <div class="card-body p-2">
                @if($menu->resep->isEmpty())
                    <p class="text-muted small text-center py-2">Belum ada resep</p>
                @else
                    <ul class="list-unstyled mb-0">
                        @foreach($menu->resep as $r)
                        <li class="py-1 border-bottom d-flex justify-content-between">
                            <span class="small">{{ $r->bahanBaku->nama_bahan }}</span>
                            <span class="small text-muted">{{ $r->jumlah_per_porsi }} {{ $r->satuan->singkatan }}</span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('admin.resep.show', $menu) }}" class="btn btn-sm btn-outline-success w-100">
                    <i class="bi bi-gear me-1"></i>Kelola Resep
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card"><div class="card-body text-center text-muted py-5">
            <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
            Belum ada menu makanan aktif.
            <div class="mt-3"><a href="{{ route('admin.menu.create') }}" class="btn btn-success">Tambah Menu</a></div>
        </div></div>
    </div>
    @endforelse
</div>
@endsection