@extends('layouts.app')
@section('title', 'Buat Permintaan Pembelian')
@section('page_title', 'Buat Permintaan Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-cart-plus-fill me-2"></i>Buat Permintaan Pembelian</h4>
    <a href="{{ route('permintaan-pembelian.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<form method="POST" action="{{ route('permintaan-pembelian.store') }}" id="formPR">
    @csrf
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
                        <table class="table no-datatable mb-0" id="tabelDetail">
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
                                <tr id="baris-0">
                                    <td>
                                        <select name="items[0][bahan_baku_id]" class="form-select bahan-select" required onchange="pilihBahan(this, 0)">
                                            <option value="">-- Pilih Bahan --</option>
                                            @foreach($bahanBakus as $b)
                                                <option value="{{ $b->id }}"
                                                    data-stok="{{ $b->stok_saat_ini }}"
                                                    data-min="{{ $b->stok_minimum }}"
                                                    data-satuan="{{ $b->satuan->singkatan ?? '' }}"
                                                    data-satuan-id="{{ $b->satuan_id }}">
                                                    {{ $b->nama_bahan }} ({{ $b->satuan->singkatan ?? '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="badge-0" class="mt-1"></div>
                                    </td>
                                    <td><span id="stok-0" class="fw-semibold text-muted">-</span></td>
                                    <td><span id="min-0" class="fw-semibold text-muted">-</span></td>
                                    <td><input type="number" name="items[0][jumlah]" class="form-control" step="0.01" min="0.01" required></td>
                                    <td>
                                        <select name="items[0][satuan_id]" id="satuan-0" class="form-select satuan-select" required>
                                            @foreach($satuans as $s)
                                                <option value="{{ $s->id }}">{{ $s->singkatan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[0][catatan]" class="form-control" placeholder="opsional"></td>
                                    <td></td>
                                </tr>
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
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', today()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Simpan Permintaan
                        </button>
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
    window.barisIndex = 1;
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
document.getElementById('formPR').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#detailBody tr');
    if (rows.length === 0) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Harap tambahkan minimal satu bahan baku.' });
        return;
    }
    let valid = true;
    rows.forEach(row => {
        const sel = row.querySelector('.bahan-select');
        if (sel && !sel.value) { valid = false; }
    });
    if (!valid) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Harap pilih bahan baku pada setiap baris.' });
    }
});
</script>
@endpush



