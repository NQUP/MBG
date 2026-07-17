@extends('layouts.app')
@section('title', 'Input Barang Masuk')
@section('page_title', 'Input Barang Masuk')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-arrow-down-circle-fill me-2"></i>Input Barang Masuk</h4>
        <a href="{{ route('gudang.barang-masuk.index') }}" class="btn btn-outline-secondary"><i
                class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    <form method="POST" action="{{ route('gudang.barang-masuk.store') }}" id="formBarangMasuk">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                {{-- Informasi Transaksi --}}
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Informasi Transaksi</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Supplier *</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Masuk *</label>
                                <input type="date" name="tanggal_masuk" class="form-control"
                                    value="{{ old('tanggal_masuk', today()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Berdasarkan Permintaan Pembelian <span
                                        class="text-muted">(opsional)</span></label>
                                <select name="permintaan_pembelian_id" id="prSelect" class="form-select"
                                    onchange="loadPrDetail(this.value)">
                                    <option value="">-- Tanpa PR (Manual) --</option>
                                    @foreach ($prList as $pr)
                                        <option value="{{ $pr->id }}"
                                            {{ old('permintaan_pembelian_id') == $pr->id ? 'selected' : '' }}>
                                            {{ $pr->nomor_permintaan }} - {{ $pr->tanggal->format('d/m/Y') }}
                                            ({{ $pr->detail->count() }} item)
                                        </option>
                                    @endforeach
                                </select>
                                <div id="prInfo" class="alert alert-success mt-2 d-none">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <strong>PR dipilih!</strong> Detail bahan telah dimuat otomatis. Anda dapat mengubah
                                    jumlah sesuai kebutuhan.
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Bahan Masuk --}}
                <div class="card mt-3">
                    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                        <span>Detail Bahan Masuk</span>
                        <button type="button" class="btn btn-sm btn-success" onclick="tambahBaris()">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table no-datatable mb-0" id="tabelDetail">
                                <thead>
                                    <tr>
                                        <th>Bahan Baku</th>
                                        <th style="width:120px;">Jumlah</th>
                                        <th style="width:100px;">Satuan</th>
                                        <th style="width:140px;">Harga Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="detailBody">
                                    <tr id="baris-0">
                                        <td>
                                            <select name="items[0][bahan_baku_id]" class="form-select bahan-select"
                                                required>
                                                <option value="">-- Pilih Bahan --</option>
                                                @foreach ($bahanBakus as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama_bahan }}
                                                        ({{ $b->satuan->singkatan ?? '' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="items[0][jumlah]" class="form-control"
                                                step="0.01" min="0.01" required></td>
                                        <td>
                                            <select name="items[0][satuan_id]" class="form-select" required>
                                                @foreach ($satuans as $s)
                                                    <option value="{{ $s->id }}">{{ $s->singkatan }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="items[0][harga_satuan]" class="form-control"
                                                step="0.01" min="0" value="0"></td>
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
                    <div class="card-body">
                        <h6 class="fw-semibold text-muted mb-3">Ringkasan</h6>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Total Item</span>
                            <strong id="totalItem">1</strong>
                        </div>
                        <div class="mt-4 d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Simpan &amp; Update Stok
                            </button>
                            <a href="{{ route('gudang.barang-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                        <div class="mt-3 p-2 bg-light rounded">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Stok bahan baku akan langsung
                                diperbarui setelah disimpan.</small>
                        </div>
                        @if ($prList->count() > 0)
                            <div class="mt-3 p-2 bg-success bg-opacity-10 rounded border border-success border-opacity-25">
                                <small class="text-success"><i
                                        class="bi bi-cart-check me-1"></i><strong>{{ $prList->count() }}</strong>
                                    Permintaan Pembelian "Disetujui" tersedia.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@php
    $bahanOptions = $bahanBakus
        ->map(fn($b) => '<option value="' . $b->id . '">' . e($b->nama_bahan) . ' (' . e($b->satuan->singkatan) . ')</option>')
        ->implode('');
    $satuanData = $satuans->map(fn($s) => ['id' => $s->id, 'singkatan' => $s->singkatan])->values();
@endphp

@push('scripts')
    <script>
        window.barisIndex = 1;
        window.bahanOptions = @json($bahanOptions);
        window.satuanData = @json($satuanData);
    </script>
    <script src="/js/barang-masuk-create.js"></script>
@endpush
