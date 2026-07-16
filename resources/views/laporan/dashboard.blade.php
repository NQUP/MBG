@extends('layouts.app')
@section('title', 'Dashboard Laporan')
@section('page_title', 'Dashboard Laporan')

@section('content')
<div class="page-header"><h4><i class="bi bi-speedometer2 me-2"></i>Dashboard Laporan</h4></div>

<div class="card mb-3"><div class="card-body py-2">
<form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
        <label class="form-label">Periode</label>
        <select name="periode" class="form-select" onchange="this.form.submit()">
            <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="tahun" {{ $periode == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
        </select>
    </div>
    <div class="col-md-9">
        <p class="text-muted mb-0 mt-2">
            Periode: {{ Carbon\Carbon::parse($mulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
        </p>
    </div>
</form>
</div></div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
            <div><div class="stat-label">Permintaan Bahan</div><div class="stat-value">{{ number_format($total_permintaan_bahan) }}</div><small class="text-muted">siswa dilayani</small></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color:#d9822b;"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="color:#d9822b;"><i class="bi bi-cart"></i></div>
            <div><div class="stat-label">Permintaan Pembelian</div><div class="stat-value">{{ number_format($total_permintaan_pembelian) }}</div><small class="text-muted">permintaan</small></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color:#0f6cbf;"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="color:#0f6cbf;"><i class="bi bi-arrow-down-circle"></i></div>
            <div><div class="stat-label">Barang Masuk</div><div class="stat-value">{{ number_format($total_barang_masuk) }}</div><small class="text-muted">transaksi</small></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color:#8b5cf6;"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="color:#8b5cf6;"><i class="bi bi-gear"></i></div>
            <div><div class="stat-label">Penggunaan Bahan</div><div class="stat-value">{{ number_format($total_penggunaan_bahan, 2) }}</div><small class="text-muted">total unit</small></div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card"><div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Tren Permintaan Bahan & Pembelian (12 Bulan)</div>
        <div class="card-body"><canvas id="chartTrend" height="180"></canvas></div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-header"><i class="bi bi-pie-chart me-2 text-warning"></i>Distribusi Status Permintaan Pembelian</div>
        <div class="card-body"><canvas id="chartPrStatus" height="180"></canvas></div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card"><div class="card-header"><i class="bi bi-trophy me-2 text-success"></i>Bahan Baku Paling Sering Digunakan</div>
        <div class="card-body">
            @if($bahan_terpakai->count())
            <table class="table table-sm mb-0">
                <thead><tr><th>#</th><th>Bahan Baku</th><th>Total Pakai</th></tr></thead>
                <tbody>
                @foreach($bahan_terpakai as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $b->bahanBaku->nama_bahan ?? '-' }}</td>
                    <td>{{ number_format($b->total, 2) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted text-center py-3">Belum ada data.</p>
            @endif
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-header"><i class="bi bi-box-arrow-in-right me-2 text-info"></i>Akses Cepat Laporan</div>
        <div class="card-body">
            <div class="list-group">
                <a href="{{ route('laporan.stok') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Laporan Stok <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('laporan.permintaan-bahan') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Laporan Permintaan Bahan <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('laporan.permintaan-pembelian') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Laporan Permintaan Pembelian <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('laporan.barang-masuk') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Laporan Penerimaan Barang <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('laporan.penggunaan-bahan') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Laporan Penggunaan Bahan <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/laporan-dashboard.js"></script>
@endpush
