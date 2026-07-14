@extends('layouts.app')
@section('title', 'Detail Produksi')
@section('page_title', 'Detail Produksi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-fire me-2"></i>Produksi {{ $produksi->tanggal_produksi->format('d/m/Y') }}</h4>
        <small class="text-muted">Diinput oleh {{ $produksi->user->name }}</small>
    </div>
    <a href="{{ route('dapur.produksi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Ringkasan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted">Makanan</th><td>{{ $produksi->menuMakanan->nama_menu }}</td></tr>
                    <tr><th class="text-muted">Minuman</th><td>{{ $produksi->menuMinuman->nama_menu }}</td></tr>
                    <tr><th class="text-muted">Buah</th><td>{{ $produksi->menuBuah->nama_menu }}</td></tr>
                    <tr><th class="text-muted">Jumlah Siswa</th><td><strong>{{ number_format($produksi->jumlah_siswa) }}</strong></td></tr>
                    <tr><th class="text-muted">Status</th><td><span class="badge bg-success">Dikonfirmasi</span></td></tr>
                    <tr><th class="text-muted">Keterangan</th><td>{{ $produksi->keterangan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Bahan Baku Terpakai</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Bahan Baku</th><th>Jumlah Dibutuhkan</th><th>Satuan</th></tr></thead>
                    <tbody>
                        @forelse($produksi->detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                            <td>{{ number_format($d->jumlah_dibutuhkan, 3) }}</td>
                            <td>{{ $d->satuan->singkatan }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada detail.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection