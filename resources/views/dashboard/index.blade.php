@extends('layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
    <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
</div>

@if(auth()->user()->isAdmin())
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;">Total Bahan Baku</div>
                    <div class="fw-bold fs-4">{{ $data['total_bahan'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #fd7e14;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2rem;color:#fd7e14;"><i class="bi bi-journal-richtext"></i></div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;">Total Menu Aktif</div>
                    <div class="fw-bold fs-4">{{ $data['total_menu'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #0d6efd;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2rem;color:#0d6efd;"><i class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;">Pengguna Aktif</div>
                    <div class="fw-bold fs-4">{{ $data['total_user'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid {{ $data['stok_minimum'] > 0 ? '#dc3545' : '#1a7f5a' }};">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2rem;color:{{ $data['stok_minimum'] > 0 ? '#dc3545' : '#1a7f5a' }};"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;">Stok di Bawah Minimum</div>
                    <div class="fw-bold fs-4 {{ $data['stok_minimum'] > 0 ? 'text-danger' : '' }}">{{ $data['stok_minimum'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-cart-plus me-2 text-success"></i>Permintaan Pembelian
            </div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-2 fw-bold text-secondary">{{ $data['pr_draft'] }}</div>
                            <div class="text-muted small">Draft</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fs-2 fw-bold text-warning">{{ $data['pr_diajukan'] }}</div>
                            <div class="text-muted small">Menunggu</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="fs-2 fw-bold text-success">{{ $data['pr_selesai'] }}</div>
                            <div class="text-muted small">Selesai/Bln</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('permintaan-pembelian.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Semua PR
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-graph-up me-2 text-success"></i>Produksi Siswa Bulan {{ now()->isoFormat('MMMM Y') }}
            </div>
            <div class="card-body">
                @if($data['produksi_bulanan']->isEmpty())
                    <p class="text-muted text-center py-4">Belum ada data produksi bulan ini.</p>
                @else
                    <canvas id="chartProduksi" height="80"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Ringkasan Bulan Ini</div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Total Produksi</span><strong>{{ $data['total_produksi'] }} sesi</strong></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Total Siswa</span><strong>{{ $data['produksi_bulanan']->sum('total') }} porsi</strong></div>
                <div class="mt-3">
                    <a href="{{ route('admin.bahan-baku.index') }}" class="btn btn-sm btn-outline-success w-100 mb-2"><i class="bi bi-box-seam me-1"></i>Lihat Bahan Baku</a>
                    <a href="{{ route('laporan.produksi') }}" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-graph-up me-1"></i>Laporan Produksi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(auth()->user()->isPetugasGudang())
@if($data['stok_minimum']->isNotEmpty())
<div class="alert alert-danger d-flex align-items-center mb-4">
    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
    <div><strong>Peringatan Stok Minimum!</strong> Terdapat {{ $data['stok_minimum']->count() }} bahan baku yang stoknya di bawah batas minimum.<a href="{{ route('gudang.stok.index') }}" class="alert-link ms-2">Lihat detail &rarr;</a></div>
</div>
@endif
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-box-seam"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Total Jenis Bahan</div><div class="fw-bold fs-4">{{ $data['stok_bahan']->count() }}</div></div></div></div></div>
    <div class="col-md-4"><div class="card" style="border-left:4px solid #dc3545;"><div class="card-body d-flex align-items-center gap-3"><div style="font-size:2rem;color:#dc3545;"><i class="bi bi-exclamation-triangle"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Stok Minimum</div><div class="fw-bold fs-4 text-danger">{{ $data['stok_minimum']->count() }}</div></div></div></div></div>
    <div class="col-md-4"><div class="card" style="border-left:4px solid #fd7e14;"><div class="card-body d-flex align-items-center gap-3"><div style="font-size:2rem;color:#fd7e14;"><i class="bi bi-arrow-down-circle"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Barang Masuk Bulan Ini</div><div class="fw-bold fs-4">{{ $data['barang_masuk_bulan'] }}</div></div></div></div></div>
</div>
@if($data['stok_minimum']->isNotEmpty())
<div class="card mb-4"><div class="card-header bg-danger text-white fw-semibold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Bahan Baku Stok Minimum</div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Kode</th><th>Nama Bahan</th><th>Kategori</th><th>Stok Saat Ini</th><th>Stok Minimum</th><th>Satuan</th></tr></thead><tbody>@foreach($data['stok_minimum'] as $bahan)<tr><td><code>{{ $bahan->kode_bahan }}</code></td><td class="fw-semibold">{{ $bahan->nama_bahan }}</td><td>{{ $bahan->kategori->nama_kategori }}</td><td class="stok-warning">{{ number_format($bahan->stok_saat_ini,2) }}</td><td>{{ number_format($bahan->stok_minimum,2) }}</td><td>{{ $bahan->satuan->singkatan }}</td></tr>@endforeach</tbody></table></div></div>
@endif
<div class="d-flex gap-2"><a href="{{ route('gudang.barang-masuk.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Input Barang Masuk</a><a href="{{ route('gudang.stock-opname.create') }}" class="btn btn-outline-primary"><i class="bi bi-clipboard-check me-1"></i>Buat Stock Opname</a></div>
@endif

@if(auth()->user()->isPetugasDapur())
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-fire"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Produksi Hari Ini</div><div class="fw-bold fs-4">{{ $data['produksi_hari_ini']->count() }} sesi</div></div></div></div></div>
    <div class="col-md-4"><div class="card" style="border-left:4px solid #fd7e14;"><div class="card-body d-flex align-items-center gap-3"><div style="font-size:2rem;color:#fd7e14;"><i class="bi bi-people"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Siswa Minggu Ini</div><div class="fw-bold fs-4">{{ number_format($data['produksi_minggu']) }}</div></div></div></div></div>
    <div class="col-md-4"><div class="card" style="border-left:4px solid #0d6efd;"><div class="card-body d-flex align-items-center gap-3"><div style="font-size:2rem;color:#0d6efd;"><i class="bi bi-calendar3"></i></div><div><div class="text-muted" style="font-size:0.8rem;">Total Siswa Bulan Ini</div><div class="fw-bold fs-4">{{ number_format($data['total_siswa_bulan']) }}</div></div></div></div></div>
</div>
<div class="card mb-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-fire me-2 text-danger"></i>Produksi Hari Ini &mdash; {{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        <a href="{{ route('dapur.produksi.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-circle me-1"></i>Input Produksi</a>
    </div>
    @if($data['produksi_hari_ini']->isEmpty())
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>Belum ada produksi hari ini.
        <div class="mt-3"><a href="{{ route('dapur.produksi.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Input Produksi Sekarang</a></div>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Makanan</th><th>Minuman</th><th>Buah</th><th>Jumlah Siswa</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($data['produksi_hari_ini'] as $p)
                <tr><td>{{ $p->menuMakanan->nama_menu }}</td><td>{{ $p->menuMinuman->nama_menu }}</td><td>{{ $p->menuBuah->nama_menu }}</td><td><strong>{{ number_format($p->jumlah_siswa) }} siswa</strong></td><td>@if($p->status === 'dikonfirmasi')<span class="badge bg-success">Dikonfirmasi</span>@else<span class="badge bg-warning text-dark">Draft</span>@endif</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif
@endsection

@push('scripts')
@if(auth()->user()->isAdmin() && !empty($data['produksi_bulanan']) && $data['produksi_bulanan']->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartProduksi').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! $data['produksi_bulanan']->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))->toJson() !!},
        datasets: [{
            label: 'Jumlah Siswa',
            data: {!! $data['produksi_bulanan']->pluck('total')->toJson() !!},
            backgroundColor: 'rgba(26, 127, 90, 0.7)',
            borderColor: '#1a7f5a',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endif
@endpush