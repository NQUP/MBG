<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventory MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/vendor/sweetalert2/theme-bootstrap-4.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a7f5a 0%, #0d4a34 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header {
            background: linear-gradient(135deg, #1a7f5a 0%, #145e43 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 32px;
            text-align: center;
        }
        .login-header .logo { font-size: 3rem; margin-bottom: 8px; }
        .login-header h4 { font-weight: 700; margin: 0; }
        .login-header p { opacity: 0.85; font-size: 0.875rem; margin: 4px 0 0; }
        .login-body { padding: 32px; }
        .form-label { font-weight: 600; font-size: 0.875rem; }
        .btn-login {
            background: linear-gradient(135deg, #1a7f5a 0%, #145e43 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .btn-login:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="logo">🥗</div>
            <h4>Inventory MBG</h4>
            <p>SPPG Sumber Jaya 03 Tambun Selatan</p>
        </div>
        <div class="login-body">
            <h6 class="text-muted mb-4 text-center">Silakan masuk untuk melanjutkan</h6>

            @if($errors->any())
                <div id="loginError" class="d-none">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="Masukkan username" autofocus required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="mt-4 p-3 bg-light rounded" style="font-size:0.78rem;">
                <strong class="text-muted">Akun Demo:</strong><br>
                <span class="text-success fw-bold">admin</span> / admin123 — Administrator<br>
                <span class="text-warning fw-bold">gudang</span> / gudang123 — Petugas Gudang<br>
                <span class="text-primary fw-bold">dapur</span> / dapur123 — Petugas Dapur
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.min.js"></script>
    <script>
        @if($errors->any())
        Swal.fire({
            icon: "error",
            title: "Login Gagal",
            text: document.getElementById("loginError")?.textContent || "Username atau password salah.",
            confirmButtonColor: "#dc3545",
        });
        @endif
    </script>
</body>
</html>