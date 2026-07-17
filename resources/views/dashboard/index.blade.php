@extends('layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div class="page-eyebrow">Ringkasan Operasional</div>
        <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
        <span class="text-muted"><i class="bi bi-person-circle me-1"></i>Selamat datang, {{ auth()->user()->name }}</span>
    </div>

    @if (auth()->user()->isAdmin())
        {{-- ========== ADMIN DASHBOARD ========== --}}
        <div class="row g-3 mb-4">
            {{-- Total Bahan Baku --}}
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Bahan Baku</div>
                            <div class="stat-value">{{ number_format($data['total_bahan']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Menu Aktif --}}
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#d9822b;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#d9822b;">
                            <i class="bi bi-journal-richtext"></i>
                        </div>
                        <div>
                            <div class="stat-label">Menu Aktif</div>
                            <div class="stat-value">{{ number_format($data['total_menu']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Total Siswa --}}
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#5fa876;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#5fa876;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Siswa Bulan Ini</div>
                            <div class="stat-value">{{ number_format($data['total_siswa']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Stok Minimum --}}
            <div class="col-md-3">
                <div class="card stat-card"
                    style="border-left-color:{{ $data['stok_minimum'] > 0 ? '#c0503c' : '#16382b' }};">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:{{ $data['stok_minimum'] > 0 ? '#c0503c' : '#16382b' }};">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Stok Minimum</div>
                            <div class="stat-value {{ $data['stok_minimum'] > 0 ? 'text-danger' : '' }}">
                                {{ number_format($data['stok_minimum']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-graph-up me-2 text-success"></i>Produksi Harian Bulan
                        {{ now()->isoFormat('MMMM Y') }}</div>
                    <div class="card-body">
                        @if ($data['produksi_bulanan']->isEmpty())
                            <p class="text-muted text-center py-4">Belum ada data produksi bulan ini.</p>
                        @else
                            <canvas id="chartProduksiAdmin" height="100"></canvas>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-cart-plus me-2 text-success"></i>Status Permintaan Pembelian
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartPrAdmin" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Stok Bahan Baku vs Minimum
                    </div>
                    <div class="card-body">
                        <canvas id="chartStokAdmin" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-clock-history me-2 text-warning"></i>Permintaan Pembelian
                        Terbaru</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table no-datatable table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Oleh</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['pr_recent'] as $pr)
                                        <tr>
                                            <td><code>{{ $pr->nomor_permintaan }}</code></td>
                                            <td>{{ $pr->user->name ?? '-' }}</td>
                                            <td>{!! \App\Models\PermintaanPembelian::statusBadge($pr->status) !!}</td>
                                            <td>{{ $pr->created_at->format('d/m H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->isPetugasGudang())
        {{-- ========== GUDANG DASHBOARD ========== --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                        <div>
                            <div class="stat-label">Total Bahan Baku</div>
                            <div class="stat-value">{{ $data['total_bahan'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card"
                    style="border-left-color:{{ $data['stok_minimum_count'] > 0 ? '#c0503c' : '#16382b' }};">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"
                            style="color:{{ $data['stok_minimum_count'] > 0 ? '#c0503c' : '#16382b' }};"><i
                                class="bi bi-exclamation-triangle"></i></div>
                        <div>
                            <div class="stat-label">Stok Minimum</div>
                            <div class="stat-value {{ $data['stok_minimum_count'] > 0 ? 'text-danger' : '' }}">
                                {{ $data['stok_minimum_count'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#d9822b;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#d9822b;"><i class="bi bi-arrow-down-circle"></i></div>
                        <div>
                            <div class="stat-label">Barang Masuk Hari Ini</div>
                            <div class="stat-value">{{ $data['barang_masuk_hari_ini'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#4a7a8c;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#4a7a8c;"><i class="bi bi-calendar-arrow-down"></i></div>
                        <div>
                            <div class="stat-label">Barang Masuk Bulan Ini</div>
                            <div class="stat-value">{{ $data['barang_masuk_bulan'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Stok Bahan Baku Saat Ini
                    </div>
                    <div class="card-body">
                        <canvas id="chartStokGudang" height="130"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-arrow-down-circle me-2 text-warning"></i>Barang Masuk 6 Bulan
                        Terakhir</div>
                    <div class="card-body">
                        @if ($data['barang_masuk_chart']->isEmpty())
                            <p class="text-muted text-center py-4">Belum ada data.</p>
                        @else
                            <canvas id="chartBmGudang" height="180"></canvas>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-cart-plus me-2 text-success"></i>Permintaan Menunggu Persetujuan</span>
                        @if ($data['pr_stats']['Diajukan'] > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $data['pr_stats']['Diajukan'] }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table no-datatable table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Oleh</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['pr_recent'] as $pr)
                                        <tr>
                                            <td><code>{{ $pr->nomor_permintaan }}</code></td>
                                            <td>{{ $pr->user->name ?? '-' }}</td>
                                            <td>{{ $pr->created_at->format('d/m H:i') }}</td>
                                            <td>
                                                <a href="{{ route('permintaan-pembelian.show', $pr) }}"
                                                    class="btn btn-sm btn-outline-primary btn-action"><i
                                                        class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada permintaan
                                                aktif.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-graph-up me-2 text-success"></i>Riwayat Stok 7 Hari</div>
                    <div class="card-body">
                        <canvas id="chartRiwayatStok" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @if ($data['stok_minimum']->isNotEmpty())
            <div class="card mb-3 card-alert">
                <div class="card-header card-header-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Bahan Baku
                    Stok Minimum</div>
                <div class="table-responsive">
                    <table class="table no-datatable table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Bahan</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['stok_minimum'] as $bahan)
                                <tr>
                                    <td><code>{{ $bahan->kode_bahan }}</code></td>
                                    <td class="fw-semibold">{{ $bahan->nama_bahan }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($bahan->stok_saat_ini, 2) }}</td>
                                    <td>{{ number_format($bahan->stok_minimum, 2) }}</td>
                                    <td>{{ $bahan->satuan->singkatan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="d-flex gap-2">
            <a href="{{ route('gudang.barang-masuk.create') }}" class="btn btn-success"><i
                    class="bi bi-plus-circle me-1"></i>Input Barang Masuk</a>
            <a href="{{ route('gudang.stock-opname.create') }}" class="btn btn-outline-primary"><i
                    class="bi bi-clipboard-check me-1"></i>Buat Stock Opname</a>
        </div>
    @elseif($user->isPetugasDapur())
        {{-- ========== DAPUR DASHBOARD ========== --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-fire"></i></div>
                        <div>
                            <div class="stat-label">Sesi Produksi Hari Ini</div>
                            <div class="stat-value">{{ $data['produksi_hari_ini']->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#d9822b;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#d9822b;"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="stat-label">Siswa Minggu Ini</div>
                            <div class="stat-value">{{ number_format($data['produksi_minggu']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#4a7a8c;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#4a7a8c;"><i class="bi bi-calendar-check"></i></div>
                        <div>
                            <div class="stat-label">Siswa Bulan Ini</div>
                            <div class="stat-value">{{ number_format($data['total_siswa_bulan']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color:#5fa876;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="color:#5fa876;"><i class="bi bi-journal-check"></i></div>
                        <div>
                            <div class="stat-label">Sesi Bulan Ini</div>
                            <div class="stat-value">{{ $data['total_sesi_bulan'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-graph-up me-2 text-success"></i>Produksi 7 Hari Terakhir
                    </div>
                    <div class="card-body">
                        <canvas id="chartProduksiDapur" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-pie-chart me-2 text-warning"></i>Distribusi Menu Bulan Ini
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartMenuDapur" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-fire me-2 text-danger"></i>Produksi Hari Ini</span>
                        <a href="{{ route('dapur.produksi.create') }}" class="btn btn-sm btn-success"><i
                                class="bi bi-plus-circle me-1"></i>Input Produksi</a>
                    </div>
                    @if ($data['produksi_hari_ini']->isEmpty())
                        <div class="card-body text-center text-muted py-5">
                            <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>Belum ada produksi hari ini.
                            <div class="mt-3"><a href="{{ route('dapur.produksi.create') }}"
                                    class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Input Produksi
                                    Sekarang</a></div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table no-datatable table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Makanan</th>
                                        <th>Minuman</th>
                                        <th>Buah</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['produksi_hari_ini'] as $p)
                                        <tr>
                                            <td>{{ $p->menuMakanan->nama_menu }}</td>
                                            <td>{{ $p->menuMinuman->nama_bahan ?? '-' }}</td>
                                            <td>{{ $p->menuBuah->nama_bahan ?? '-' }}</td>
                                            <td><strong>{{ number_format($p->jumlah_siswa) }} siswa</strong></td>
                                            <td>
                                                @if ($p->status === 'dikonfirmasi')
                                                <span class="badge bg-success">Dikonfirmasi</span>@else<span
                                                        class="badge bg-warning text-dark">Draft</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-clock-history me-2 text-warning"></i>Riwayat Produksi Terbaru
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table no-datatable table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Makanan</th>
                                        <th>Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['produksi_recent'] as $p)
                                        <tr>
                                            <td>{{ $p->tanggal_produksi->format('d/m') }}</td>
                                            <td>{{ $p->menuMakanan->nama_menu }}</td>
                                            <td><strong>{{ number_format($p->jumlah_siswa) }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Pengumuman -->
        {{-- <div class="modal fade" id="modalPengumuman" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-megaphone-fill text-warning me-2"></i>
                            Pengumuman
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <img src="{{ asset('images/bahlil.jfif') }}" class="img-fluid rounded shadow-sm"
                            alt="Pengumuman">

                        <div class="mt-3">
                            <h5>Selamat Datang</h5>
                            <p class="text-muted mb-0">
                                Selamat My Little Bolu Ketan.
                            </p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" data-bs-dismiss="modal">
                            <i class="bi bi-check-circle me-1"></i>
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif

@endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="/css/dashboard.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== ADMIN CHARTS =====
            @if (auth()->user()->isAdmin())
                // Produksi Bulanan
                if (document.getElementById('chartProduksiAdmin')) {
                    createBarChart('chartProduksiAdmin',
                        {!! $data['produksi_bulanan']->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))->toJson() !!},
                        [{
                            label: 'Jumlah Siswa',
                            data: {!! $data['produksi_bulanan']->pluck('total')->toJson() !!},
                            backgroundColor: DASHBOARD_COLORS.leafLight,
                            borderColor: DASHBOARD_COLORS.leaf
                        }], {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    );
                }

                // PR Status Doughnut
                if (document.getElementById('chartPrAdmin')) {
                    createDoughnutChart('chartPrAdmin',
                        ['Draft', 'Diajukan', 'Disetujui', 'Selesai', 'Ditolak'],
                        [{!! json_encode(array_values($data['pr_stats'])) !!}],
                        ['#8a9480', DASHBOARD_COLORS.yellow, DASHBOARD_COLORS.leaf, DASHBOARD_COLORS.blue,
                            DASHBOARD_COLORS.red
                        ]
                    );
                }

                // Stok vs Minimum
                if (document.getElementById('chartStokAdmin')) {
                    createBarChart('chartStokAdmin',
                        {!! $data['stok_chart']->pluck('nama_bahan')->toJson() !!},
                        [{
                                label: 'Stok Saat Ini',
                                data: {!! $data['stok_chart']->pluck('stok_saat_ini')->toJson() !!},
                                backgroundColor: DASHBOARD_COLORS.leafLight,
                                borderColor: DASHBOARD_COLORS.leaf
                            },
                            {
                                label: 'Stok Minimum',
                                data: {!! $data['stok_chart']->pluck('stok_minimum')->toJson() !!},
                                backgroundColor: 'rgba(192,80,60,0.14)',
                                borderColor: DASHBOARD_COLORS.red
                            }
                        ], {
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    );
                }
            @endif

            // ===== GUDANG CHARTS =====
            @if ($user->isPetugasGudang())
                // Stok Bahan Baku
                if (document.getElementById('chartStokGudang')) {
                    var stokLabels = {!! $data['stok_chart']->pluck('nama_bahan')->toJson() !!};
                    var stokData = {!! $data['stok_chart']->pluck('stok_saat_ini')->toJson() !!};
                    var stokMinData = {!! $data['stok_chart']->pluck('stok_minimum')->toJson() !!};
                    var barColors = stokData.map((v, i) => v <= stokMinData[i] ? DASHBOARD_COLORS.red :
                        DASHBOARD_COLORS.leaf);
                    new Chart(document.getElementById('chartStokGudang'), {
                        type: 'bar',
                        data: {
                            labels: stokLabels,
                            datasets: [{
                                    label: 'Stok Saat Ini',
                                    data: stokData,
                                    backgroundColor: barColors.map(c => c === DASHBOARD_COLORS.red ?
                                        'rgba(192,80,60,0.22)' : DASHBOARD_COLORS.leafLight),
                                    borderColor: barColors,
                                    borderWidth: 1,
                                    borderRadius: 4
                                },
                                {
                                    label: 'Batas Minimum',
                                    data: stokMinData,
                                    backgroundColor: 'rgba(226,168,63,0.22)',
                                    borderColor: DASHBOARD_COLORS.yellow,
                                    borderWidth: 1,
                                    borderRadius: 4
                                }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Barang Masuk Bulanan
                if (document.getElementById('chartBmGudang')) {
                    createBarChart('chartBmGudang',
                        {!! $data['barang_masuk_chart']->pluck('bulan')->toJson() !!},
                        [{
                            label: 'Jumlah Transaksi',
                            data: {!! $data['barang_masuk_chart']->pluck('jumlah')->toJson() !!},
                            backgroundColor: 'rgba(217,130,43,0.18)',
                            borderColor: DASHBOARD_COLORS.orange
                        }], {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    );
                }

                if (document.getElementById('chartRiwayatStok')) {
                    createLineChart('chartRiwayatStok',
                        {!! json_encode($data['riwayat_labels']) !!},
                        [{
                                label: 'Masuk',
                                data: {!! json_encode($data['riwayat_masuk']) !!},
                                borderColor: DASHBOARD_COLORS.leaf,
                                backgroundColor: DASHBOARD_COLORS.leafLight,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Keluar',
                                data: {!! json_encode($data['riwayat_keluar']) !!},
                                borderColor: DASHBOARD_COLORS.red,
                                backgroundColor: 'rgba(192,80,60,0.1)',
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    );
                }
            @endif

            // ===== DAPUR CHARTS =====
            @if ($user->isPetugasDapur())
                if (document.getElementById('chartProduksiDapur')) {
                    createBarChart('chartProduksiDapur',
                        {!! $data['produksi_mingguan']->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->locale('id')->isoFormat('dddd, D MMM'))->toJson() !!},
                        [{
                            label: 'Jumlah Siswa',
                            data: {!! $data['produksi_mingguan']->pluck('total')->toJson() !!},
                            backgroundColor: DASHBOARD_COLORS.leafLight,
                            borderColor: DASHBOARD_COLORS.leaf
                        }], {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    );
                }

                if (document.getElementById('chartMenuDapur')) {
                    var menuLabels = {!! $data['menu_stats']->pluck('menuMakanan.nama_menu')->map(fn($n) => $n ?? 'N/A')->toJson() !!};
                    var menuData = {!! $data['menu_stats']->pluck('total_siswa')->toJson() !!};
                    var menuColors = ['#16382b', '#d9822b', '#4a7a8c', '#c0503c', '#e2a83f', '#5fa876', '#7c6a9c',
                        '#86c99a'
                    ];
                    createDoughnutChart('chartMenuDapur', menuLabels, menuData, menuColors.slice(0, menuLabels
                        .length));
                }
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalPengumuman'));
            modal.show();
        });
    </script>
@endpush
