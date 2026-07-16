@extends('layouts.app')
@section('title', 'Input Produksi Harian')
@section('page_title', 'Input Produksi Harian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-fire me-2"></i>Input Produksi Harian</h4>
    <a href="{{ route('dapur.produksi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<form method="POST" action="{{ route('dapur.produksi.store') }}" id="formProduksi">
    @csrf
    <div class="row g-3">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Form Produksi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal *</label>
                            <input type="date" name="tanggal_produksi" class="form-control" value="{{ old('tanggal_produksi', today()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Siswa *</label>
                            <input type="number" name="jumlah_siswa" id="jumlahSiswa" class="form-control" value="{{ old('jumlah_siswa') }}" min="1" required oninput="updatePreview()">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Makanan *</label>
                            <select name="menu_makanan_id" id="menuMakanan" class="form-select" required onchange="loadResep()">
                                <option value="">-- Pilih Makanan --</option>
                                @foreach($menuMakanan as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_menu }}</option>
                                @endforeach
                            </select>
                        </div>
<div class="col-md-6">
                            <label class="form-label">Minuman *</label>
                            <select name="bahan_minuman_id" id="bahanMinuman" class="form-select" required onchange="updatePreview()">
                                <option value="">-- Pilih Minuman --</option>
                                @foreach($bahanMinuman as $b)
                                    <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}" data-satuan="{{ $b->satuan->singkatan ?? '' }}" data-nama="{{ $b->nama_bahan }}">{{ $b->nama_bahan }} ({{ $b->satuan->singkatan ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buah *</label>
                            <select name="bahan_buah_id" id="bahanBuah" class="form-select" required onchange="updatePreview()">
                                <option value="">-- Pilih Buah --</option>
                                @foreach($bahanBuah as $b)
                                    <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}" data-satuan="{{ $b->satuan->singkatan ?? '' }}" data-nama="{{ $b->nama_bahan }}">{{ $b->nama_bahan }} ({{ $b->satuan->singkatan ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-success text-white fw-semibold">
                    <i class="bi bi-calculator me-2"></i>Preview Kebutuhan Bahan
                </div>
                <div class="card-body" id="previewResep">
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
                        Pilih menu makanan dan isi jumlah siswa untuk melihat kebutuhan bahan.
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-success w-100" type="button" id="btnSimpanProduksi">
                        <i class="bi bi-check-circle me-1"></i>Simpan Produksi & Potong Stok
                    </button>
                    <div id="peringatanStok" class="alert alert-danger d-none mt-2 mb-0 py-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>Masih terdapat bahan baku yang tidak mencukupi.
                    </div>
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Sistem akan mengambil resep (BOM), menghitung kebutuhan = jumlah per porsi * jumlah siswa, lalu mengurangi stok otomatis.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
    window.produksiResepUrl = '{{ route('dapur.produksi.resep') }}';
</script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="/js/produksi-create.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var kekurangan = @json(session('error_stok'));
    var userRole = '{{ auth()->user()->role }}';
    var html = '<div style="text-align:left;">';
    html += '<p class="mb-3 text-muted"><i class="bi bi-exclamation-triangle text-danger me-1"></i> Bahan yang kurang:</p>';
    html += '<div style="max-height:320px;overflow-y:auto;">';
    kekurangan.forEach(function(item) {
        html += '<div style="border-bottom:1px solid #eee;padding:10px 0;">';
        html += '<strong style="color:#dc3545;">' + item.nama_bahan + '</strong>';
        html += '<div style="display:grid;grid-template-columns:auto 1fr;gap:4px 12px;font-size:13px;margin-top:6px;">';
        html += '<span style="color:#666;">Dibutuhkan :</span><span style="font-weight:600;">' + parseFloat(item.kebutuhan).toFixed(2) + ' ' + item.satuan + '</span>';
        html += '<span style="color:#666;">Tersedia   :</span><span style="font-weight:600;">' + parseFloat(item.stok).toFixed(2) + ' ' + item.satuan + '</span>';
        html += '<span style="color:#666;">Kurang     :</span><span style="font-weight:600;color:#dc3545;">' + parseFloat(item.kekurangan).toFixed(2) + ' ' + item.satuan + '</span>';
        html += '</div></div>';
    });
    html += '</div></div>';

    @if(auth()->user()->role === 'petugas_dapur')
    Swal.fire({
        title: '\u26a0\ufe0f Produksi Tidak Dapat Diproses',
        html: html,
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-send me-1"></i> Kirim Permintaan Pembelian',
        cancelButtonText: 'Tutup',
        confirmButtonColor: '#1a7f5a',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
    }).then(function(result) {
        if (result.isConfirmed) {
var items = kekurangan.map(function(item) {
                return {
                    bahan_baku_id: item.bahan_baku_id,
                    jumlah: item.kekurangan,
                    satuan_id: item.satuan_id,
                    catatan: item.nama_bahan + ' - Kekurangan stok produksi',
                };
            });

            fetch('{{ route("dapur.produksi.ajukan-permintaan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ items: items })
            })
            .then(function(res) {
                if (!res.ok) {
                    return res.json().then(function(err) {
                        throw new Error(err.message || 'Validation error');
                    });
                }
                return res.json();
            })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: data.message,
                        confirmButtonColor: '#1a7f5a',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: data.message || 'Terjadi kesalahan saat mengirim permintaan.',
                        confirmButtonColor: '#dc3545',
                    });
                }
            })
            .catch(function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: err.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545',
                });
            });
        }
    });
    @else
    Swal.fire({
        title: '\u26a0\ufe0f Produksi Tidak Dapat Diproses',
        html: html,
        icon: 'error',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#6c757d',
    });
    @endif
});
</script>
@endpush


