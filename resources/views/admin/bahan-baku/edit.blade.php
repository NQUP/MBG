@extends('layouts.app')
@section('title', 'Edit Bahan Baku')
@section('page_title', 'Edit Bahan Baku')
@section('content')
<div class="page-header"><h4><i class="bi bi-box-seam me-2"></i>Edit: {{ $bahanBaku->nama_bahan }}</h4></div>
<div class="card"><div class="card-body">
    <div class="alert alert-info d-flex gap-2 mb-3">
        <i class="bi bi-info-circle"></i>
        <span>Kode Bahan: <strong>{{ $bahanBaku->kode_bahan }}</strong> | Stok Saat Ini: <strong>{{ number_format($bahanBaku->stok_saat_ini, 2) }} {{ $bahanBaku->satuan->singkatan }}</strong></span>
    </div>
    <form method="POST" action="{{ route('admin.bahan-baku.update', $bahanBaku) }}">
        @csrf @method('PUT')
        <div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Bahan *</label>
        <input type="text" name="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror"
               value="{{ old('nama_bahan', $bahanBaku->nama_bahan ?? '') }}" required>
        @error('nama_bahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori *</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ old('kategori_id', $bahanBaku->kategori_id ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Satuan *</label>
        <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror" required>
            <option value="">-- Pilih Satuan --</option>
            @foreach($satuans as $s)
                <option value="{{ $s->id }}" {{ old('satuan_id', $bahanBaku->satuan_id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->nama_satuan }} ({{ $s->singkatan }})</option>
            @endforeach
        </select>
        @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok Minimum *</label>
        <input type="number" name="stok_minimum" step="0.01" min="0" class="form-control @error('stok_minimum') is-invalid @enderror"
               value="{{ old('stok_minimum', $bahanBaku->stok_minimum ?? '0') }}" required>
        @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Beli (Rp)</label>
        <input type="number" name="harga_beli" step="0.01" min="0" class="form-control"
               value="{{ old('harga_beli', $bahanBaku->harga_beli ?? '0') }}" placeholder="0">
    </div>
    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $bahanBaku->keterangan ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $bahanBaku->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Bahan Aktif</label>
        </div>
    </div>
</div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
            <a href="{{ route('admin.bahan-baku.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection