@extends('layouts.app')
@section('title', 'Edit Permintaan Pembelian')
@section('page_title', 'Edit Permintaan Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-pencil-square me-2"></i>Edit: {{ $permintaanPembelian->nomor_permintaan }}</h4>
    <a href="{{ route('permintaan-pembelian.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<form method="POST" action="{{ route('permintaan-pembelian.update', $permintaanPembelian) }}" id="formPR">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam me-2 text-success"></i>Detail Bahan</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="tambahBaris()">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Baris
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table no-datatable mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width:240px;">Bahan Baku</th>
                                    <th style="min-width:100px;">Stok Saat Ini</th>
                                    <th style="min-width:100px;">Stok Minimum</th>
                                    <th style="min-width:110px;">Jumlah</th>
                                    <th style="min-width:100px;">Satuan</th>
                                    <th>Catatan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="detailBody">
                                @php $idx = 0; @endphp
                                @foreach($permintaanPembelian->detail as $detail)
                                <tr id="baris-{{ $idx }}">
                                    <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $detail->id }}">
                                    <td>
                                        <select name="items[{{ $idx }}][bahan_baku_id]" class="form-select bahan-select" required onchange="pilihBahan(this, {{ $idx }})">
                                            <option value="">-- Pilih Bahan --</option>
                                            @foreach($bahanBakus as $b)
                                                <option value="{{ $b->id }}"
                                                    data-stok="{{ $b->stok_saat_ini }}"
                                                    data-min="{{ $b->stok_minimum }}"
                                                    data-satuan="{{ $b->satuan->singkatan ?? '' }}"
                                                    data-satuan-id="{{ $b->satuan_id }}"
                                                    {{ $detail->bahan_baku_id === $b->id ? 'selected' : '' }}>
                                                    {{ $b->nama_bahan }} ({{ $b->satuan->singkatan ?? '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="badge-{{ $idx }}" class="mt-1"></div>
                                    </td>
                                    <td><span id="stok-{{ $idx }}" class="fw-semibold text-muted">-</span></td>
                                    <td><span id="min-{{ $idx }}" class="fw-semibold text-muted">-</span></td>
                                    <td><input type="number" name="items[{{ $idx }}][jumlah]" class="form-control" step="0.01" min="0.01" value="{{ $detail->jumlah }}" required></td>
                                    <td>
                                        <select name="items[{{ $idx }}][satuan_id]" id="satuan-{{ $idx }}" class="form-select satuan-select" required>
                                            @foreach($satuans as $s)
                                                <option value="{{ $s->id }}" {{ $detail->satuan_id === $s->id ? 'selected' : '' }}>{{ $s->singkatan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[{{ $idx }}][catatan]" class="form-control" value="{{ $detail->catatan }}"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris('{{ $idx }}')"><i class="bi bi-trash"></i></button></td>
                                </tr>
                                @php $idx++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Informasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $permintaanPembelian->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $permintaanPembelian->keterangan) }}</textarea>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="bi bi-info-circle me-1"></i>Status saat ini: {!! \App\Models\PermintaanPembelian::statusBadge($permintaanPembelian->status) !!}</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                        <a href="{{ route('permintaan-pembelian.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    window.barisIndex = @json($idx);
    window.bahanData = @json($bahanBakus->map(fn($b) => [
        'id' => $b->id,
        'nama' => $b->nama_bahan,
        'stok' => (float) $b->stok_saat_ini,
        'min' => (float) $b->stok_minimum,
        'satuan' => $b->satuan->singkatan ?? '',
        'satuan_id' => $b->satuan_id,
    ]));
    window.satuanData = @json($satuans->map(fn($s) => ['id' => $s->id, 'singkatan' => $s->singkatan]));
</script>
<script src="/js/permintaan-pembelian.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.bahan-select').forEach(function(sel) {
        const match = sel.name.match(/items\[(\d+)\]/);
        if (match) pilihBahan(sel, parseInt(match[1]));
    });
});
</script>
@endpush



