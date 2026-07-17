<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Inventory MBG') - SPPG Sumber Jaya 03</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logord.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/vendor/sweetalert2/theme-bootstrap-4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Beri jarak untuk kontrol DataTables (search, length menu, info, pagination) di dalam card */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 1rem 1rem 0.5rem;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 0.75rem 1rem 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .pagination {
            margin-bottom: 0;
        }

        /* Tom Select: pastikan dropdown tampil di atas elemen lain & rapi di dalam tabel */
        .ts-dropdown {
            z-index: 1060;
        }

        td .ts-wrapper {
            min-width: 70px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="brand-icon">
                    <img src="{{ asset('images/logord.png') }}" alt="Logo" class="logo-sidebar">
                </div>
                <div>
                    <h5 class="mb-0">Inventory MBG</h5>
                    <small>SPPG Sumber Jaya 03</small>
                </div>
            </div>
        </div>

        <div class="nav-scroll">
            <ul class="nav flex-column" id="sidebarAccordion">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                @if (auth()->user()->isAdmin())
                    {{-- ===================== MASTER DATA ===================== --}}
                    @php
                        $masterDataActive = request()->routeIs([
                            'admin.users.*',
                            'admin.suppliers.*',
                            'admin.kategori.*',
                            'admin.satuan.*',
                            'admin.bahan-baku.*',
                        ]);
                    @endphp
                    <li>
                        <a class="sidebar-section" data-bs-toggle="collapse" href="#groupMasterData" role="button"
                            aria-expanded="{{ $masterDataActive ? 'true' : 'false' }}" aria-controls="groupMasterData">
                            Master Data
                            <i class="bi bi-chevron-down ms-auto collapse-caret"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse {{ $masterDataActive ? 'show' : '' }}" id="groupMasterData"
                            data-bs-parent="#sidebarAccordion">
                            <ul class="nav flex-column">
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
                            </ul>
                        </div>
                    </li>

                    {{-- ===================== MENU & RESEP ===================== --}}
                    @php
                        $menuResepActive = request()->routeIs(['admin.menu.*', 'admin.resep.*']);
                    @endphp
                    <li>
                        <a class="sidebar-section" data-bs-toggle="collapse" href="#groupMenuResep" role="button"
                            aria-expanded="{{ $menuResepActive ? 'true' : 'false' }}" aria-controls="groupMenuResep">
                            Menu &amp; Resep
                            <i class="bi bi-chevron-down ms-auto collapse-caret"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse {{ $menuResepActive ? 'show' : '' }}" id="groupMenuResep"
                            data-bs-parent="#sidebarAccordion">
                            <ul class="nav flex-column">
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
                            </ul>
                        </div>
                    </li>

                    {{-- ===================== LAPORAN ===================== --}}
                    @php
                        $laporanActive = request()->routeIs([
                            'laporan.dashboard',
                            'laporan.stok',
                            'laporan.permintaan-bahan',
                            'laporan.permintaan-pembelian',
                            'laporan.barang-masuk',
                            'laporan.penggunaan-bahan',
                        ]);
                    @endphp
                    <li>
                        <a class="sidebar-section" data-bs-toggle="collapse" href="#groupLaporan" role="button"
                            aria-expanded="{{ $laporanActive ? 'true' : 'false' }}" aria-controls="groupLaporan">
                            Laporan
                            <i class="bi bi-chevron-down ms-auto collapse-caret"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse {{ $laporanActive ? 'show' : '' }}" id="groupLaporan"
                            data-bs-parent="#sidebarAccordion">
                            <ul class="nav flex-column">
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
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (auth()->user()->isPetugasGudang())
                    {{-- ===================== GUDANG ===================== --}}
                    @php
                        $ac = \App\Models\BahanBaku::where('is_active', true)
                            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
                            ->count();
                        $gudangActive = request()->routeIs([
                            'gudang.stok.*',
                            'gudang.barang-masuk.*',
                            'gudang.stock-opname.*',
                        ]);
                    @endphp
                    <li>
                        <a class="sidebar-section" data-bs-toggle="collapse" href="#groupGudang" role="button"
                            aria-expanded="{{ $gudangActive ? 'true' : 'false' }}" aria-controls="groupGudang">
                            Gudang
                            <i class="bi bi-chevron-down ms-auto collapse-caret"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse {{ $gudangActive ? 'show' : '' }}" id="groupGudang"
                            data-bs-parent="#sidebarAccordion">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('gudang.stok.index') }}"
                                        class="nav-link {{ request()->routeIs('gudang.stok.*') ? 'active' : '' }}">
                                        <i class="bi bi-box-seam"></i> Stok Bahan Baku
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
                            </ul>
                        </div>
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
                    {{-- ===================== DAPUR ===================== --}}
                    @php
                        $dapurActive = request()->routeIs(['dapur.produksi.*']);
                    @endphp
                    <li>
                        <a class="sidebar-section" data-bs-toggle="collapse" href="#groupDapur" role="button"
                            aria-expanded="{{ $dapurActive ? 'true' : 'false' }}" aria-controls="groupDapur">
                            Dapur
                            <i class="bi bi-chevron-down ms-auto collapse-caret"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse {{ $dapurActive ? 'show' : '' }}" id="groupDapur"
                            data-bs-parent="#sidebarAccordion">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('dapur.produksi.index') }}"
                                        class="nav-link {{ request()->routeIs('dapur.produksi.*') ? 'active' : '' }}">
                                        <i class="bi bi-fire"></i> Produksi Harian
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>

        {{-- ===================== USER PANEL (DROPDOWN) ===================== --}}
        {{-- <div class="p-3" style="border-top:1px solid rgba(250,246,236,0.12);">
            <div class="dropdown">
                <a href="#"
                    class="d-flex align-items-center gap-2 p-2 navbar-user text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="overflow-hidden flex-grow-1">
                        <div class="text-white fw-semibold text-truncate">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size:0.78rem;color:rgba(250,246,236,0.6);">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end navbar-user-menu"> --}}
        {{-- <li>
                        <a class="dropdown-item"
                            href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}">
                            <i class="bi bi-person-circle me-2"></i> Profil
                        </a>
                    </li> --}}
        {{-- <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div> --}}
    </nav>
    <div id="content">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-semibold text-muted">@yield('page_title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-4 me-2">
                <span class="text-muted" style="font-size:0.85rem;">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </span>
                <div class="dropdown">
                    <a href="#"
                        class="navbar-user d-flex align-items-center text-decoration-none text-dark dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center"
                            style="width:36px;height:36px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="ms-2 text-start">
                            <div class="fw-semibold">
                                {{ auth()->user()->name }}
                            </div>
                            <small class="text-muted">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end navbar-user-menu">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger px-2 py-1">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="app-main">
            @if (session('success'))
                <div id="flash-data" data-type="success" data-message="{{ session('success') }}"></div>
            @endif
            @if (session('error'))
                <div id="flash-data" data-type="error" data-message="{{ session('error') }}"></div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.min.js"></script>
    <script src="/vendor/sweetalert2/confetti.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        // Select dengan pencarian (Tom Select) untuk semua .form-select,
        // kecuali yang diberi class 'no-tomselect' atau select bawaan DataTables
        function initSelectSearch(root) {
            if (typeof TomSelect === 'undefined') return;
            var scope = root || document;
            var selects = scope.querySelectorAll ? scope.querySelectorAll('select.form-select:not(.no-tomselect)') : [];
            selects.forEach(function(el) {
                if (el.tomselect) return;
                if (el.closest('.dataTables_wrapper')) return; // length menu DataTables
                var placeholderOpt = el.querySelector('option[value=""]');
                new TomSelect(el, {
                    create: false,
                    maxOptions: null,
                    placeholder: placeholderOpt ? placeholderOpt.textContent.trim() : 'Pilih...',
                    sortField: [{
                        field: '$order'
                    }] // pertahankan urutan asli dari server
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Terapkan DataTable ke semua tabel .table kecuali yang diberi class 'no-datatable'
            $('table.table:not(.no-datatable)').each(function() {
                var $table = $(this);

                // Lewati tabel tanpa thead (bukan tabel data)
                if ($table.find('thead th').length === 0) return;

                // Lewati tabel kosong (baris placeholder memakai colspan yang membuat DataTables error)
                if ($table.find('tbody td[colspan]').length > 0) return;

                // Kolom "Aksi" tidak perlu bisa di-sort
                var columnDefs = [];
                $table.find('thead th').each(function(index) {
                    if ($(this).text().trim().toLowerCase() === 'aksi') {
                        columnDefs.push({
                            orderable: false,
                            searchable: false,
                            targets: index
                        });
                    }
                });

                $table.DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 20, 30],
                        [10, 20, 30]
                    ],
                    order: [],
                    columnDefs: columnDefs,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                        infoFiltered: "(disaring dari _MAX_ total entri)",
                        zeroRecords: "Tidak ada data yang cocok ditemukan",
                        emptyTable: "Tidak ada data",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "Lanjut",
                            previous: "Sebelum"
                        }
                    }
                });
            });

            // Inisialisasi select+search di seluruh halaman (setelah DataTables selesai dibuat)
            initSelectSearch(document);
        });
    </script>
    @stack('scripts')
</body>

</html>
