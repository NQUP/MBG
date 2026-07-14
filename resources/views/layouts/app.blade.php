<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Inventory MBG') - SPPG Sumber Jaya 03</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/vendor/sweetalert2/theme-bootstrap-4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #1a7f5a;
            --primary-dark: #145e43;
            --primary-light: #e8f5f0;
        }

        body {
            background: #f4f7f6;
            font-family: 'Segoe UI', sans-serif;
        }

        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #1a7f5a 0%, #145e43 100%);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        #sidebar .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        #sidebar .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        #sidebar .sidebar-brand small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 10px 20px;
            border-radius: 0;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            padding-left: 25px;
        }

        #sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }

        #sidebar .sidebar-section {
            padding: 10px 20px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
        }

        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .navbar-top {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 24px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h4 {
            font-weight: 700;
            color: #1a7f5a;
            margin: 0;
        }

        .card {
            border: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .stat-card {
            border-left: 4px solid var(--primary);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            color: var(--primary);
            opacity: 0.8;
        }

        .table th {
            font-weight: 600;
            font-size: 0.85rem;
            background: #f8f9fa;
        }

        .btn-action {
            padding: 4px 10px;
            font-size: 0.8rem;
        }

        .stok-warning {
            color: #dc3545;
            font-weight: 600;
        }

        .stok-ok {
            color: #1a7f5a;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #content {
                margin-left: 0;
            }
        }

        .swal2-popup {
            font-size: 14px;
            border-radius: 12px;
        }

        .swal2-title {
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="font-size:1.8rem;">&#x1F957;</span>
                <div>
                    <h5 class="mb-0">Inventory MBG</h5>
                    <small>SPPG Sumber Jaya 03</small>
                </div>
            </div>
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            @if (auth()->user()->isAdmin())
                <li>
                    <div class="sidebar-section">Master Data</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}"
                        class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> Supplier
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kategori.index') }}"
                        class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Kategori Bahan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.satuan.index') }}"
                        class="nav-link {{ request()->routeIs('admin.satuan.*') ? 'active' : '' }}">
                        <i class="bi bi-rulers"></i> Satuan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.bahan-baku.index') }}"
                        class="nav-link {{ request()->routeIs('admin.bahan-baku.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Bahan Baku
                    </a>
                </li>
                <li>
                    <div class="sidebar-section">Menu &amp; Resep</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.menu.index') }}"
                        class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-richtext"></i> Kelola Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.resep.index') }}"
                        class="nav-link {{ request()->routeIs('admin.resep.*') ? 'active' : '' }}">
                        <i class="bi bi-card-list"></i> Resep (BOM)
                    </a>
                </li>
                <li>
                    <div class="sidebar-section">Laporan</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.stok') }}"
                        class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> Laporan Stok
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.produksi') }}"
                        class="nav-link {{ request()->routeIs('laporan.produksi') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Laporan Produksi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.barang-masuk') }}"
                        class="nav-link {{ request()->routeIs('laporan.barang-masuk') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle"></i> Laporan Barang Masuk
                    </a>
                </li>
            @endif
            @if (auth()->user()->isPetugasGudang())
                <li>
                    <div class="sidebar-section">Gudang</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.stok.index') }}"
                        class="nav-link {{ request()->routeIs('gudang.stok.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Stok Bahan Baku
                        @php $ac = \App\Models\BahanBaku::where('is_active', true)->whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count(); @endphp
                        @if ($ac > 0)
                            <span class="badge bg-danger ms-1">{{ $ac }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.barang-masuk.index') }}"
                        class="nav-link {{ request()->routeIs('gudang.barang-masuk.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle"></i> Barang Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.stock-opname.index') }}"
                        class="nav-link {{ request()->routeIs('gudang.stock-opname.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i> Stock Opname
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAdmin() || auth()->user()->isPetugasGudang())
                <li class="nav-item">
                    <a href="{{ route('permintaan-pembelian.index') }}"
                        class="nav-link {{ request()->routeIs('permintaan-pembelian.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i> Permintaan Pembelian
                    </a>
                </li>
            @endif
            @if (auth()->user()->isPetugasDapur())
                <li>
                    <div class="sidebar-section">Dapur</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dapur.produksi.index') }}"
                        class="nav-link {{ request()->routeIs('dapur.produksi.*') ? 'active' : '' }}">
                        <i class="bi bi-fire"></i> Produksi Harian
                    </a>
                </li>
            @endif
        </ul>
        <div class="mt-auto p-3" style="position:absolute;bottom:0;width:100%;">
            <div class="d-flex align-items-center gap-2 p-2"
                style="background:rgba(255,255,255,0.1);border-radius:8px;">
                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                    style="width:36px;height:36px;min-width:36px;">
                    <i class="bi bi-person-fill text-success"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-semibold text-truncate" style="font-size:0.8rem;">
                        {{ auth()->user()->name }}</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.7);">
                        {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</div>
                </div>
            </div>
        </div>
    </nav>
    <div id="content">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold text-muted">@yield('page_title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:0.85rem;"><i class="bi bi-calendar3"></i>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                            class="bi bi-box-arrow-right"></i> Keluar</button>
                </form>
            </div>
        </nav>
        <div class="p-4">
            @if (session('success'))
                <div id="flash-data" data-type="success" data-message="{{ session('success') }}"></div>
            @endif
            @if (session('error'))
                <div id="flash-data" data-type="error" data-message="{{ session('error') }}"></div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.min.js"></script>
    <script src="/vendor/sweetalert2/confetti.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (t) => {
                    t.onmouseenter = Swal.stopTimer;
                    t.onmouseleave = Swal.resumeTimer;
                }
            });
            var flash = document.getElementById('flash-data');
            if (flash) {
                var type = flash.dataset.type || 'success';
                var msg = flash.dataset.message;
                if (msg) {
                    Toast.fire({
                        icon: type,
                        title: msg
                    });
                    if (type === 'success') {
                        setTimeout(function() {
                            confetti({
                                particleCount: 80,
                                spread: 70,
                                origin: {
                                    y: 0.6
                                },
                                colors: ['#1a7f5a', '#145e43', '#f59e0b']
                            });
                        }, 300);
                    }
                }
            }
        });
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-hapus');
            if (!btn) return;
            e.preventDefault();
            var form = btn.closest('form');
            if (!form) return;
            Swal.fire({
                title: 'Hapus ' + (btn.dataset.label || 'item ini') + '?',
                html: 'Data yang dihapus <b>tidak bisa</b> dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) form.submit();
            });
        });
        document.addEventListener('submit', function(e) {
            var form = e.target.closest('form[data-confirm]');
            if (!form) return;
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                html: form.dataset.confirm || 'Yakin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a7f5a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) form.submit();
            });
        });
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-confirm]');
            if (!btn) return;
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                html: btn.dataset.confirm || 'Yakin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a7f5a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) {
                    var f = btn.closest('form');
                    if (f) f.submit();
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
