@extends('layouts.app')
@section('title', 'Tambah Menu')
@section('page_title', 'Tambah Menu')
@section('content')
<div class="page-header"><h4><i class="bi bi-journal-plus me-2"></i>Tambah Menu</h4></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.menu.store') }}">
        @csrf
        <div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Menu *</label>
        <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis Menu *</label>
        <select name="jenis_menu" class="form-select" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="makanan" {{ old('jenis_menu', $menu->jenis_menu ?? '') === 'makanan' ? 'selected' : '' }}>🍛 Makanan</option>
            <option value="minuman" {{ old('jenis_menu', $menu->jenis_menu ?? '') === 'minuman' ? 'selected' : '' }}>🥤 Minuman</option>
            <option value="buah" {{ old('jenis_menu', $menu->jenis_menu ?? '') === 'buah' ? 'selected' : '' }}>🍎 Buah</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Menu Aktif</label>
        </div>
    </div>
</div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection