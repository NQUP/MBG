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
                        <table class="table mb-0" id="tabelDetail">
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
let barisIndex = 1;
const bahanData = @json($bahanBakus->map(fn($b) => [
    'id'         => $b->id,
    'nama'       => $b->nama_bahan,
    'stok'       => (float) $b->stok_saat_ini,
    'min'        => (float) $b->stok_minimum,
    'satuan'     => $b->satuan->singkatan ?? '',
    'satuan_id'  => $b->satuan_id,
]));
const satuanData = @json($satuans->map(fn($s) => ['id' => $s->id, 'singkatan' => $s->singkatan]));

function getBahanOpts() {
    return '<option value="">-- Pilih Bahan --</option>' +
        bahanData.map(b => `<option value="${b.id}" data-stok="${b.stok}" data-min="${b.min}" data-satuan="${b.satuan}" data-satuan-id="${b.satuan_id}">${b.nama} (${b.satuan})</option>`).join('');
}
function getSatuanOpts() {
    return satuanData.map(s => `<option value="${s.id}">${s.singkatan}</option>`).join('');
}

function tambahBaris() {
    const i = barisIndex++;
    const tr = document.createElement('tr');
    tr.id = 'baris-' + i;
    tr.innerHTML = `
        <td>
            <select name="items[${i}][bahan_baku_id]" class="form-select bahan-select" required onchange="pilihBahan(this, ${i})">
                ${getBahanOpts()}
            </select>
            <div id="badge-${i}" class="mt-1"></div>
        </td>
        <td><span id="stok-${i}" class="fw-semibold text-muted">-</span></td>
        <td><span id="min-${i}" class="fw-semibold text-muted">-</span></td>
        <td><input type="number" name="items[${i}][jumlah]" class="form-control" step="0.01" min="0.01" required></td>
        <td><select name="items[${i}][satuan_id]" id="satuan-${i}" class="form-select satuan-select" required>${getSatuanOpts()}</select></td>
        <td><input type="text" name="items[${i}][catatan]" class="form-control" placeholder="opsional"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(${i})"><i class="bi bi-trash"></i></button></td>
    `;
    document.getElementById('detailBody').appendChild(tr);
}

function hapusBaris(i) {
    const el = document.getElementById('baris-' + i);
    if (el) el.remove();
}

function pilihBahan(select, i) {
    const opt = select.options[select.selectedIndex];
    const stokEl  = document.getElementById('stok-' + i);
    const minEl   = document.getElementById('min-' + i);
    const badgeEl = document.getElementById('badge-' + i);
    const satuanEl = document.getElementById('satuan-' + i);

    if (!opt || !opt.value) {
        stokEl.textContent = '-';
        minEl.textContent  = '-';
        badgeEl.innerHTML  = '';
        return;
    }
    const stok   = parseFloat(opt.dataset.stok) || 0;
    const min    = parseFloat(opt.dataset.min)  || 0;
    const satuan = opt.dataset.satuan || '';
    const satId  = opt.dataset.satuanId || '';

    stokEl.textContent = stok.toFixed(2) + ' ' + satuan;
    minEl.textContent  = min.toFixed(2)  + ' ' + satuan;
    stokEl.className   = stok <= min ? 'fw-semibold text-danger' : 'fw-semibold text-success';

    if (stok <= min) {
        badgeEl.innerHTML = '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Perlu Pembelian</span>';
    } else {
        badgeEl.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Stok Aman</span>';
    }

    // Auto-set satuan
    if (satuanEl && satId) satuanEl.value = satId;
}

document.getElementById('formPR').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#detailBody tr');
    if (rows.length === 0) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Harap tambahkan minimal satu bahan baku.' });
        return;
    }
    // Check empty bahan
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



