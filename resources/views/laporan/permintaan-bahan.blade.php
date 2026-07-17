@extends('layouts.app')
@section('title', 'Laporan Permintaan Bahan')
@section('page_title', 'Laporan Permintaan Bahan')
@section('content')
    <div class="page-header">
        <h4><i class="bi bi-graph-up me-2"></i>Laporan Permintaan Bahan Baku</h4>
    </div>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="dari" class="form-control" value="{{ $dari }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Menu</label>
                    <select name="menu" class="form-select">
                        <option value="">-- Semua Menu --</option>
                        @foreach ($menus as $m)
                            <option value="{{ $m->id }}" {{ request('menu') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_menu }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('laporan.permintaan-bahan') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
            <div class="row g-2 mt-2">
                <div class="col-md-12 d-flex gap-2">
                    <a href="{{ route('laporan.permintaan-bahan.pdf', request()->query()) }}" class="btn btn-danger"><i
                            class="bi bi-file-pdf"></i> Export PDF</a>
                    <a href="{{ route('laporan.permintaan-bahan.excel', request()->query()) }}" class="btn btn-success"><i
                            class="bi bi-file-excel"></i> Export Excel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-success"><strong>Total siswa:</strong> {{ number_format($totalSiswa) }} porsi</div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Makanan</th>
                        <th>Minuman</th>
                        <th>Buah</th>
                        <th>Siswa</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksis as $p)
                        <tr>
                            <td>{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                            <td>{{ $p->menuMakanan->nama_menu }}</td>
                            <td>{{ $p->menuMinuman->nama_bahan ?? '-' }}</td>
                            <td>{{ $p->menuBuah->nama_bahan ?? '-' }}</td>
                            <td>{{ number_format($p->jumlah_siswa) }}</td>
                            <td>{{ $p->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
