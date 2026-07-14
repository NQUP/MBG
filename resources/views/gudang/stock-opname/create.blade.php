@extends('layouts.app')
@section('title', 'Buat Stock Opname')
@section('page_title', 'Buat Stock Opname')

@section('content')
<div class="page-header"><h4><i class="bi bi-clipboard-plus me-2"></i>Buat Stock Opname Baru</h4></div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Sistem akan membuat daftar stock opname berisi seluruh bahan baku aktif dengan stok sistem saat ini.
            Anda kemudian akan mengisi stok fisik hasil penghitungan di gudang.
        </div>
        <form method="POST" action="{{ route('gudang.stock-opname.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
            </div>
            <div class="alert alert-warning">
                <strong>{{ $bahanBakus->count() }}</strong> bahan baku akan dimasukkan ke dalam stock opname ini.
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-clipboard-plus me-1"></i>Buat Stock Opname</button>
                <a href="{{ route('gudang.stock-opname.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection