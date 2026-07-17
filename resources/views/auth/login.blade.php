<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventory MBG</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logord.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/vendor/sweetalert2/theme-bootstrap-4.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="/css/login.css" rel="stylesheet">
</head>

<body>
    <div class="stage">
        <!-- LEFT: brand / crate panel -->
        <div class="brand-panel">
            <div class="crate-mark">Rantai Pasok Dapur</div>

            <div class="brand-mid">
                <div class="brand-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-sidebar">
                </div>
                <h1>Inventory MBG</h1>
                <p>Pantau stok bahan pangan, distribusi, dan penerimaan gudang dari satu dapur ke ribuan porsi makan
                    bergizi.</p>
                <div class="unit-tag"><i class="bi bi-geo-alt"></i> SPPG Sumber Jaya 03 · Tambun Selatan</div>
            </div>

            <div class="stat-row">
                <div><span>3</span><small>Peran Akses</small></div>
                <div><span>24/7</span><small>Pemantauan Stok</small></div>
                <div><span>01</span><small>Titik Dapur</small></div>
            </div>
        </div>

        <!-- RIGHT: ticket-style login card -->
        <div class="form-panel">
            <div class="ticket">
                <div class="perf">
                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                </div>
                <div class="ticket-inner">
                    <div class="eyebrow-row">
                        <span class="eyebrow">Form · Akses-01</span>
                        <div class="stamp">Akses<br>SPPG</div>
                    </div>

                    <h2>Selamat datang</h2>
                    <p class="sub">Masuk untuk melanjutkan ke sistem inventory.</p>

                    @if ($errors->any())
                        <div id="loginError" class="d-none">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="field">
                            <label>Username</label>
                            <div class="field-wrap @error('username') is-invalid @enderror">
                                <i class="bi bi-person"></i>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    placeholder="Masukkan username" autofocus required>
                            </div>
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <div class="field-wrap">
                                <i class="bi bi-lock"></i>
                                <input type="password" name="password" placeholder="Masukkan password" required>
                            </div>
                        </div>
                        <div class="remember-row">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </button>
                    </form>

                    <div class="demo-box">
                        <span class="demo-label">Akun Demo</span>
                        <div class="demo-row"><span class="cred">admin / admin123</span><span
                                class="role">Administrator</span></div>
                        <div class="demo-row"><span class="cred">gudang / gudang123</span><span class="role">Petugas
                                Gudang</span></div>
                        <div class="demo-row"><span class="cred">dapur / dapur123</span><span class="role">Petugas
                                Dapur</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.min.js"></script>
    <script src="/js/login.js"></script>
</body>

</html>
