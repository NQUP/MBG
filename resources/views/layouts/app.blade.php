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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --forest: #16382b;
            --forest-deep: #0d271e;
            --leaf: #5fa876;
            --leaf-bright: #86c99a;
            --leaf-tint: rgba(95, 168, 118, 0.14);
            --turmeric: #d9822b;
            --turmeric-tint: rgba(217, 130, 43, 0.14);
            --sky: #4a7a8c;
            --danger: #c0503c;
            --danger-tint: rgba(192, 80, 60, 0.12);
            --rice: #faf6ec;
            --rice-dim: #f0ead8;
            --ink: #22291f;
            --muted: #6b7563;
            --sage-line: #dfe3d2;
            --primary: var(--forest);
            --primary-dark: var(--forest-deep);
            --primary-light: var(--leaf-tint);
        }

        body {
            background: var(--rice);
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            font-size: 1rem;
            line-height: 1.6;
        }

        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 0%, rgba(134, 201, 154, 0.14), transparent 45%),
                linear-gradient(180deg, var(--forest) 0%, var(--forest-deep) 100%);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        #sidebar .nav-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar .nav-scroll::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar .nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(250, 246, 236, 0.2);
            border-radius: 4px;
        }

        #sidebar .sidebar-brand {
            padding: 22px 18px 18px;
            border-bottom: 1px solid rgba(250, 246, 236, 0.15);
        }

        #sidebar .sidebar-brand .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(250, 246, 236, 0.08);
            border: 1px solid rgba(250, 246, 236, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex: none;
        }

        #sidebar .sidebar-brand h5 {
            font-family: 'Fraunces', serif;
            color: #fff;
            font-weight: 600;
            font-size: 1.15rem;
            margin-bottom: 2px;
        }

        #sidebar .sidebar-brand small {
            font-family: 'JetBrains Mono', monospace;
            color: var(--leaf-bright);
            font-size: 0.78rem;
            letter-spacing: 0.03em;
        }

        #sidebar .nav-link {
            position: relative;
            color: rgba(250, 246, 236, 0.78);
            padding: 12px 20px 12px 24px;
            border-radius: 0;
            font-size: 0.95rem;
            transition: all 0.15s;
        }

        #sidebar .nav-link i {
            width: 22px;
            margin-right: 10px;
            font-size: 1.05rem;
            color: rgba(250, 246, 236, 0.55);
        }

        #sidebar .nav-link:hover {
            background: rgba(250, 246, 236, 0.08);
            color: #fff;
        }

        #sidebar .nav-link.active {
            background: rgba(250, 246, 236, 0.1);
            color: #fff;
            font-weight: 600;
        }

        #sidebar .nav-link.active::before,
        #sidebar .nav-link:hover::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--leaf-bright);
        }

        #sidebar .nav-link.active i {
            color: var(--leaf-bright);
        }

        #sidebar .sidebar-section {
            padding: 18px 20px 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(250, 246, 236, 0.4);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #sidebar .sidebar-section::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(250, 246, 236, 0.12);
        }

        #sidebar .sidebar-user {
            background: rgba(250, 246, 236, 0.08);
            border: 1px solid rgba(250, 246, 236, 0.12);
            border-radius: 10px;
        }

        #sidebar .sidebar-user .avatar {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            background: var(--rice);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #sidebar .sidebar-user .avatar i {
            color: var(--forest);
        }

        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .navbar-top {
            background: #fff;
            border-bottom: 1px solid var(--sage-line);
            padding: 14px 28px;
        }

        .navbar-top h6 {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-size: 0.8rem !important;
            color: var(--muted) !important;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header .text-muted {
            font-size: 0.9rem;
            display: block;
            margin-top: 4px;
        }

        .page-header h4 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            color: var(--forest);
            margin: 0;
            font-size: 1.5rem;
        }

        .page-eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--leaf);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-eyebrow::before {
            content: '';
            width: 20px;
            height: 1px;
            background: var(--leaf);
        }

        .card {
            border: 1px solid var(--sage-line);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-radius: 12px;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        }

        .card-header {
            background: var(--rice-dim);
            border-bottom: 1px solid var(--sage-line);
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            color: var(--forest);
            font-size: 0.95rem;
            padding: 14px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background: var(--rice-dim);
            border-top: 1px solid var(--sage-line);
            border-radius: 0 0 12px 12px !important;
            padding: 12px 20px;
        }

        .stat-card {
            border-left: 4px solid var(--forest);
        }

        .stat-card .stat-icon {
            font-size: 2.2rem;
            color: var(--forest);
            opacity: 0.85;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--ink);
            font-family: 'Fraunces', serif;
        }

        .table {
            font-size: 0.9rem;
        }

        .table th {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted);
            background: var(--rice-dim);
            border-color: var(--sage-line);
            padding: 12px 14px;
        }

        .table td {
            border-color: var(--sage-line);
            padding: 12px 14px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(95, 168, 118, 0.04);
        }

        code {
            font-family: 'JetBrains Mono', monospace;
            background: var(--rice-dim);
            color: var(--forest);
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 0.88rem;
        }

        .btn-action {
            padding: 6px 14px;
            font-size: 0.88rem;
            border-radius: 8px;
        }

        .stok-warning {
            color: var(--danger);
            font-weight: 600;
        }

        .stok-ok {
            color: var(--forest);
        }

        .btn {
            font-size: 0.9rem;
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .btn-sm {
            font-size: 0.82rem;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .btn-lg {
            font-size: 1rem;
            padding: 10px 24px;
            border-radius: 10px;
        }

        .btn-success {
            background: var(--forest);
            border-color: var(--forest);
        }

        .btn-success:hover {
            background: #1e4634;
            border-color: #1e4634;
        }

        .btn-outline-primary {
            color: var(--forest);
            border-color: var(--forest);
        }

        .btn-outline-primary:hover {
            background: var(--forest);
            border-color: var(--forest);
        }

        .btn-outline-danger {
            color: var(--danger);
            border-color: var(--danger);
        }

        .btn-outline-danger:hover {
            background: var(--danger);
            border-color: var(--danger);
        }

        .btn-outline-secondary {
            color: var(--muted);
            border-color: var(--sage-line);
        }

        .btn-outline-secondary:hover {
            background: var(--rice-dim);
            border-color: var(--muted);
            color: var(--ink);
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            font-size: 0.92rem;
            padding: 8px 14px;
            border-radius: 8px;
            border-color: var(--sage-line);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--leaf);
            box-shadow: 0 0 0 3px var(--leaf-tint);
        }

        .form-control-sm, .form-select-sm {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .badge.bg-success {
            background: var(--leaf) !important;
        }

        .badge.bg-warning {
            background: var(--turmeric) !important;
            color: #fff !important;
        }

        .badge.bg-danger {
            background: var(--danger) !important;
        }

        .badge.bg-primary {
            background: var(--sky) !important;
        }

        .badge.bg-secondary {
            background: #9ba893 !important;
        }

        .text-success {
            color: var(--leaf) !important;
        }

        .text-primary {
            color: var(--sky) !important;
        }

        .text-warning {
            color: var(--turmeric) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-muted {
            color: var(--muted) !important;
        }

        .small, small {
            font-size: 0.82rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #content {
                margin-left: 0;
            }
        }

        .pagination {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .page-link {
            color: var(--forest);
            border-color: var(--sage-line);
            padding: 8px 16px;
        }

        .page-link:hover {
            background: var(--leaf-tint);
            border-color: var(--leaf);
            color: var(--forest);
        }

        .page-item.active .page-link {
            background: var(--forest);
            border-color: var(--forest);
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
        }

        .btn-danger:hover {
            background: #a8422e;
            border-color: #a8422e;
        }

        .swal2-popup {
            font-size: 15px;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
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
                <div class="brand-icon">&#x1F957;</div>
                <div>
                    <h5 class="mb-0">Inventory MBG</h5>
                    <small>SPPG Sumber Jaya 03</small>
                </div>
            </div>
        </div>
        <div class="nav-scroll">
        <ul class="nav flex-column">
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
                    <di                <li>
                    <div class="sidebar-section">Laporan</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.dashboard') }}"
                        class="nav-link {{ request()->routeIs('laporan.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.stok') }}"
                        class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> Laporan Stok
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.permintaan-bahan') }}"
                        class="nav-link {{ request()->routeIs('laporan.permintaan-bahan') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Laporan Permintaan Bahan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.permintaan-pembelian') }}"
                        class="nav-link {{ request()->routeIs('laporan.permintaan-pembelian') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i> Laporan Permintaan Pembelian
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.barang-masuk') }}"
                        class="nav-link {{ request()->routeIs('laporan.barang-masuk') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle"></i> Laporan Barang Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.penggunaan-bahan') }}"
                        class="nav-link {{ request()->routeIs('laporan.penggunaan-bahan') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Laporan Penggunaan Bahan
                    </a>
                </li>@endif
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
        </div>
        <div class="p-3" style="border-top:1px solid rgba(250,246,236,0.12);">
            <div class="d-flex align-items-center gap-2 p-2 sidebar-user">
                <div class="avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-semibold text-truncate" >
                        {{ auth()->user()->name }}</div>
                    <div style="font-size:0.78rem;color:rgba(250,246,236,0.6);">
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
                                colors: ['#16382b', '#5fa876', '#d9822b']
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
                confirmButtonColor: '#c0503c',
                cancelButtonColor: '#8a9480',
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
                confirmButtonColor: '#16382b',
                cancelButtonColor: '#8a9480',
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
                confirmButtonColor: '#16382b',
                cancelButtonColor: '#8a9480',
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



