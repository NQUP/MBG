@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page_title', 'Edit Menu')
@section('content')
<div class="page-header"><h4><i class="bi bi-journal-pen me-2"></i>Edit Menu: {{ $menu->nama_menu }}</h4></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.menu.update', $menu) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Menu *</label>
                <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Menu *</label>
                <select name="jenis_menu" class="form-select" id="jenisMenu" required onchange="toggleStokFields()">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="makanan" {{ old('jenis_menu', $menu->jenis_menu) === 'makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="minuman" {{ old('jenis_menu', $menu->jenis_menu) === 'minuman' ? 'selected' : '' }}>Minuman</option>
                    <option value="buah" {{ old('jenis_menu', $menu->jenis_menu) === 'buah' ? 'selected' : '' }}>Buah</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            </div>
            <div id="stokFields" class="col-12" style="display:{{ in_array(old('jenis_menu', $menu->jenis_menu), ['minuman','buah']) ? 'block' : 'none' }};">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-box-seam me-1"></i>Stok Minuman / Buah</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Stok Saat Ini</label>
                                <input type="number" name="stok_saat_ini" class="form-control" value="{{ old('stok_saat_ini', $menu->stok_saat_ini) }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok Minimum</label>
                                <input type="number" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $menu->stok_minimum) }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Satuan</label>
                                <select name="satuan_id" class="form-select">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach($satuans as $s)
                                        <option value="{{ $s->id }}" {{ old('satuan_id', $menu->satuan_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_satuan }} ({{ $s->singkatan }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i>Stok akan dipotong otomatis saat produksi (1 unit per siswa). Kolom ini hanya untuk menu Minuman & Buah.</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Menu Aktif</label>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
@push('scripts')
<script>
function toggleStokFields() {
    var jenis = document.getElementById('jenisMenu').value;
    document.getElementById('stokFields').style.display = (jenis === 'minuman' || jenis === 'buah') ? 'block' : 'none';
}
</script>
@endpush

