@extends('layouts.app')
@section('title', 'Resep - ' . $menu->nama_menu)
@section('page_title', 'Resep: ' . $menu->nama_menu)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-card-list me-2"></i>Resep: {{ $menu->nama_menu }}</h4>
        <small class="text-muted">Kode: {{ $menu->kode_menu }}</small>
    </div>
    <a href="{{ route('admin.resep.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-3">
    {{-- Daftar Bahan dalam Resep --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul me-2 text-success"></i>Daftar Bahan ({{ $menu->resep->count() }} bahan)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Nama Bahan</th><th>Jumlah/Porsi</th><th>Satuan</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($menu->resep as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $r->bahanBaku->nama_bahan }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.resep.update', $r) }}" class="d-flex gap-2 align-items-center">
                                    @csrf @method('PUT')
                                    <input type="number" name="jumlah_per_porsi" class="form-control" style="width:90px"
                                           value="{{ $r->jumlah_per_porsi }}" step="0.001" min="0.001" required>
                                    <select name="satuan_id" class="form-select" style="width:80px">
                                        @foreach($satuans as $s)
                                            <option value="{{ $s->id }}" {{ $r->satuan_id == $s->id ? 'selected' : '' }}>{{ $s->singkatan }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary" title="Simpan"><i class="bi bi-check"></i></button>
                                </form>
                            </td>
                            <td>{{ $r->satuan->singkatan }}</td>
                            <td>{{ $r->keterangan ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.resep.destroy', $r) }}" class="d-inline"
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action btn-hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada bahan dalam resep ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Form Tambah Bahan --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-plus-circle me-2"></i>Tambah Bahan ke Resep
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.resep.store', $menu) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Bahan Baku *</label>
                        <select name="bahan_baku_id" class="form-select @error('bahan_baku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahanBakus as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan->singkatan }})</option>
                            @endforeach
                        </select>
                        @error('bahan_baku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah per Porsi *</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_per_porsi" class="form-control @error('jumlah_per_porsi') is-invalid @enderror"
                                   step="0.001" min="0.001" placeholder="0.000" required>
                            <select name="satuan_id" class="form-select" style="max-width:100px" required>
                                @foreach($satuans as $s)
                                    <option value="{{ $s->id }}">{{ $s->singkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('jumlah_per_porsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle me-1"></i>Tambah ke Resep
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-semibold text-muted">Preview untuk <span class="text-success" id="previewSiswa">100</span> Siswa</h6>
                <div class="mb-2">
                    <input type="range" class="form-range" min="50" max="1000" step="50" value="100" id="sliderSiswa">
                </div>
                <div id="previewTable">
                    @foreach($menu->resep as $r)
                    <div class="d-flex justify-content-between py-1 border-bottom" data-jumlah="{{ $r->jumlah_per_porsi }}">
                        <small>{{ $r->bahanBaku->nama_bahan }}</small>
                        <small class="fw-bold text-success preview-total">{{ number_format($r->jumlah_per_porsi * 100, 2) }} {{ $r->satuan->singkatan }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/js/admin-resep.js"></script>
@endpush




