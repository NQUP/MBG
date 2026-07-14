@extends('layouts.app')
@section('title', 'Detail Barang Masuk')
@section('page_title', 'Detail Barang Masuk')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-arrow-down-circle me-2"></i>{{ $barangMasuk->kode_transaksi }}</h4>
        <small class="text-muted">{{ $barangMasuk->tanggal_masuk->format('d F Y') }}</small>
    </div>
    <a href="{{ route('gudang.barang-masuk.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Informasi Transaksi</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Kode</th><td><code>{{ $barangMasuk->kode_transaksi }}</code></td></tr>
                    <tr><th class="text-muted">Tanggal</th><td>{{ $barangMasuk->tanggal_masuk->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Supplier</th><td>{{ $barangMasuk->supplier->nama_supplier }}</td></tr>
                    <tr><th class="text-muted">Diinput</th><td>{{ $barangMasuk->user->name }}</td></tr>
                    @if($barangMasuk->permintaanPembelian)
                    <tr>
                        <th class="text-muted">Berdasarkan PR</th>
                        <td>
                            <a href="{{ route('gudang.permintaan-pembelian.show', $barangMasuk->permintaanPembelian) }}" class="text-decoration-none">
                                <code>{{ $barangMasuk->permintaanPembelian->nomor_permintaan }}</code>
                                <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr><th class="text-muted">Keterangan</th><td>{{ $barangMasuk->keterangan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Detail Bahan Masuk</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Nama Bahan</th><th>Jumlah</th><th>Satuan</th><th>Harga Satuan</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @foreach($barangMasuk->detail as $d)
                        @php $subtotal = $d->jumlah * $d->harga_satuan; $total += $subtotal; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                            <td>{{ number_format($d->jumlah, 2) }}</td>
                            <td>{{ $d->satuan->singkatan }}</td>
                            <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Total</td>
                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection