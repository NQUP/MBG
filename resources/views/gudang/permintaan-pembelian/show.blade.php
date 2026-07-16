@extends('layouts.app')
@section('title', 'Detail PR - ' . $permintaanPembelian->nomor_permintaan)
@section('page_title', 'Detail Permintaan Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-file-text me-2"></i>{{ $permintaanPembelian->nomor_permintaan }}</h4>
        <small class="text-muted">{{ $permintaanPembelian->tanggal->format('d F Y') }} &bull; {{ $permintaanPembelian->user->name ?? '-' }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($permintaanPembelian->status === 'Draft')
            <form method="POST" action="{{ route('permintaan-pembelian.ubah-status', $permintaanPembelian) }}" data-confirm="Ajukan permintaan ini untuk disetujui?">
                @csrf
                <input type="hidden" name="status_baru" value="Diajukan">
                <button type="submit" class="btn btn-warning"><i class="bi bi-send me-1"></i>Ajukan</button>
            </form>
            <a href="{{ route('permintaan-pembelian.edit', $permintaanPembelian) }}" class="btn btn-outline-info"><i class="bi bi-pencil me-1"></i>Edit</a>
        @endif
        @if($permintaanPembelian->status === 'Diajukan')
            <form method="POST" action="{{ route('permintaan-pembelian.ubah-status', $permintaanPembelian) }}" data-confirm="Setujui permintaan pembelian ini?">
                @csrf
                <input type="hidden" name="status_baru" value="Disetujui">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Setujui</button>
            </form>
            <form method="POST" action="{{ route('permintaan-pembelian.ubah-status', $permintaanPembelian) }}" data-confirm="Tolak permintaan pembelian ini?">
                @csrf
                <input type="hidden" name="status_baru" value="Ditolak">
                <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i>Tolak</button>
            </form>
        @endif
        @if($permintaanPembelian->status === 'Disetujui')
            <span class="badge bg-success fs-6 align-self-center"><i class="bi bi-check-circle me-1"></i>Disetujui â€“ Siap Barang Masuk</span>
        @endif
        @if($permintaanPembelian->status === 'Ditolak')
            <form method="POST" action="{{ route('permintaan-pembelian.ubah-status', $permintaanPembelian) }}" data-confirm="Kembalikan permintaan ini ke status Draft?">
                @csrf
                <input type="hidden" name="status_baru" value="Draft">
                <button type="submit" class="btn btn-outline-warning"><i class="bi bi-arrow-counterclockwise me-1"></i>Kembalikan ke Draft</button>
            </form>
        @endif
        @if($permintaanPembelian->status === 'Selesai')
            <span class="badge bg-info text-dark fs-6 align-self-center"><i class="bi bi-bag-check me-1"></i>Selesai â€“ Semua Item Diterima</span>
        @endif
        <a href="{{ route('permintaan-pembelian.index') }}" class="btn btn-outline-secondary btn-sm align-self-center"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Informasi PR</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Nomor</th>
                        <td><code>{{ $permintaanPembelian->nomor_permintaan }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal</th>
                        <td>{{ $permintaanPembelian->tanggal->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>{!! \App\Models\PermintaanPembelian::statusBadge($permintaanPembelian->status) !!}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Dibuat Oleh</th>
                        <td>{{ $permintaanPembelian->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Keterangan</th>
                        <td>{{ $permintaanPembelian->keterangan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Jumlah Item</th>
                        <td><span class="badge bg-secondary">{{ $permintaanPembelian->detail->count() }} item</span></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Workflow timeline --}}
        <div class="card mt-3">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-diagram-3 me-2 text-primary"></i>Alur Status</div>
            <div class="card-body py-3">
                @php
                    $statuses = ['Draft', 'Diajukan', 'Disetujui', 'Selesai'];
                    $statusNow = $permintaanPembelian->status;
                    $colors = ['Draft'=>'secondary','Diajukan'=>'warning','Disetujui'=>'success','Selesai'=>'info','Ditolak'=>'danger'];
                @endphp
                @if($statusNow === 'Ditolak')
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger">Ditolak</span>
                        <small class="text-muted">Dapat dikembalikan ke Draft</small>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        @foreach($statuses as $i => $s)
                            <span class="badge bg-{{ $colors[$s] ?? 'secondary' }} {{ $statusNow === $s ? 'opacity-100' : 'opacity-50' }}">{{ $s }}</span>
                            @if($i < count($statuses)-1)
                                <i class="bi bi-arrow-right text-muted small"></i>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-list-ul me-2 text-success"></i>Daftar Bahan ({{ $permintaanPembelian->detail->count() }} item)
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bahan Baku</th>
                            <th class="text-end">Jumlah Diminta</th>
                            <th>Satuan</th>
                            <th class="text-end">Stok Saat Ini</th>
                            <th class="text-end">Stok Minimum</th>
                            <th class="text-center">Status Stok</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($permintaanPembelian->detail as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $d->bahanBaku->nama_bahan ?? '-' }}</td>
                            <td class="text-end">{{ number_format($d->jumlah, 2) }}</td>
                            <td>{{ $d->satuan->singkatan ?? '-' }}</td>
                            <td class="text-end {{ ($d->bahanBaku->stok_saat_ini ?? 0) <= ($d->bahanBaku->stok_minimum ?? 0) ? 'text-danger fw-bold' : 'text-success' }}">
                                {{ number_format($d->bahanBaku->stok_saat_ini ?? 0, 2) }}
                            </td>
                            <td class="text-end">{{ number_format($d->bahanBaku->stok_minimum ?? 0, 2) }}</td>
                            <td class="text-center">
                                @if(($d->bahanBaku->stok_saat_ini ?? 0) <= ($d->bahanBaku->stok_minimum ?? 0))
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di Bawah Minimum</span>
                                @else
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $d->catatan ?: '-' }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($permintaanPembelian->barangMasuk && $permintaanPembelian->barangMasuk->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-arrow-down-circle me-2 text-primary"></i>Barang Masuk Terkait
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permintaanPembelian->barangMasuk as $bm)
                        <tr>
                            <td><code>{{ $bm->kode_transaksi }}</code></td>
                            <td>{{ $bm->tanggal_masuk->format('d/m/Y') }}</td>
                            <td>{{ $bm->supplier->nama_supplier ?? '-' }}</td>
                            <td>{{ $bm->user->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


