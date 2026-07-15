<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventory MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/vendor/sweetalert2/theme-bootstrap-4.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --forest: #16382b;
            --forest-deep: #0d271e;
            --leaf: #5fa876;
            --leaf-bright: #86c99a;
            --rice: #faf6ec;
            --rice-dim: #f0ead8;
            --turmeric: #d9822b;
            --ink: #22291f;
            --sage-line: #d8dcc9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--forest-deep);
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            display: flex;
        }

        .stage {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            width: 100%;
            min-height: 100vh;
        }

        /* ---------- LEFT: brand / crate panel ---------- */
        .brand-panel {
            position: relative;
            background:
                radial-gradient(circle at 15% 20%, rgba(134, 201, 154, 0.16), transparent 45%),
                repeating-linear-gradient(115deg, rgba(255, 255, 255, 0.025) 0px, rgba(255, 255, 255, 0.025) 2px, transparent 2px, transparent 34px),
                linear-gradient(160deg, var(--forest) 0%, var(--forest-deep) 100%);
            color: var(--rice);
            padding: 56px 56px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            inset: 18px;
            border: 1px solid rgba(250, 246, 236, 0.16);
            border-radius: 2px;
            pointer-events: none;
        }

        .crate-mark {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--leaf-bright);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .crate-mark::before {
            content: "";
            width: 22px;
            height: 1px;
            background: var(--leaf-bright);
        }

        .brand-mid {
            margin-top: 48px;
        }

        .brand-icon {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: rgba(250, 246, 236, 0.08);
            border: 1px solid rgba(250, 246, 236, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            margin-bottom: 28px;
        }

        .brand-mid h1 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 2.6rem;
            line-height: 1.08;
            margin: 0 0 14px;
            letter-spacing: -0.01em;
        }

        .brand-mid p {
            font-size: 0.98rem;
            line-height: 1.6;
            color: rgba(250, 246, 236, 0.72);
            max-width: 380px;
            margin: 0;
        }

        .unit-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding: 7px 14px;
            border: 1px dashed rgba(134, 201, 154, 0.55);
            border-radius: 999px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.76rem;
            color: var(--leaf-bright);
            letter-spacing: 0.02em;
        }

        .unit-tag i {
            font-size: 0.85rem;
        }

        .stat-row {
            display: flex;
            gap: 36px;
            margin-top: auto;
            padding-top: 40px;
            border-top: 1px solid rgba(250, 246, 236, 0.14);
        }

        .stat-row div span {
            display: block;
            font-family: 'Fraunces', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--rice);
        }

        .stat-row div small {
            font-size: 0.7rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(250, 246, 236, 0.55);
        }

        /* ---------- RIGHT: ticket panel ---------- */
        .form-panel {
            background: var(--rice);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .ticket {
            width: 100%;
            max-width: 400px;
            position: relative;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 30px 60px -20px rgba(13, 39, 30, 0.35);
            border: 1px solid var(--sage-line);
        }

        /* perforation strip */
        .ticket::before {
            content: "";
            position: absolute;
            top: -1px;
            left: 0;
            right: 0;
            height: 0;
            border-top: 2px dashed #c7cdb8;
        }

        .perf {
            display: flex;
            justify-content: space-between;
            padding: 0 6px;
            transform: translateY(-9px);
        }

        .perf span {
            width: 16px;
            height: 16px;
            background: var(--rice);
            border: 1px solid var(--sage-line);
            border-radius: 50%;
        }

        .ticket-inner {
            padding: 8px 40px 40px;
        }

        .eyebrow-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7a8570;
        }

        .stamp {
            width: 54px;
            height: 54px;
            border: 1.5px solid var(--turmeric);
            border-radius: 50%;
            color: var(--turmeric);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            transform: rotate(-9deg);
            opacity: 0.85;
            line-height: 1.1;
        }

        .ticket-inner h2 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.55rem;
            margin: 0 0 4px;
            color: var(--forest);
        }

        .ticket-inner .sub {
            font-size: 0.87rem;
            color: #63705a;
            margin: 0 0 28px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--forest);
            margin-bottom: 7px;
        }

        .field-wrap {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--sage-line);
            border-radius: 8px;
            background: var(--rice);
            padding: 0 14px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .field-wrap:focus-within {
            border-color: var(--leaf);
            box-shadow: 0 0 0 3px rgba(95, 168, 118, 0.18);
        }

        .field-wrap i {
            color: #8a9480;
            font-size: 0.95rem;
        }

        .field-wrap input {
            border: none;
            background: transparent;
            padding: 12px 10px;
            width: 100%;
            font-size: 0.94rem;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            outline: none;
        }

        .field-wrap.is-invalid {
            border-color: #c0503c;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-row input {
            width: 16px;
            height: 16px;
            accent-color: var(--leaf);
        }

        .remember-row label {
            font-size: 0.85rem;
            color: #55604c;
        }

        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 14px;
            background: var(--forest);
            color: var(--rice);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.94rem;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease;
        }

        .btn-submit:hover {
            background: #1e4634;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .demo-box {
            margin-top: 26px;
            padding: 14px 16px;
            border: 1px dashed var(--sage-line);
            border-radius: 8px;
            background: var(--rice);
        }

        .demo-box .demo-label {
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #7a8570;
            margin-bottom: 8px;
            display: block;
        }

        .demo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            padding: 4px 0;
        }

        .demo-row .cred {
            color: var(--forest);
            font-weight: 600;
        }

        .demo-row .role {
            color: #7a8570;
        }

        @media (max-width: 900px) {
            .stage {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                padding: 40px 28px;
            }

            .stat-row {
                display: none;
            }

            .brand-mid h1 {
                font-size: 2.1rem;
            }
        }
    </style>
</head>

<body>
    <div class="stage">
        <!-- LEFT: brand / crate panel -->
        <div class="brand-panel">
            <div class="crate-mark">Rantai Pasok Dapur</div>

            <div class="brand-mid">
                <div class="brand-icon">🥗</div>
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
    <script>
        @if ($errors->any())
            Swal.fire({
                icon: "error",
                title: "Login Gagal",
                text: document.getElementById("loginError")?.textContent || "Username atau password salah.",
                confirmButtonColor: "#c0503c",
            });
        @endif
    </script>
</body>

</html>
