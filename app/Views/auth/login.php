<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HELIOSCARGO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-navy: #1e3a5f;
            --primary-navy-dark: #152a45;
            --accent-orange: #f97316;
            --accent-orange-hover: #ea6a0e;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            background: var(--bg-light);
            overflow: hidden;
        }

        /* Panel kiri — branding area */
        .login-brand-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-navy-dark) 60%, #0f1f33 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-brand-panel::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: var(--accent-orange);
            opacity: 0.06;
            border-radius: 50%;
        }

        .login-brand-panel::after {
            content: '';
            position: absolute;
            bottom: -15%;
            left: -10%;
            width: 350px;
            height: 350px;
            background: var(--accent-orange);
            opacity: 0.04;
            border-radius: 50%;
        }

        .brand-content {
            text-align: center;
            z-index: 1;
            max-width: 400px;
        }

        .brand-icon-wrapper {
            width: 80px;
            height: 80px;
            background: var(--accent-orange);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(249, 115, 22, 0.3);
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0.25rem;
        }

        .brand-title span {
            color: var(--accent-orange);
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
        }

        .brand-features {
            text-align: left;
            list-style: none;
            padding: 0;
        }

        .brand-features li {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-features li i {
            color: var(--accent-orange);
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        /* Panel kanan — form login */
        .login-form-panel {
            width: 480px;
            min-width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            background: #fff;
        }

        .login-form-wrapper {
            max-width: 360px;
            width: 100%;
            margin: 0 auto;
        }

        .login-greeting {
            margin-bottom: 2rem;
        }

        .login-greeting h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
        }

        .login-greeting p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .input-group-login {
            position: relative;
        }

        .input-group-login .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.7rem 0.9rem 0.7rem 2.75rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--bg-light);
        }

        .input-group-login .form-control:focus {
            border-color: var(--primary-navy);
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
            background: #fff;
        }

        .input-group-login .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 4;
            transition: color 0.2s;
        }

        .input-group-login .form-control:focus ~ .input-icon,
        .input-group-login .form-control:focus + .input-icon {
            color: var(--primary-navy);
        }

        .toggle-password {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            z-index: 4;
            padding: 0;
            font-size: 1.1rem;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary-navy);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-navy-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: #fff;
            width: 100%;
            transition: transform 0.15s, box-shadow 0.15s;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(30, 58, 95, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-login {
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-login i {
            margin-right: 0.5rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 2.5rem;
            color: #94a3b8;
            font-size: 0.8rem;
        }

        /* Responsive — di mobile brand panel disembunyikan */
        @media (max-width: 991.98px) {
            .login-brand-panel {
                display: none;
            }

            .login-form-panel {
                width: 100%;
                min-width: unset;
                min-height: 100vh;
            }
        }

        @media (max-width: 576px) {
            .login-form-panel {
                padding: 2rem 1.5rem;
            }

            .login-form-wrapper {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Branding panel (kiri) -->
    <div class="login-brand-panel">
        <div class="brand-content">
            <div class="brand-icon-wrapper">
                <i class="bi bi-truck"></i>
            </div>
            <div class="brand-title">HELIOS<span>CARGO</span></div>
            <p class="brand-subtitle">Sistem Manajemen Pengiriman</p>

            <ul class="brand-features">
                <li><i class="bi bi-box-seam"></i> Kelola shipment & tracking real-time</li>
                <li><i class="bi bi-qr-code-scan"></i> Scan AWB & cetak resi otomatis</li>
                <li><i class="bi bi-building"></i> Multi-outlet & manifest management</li>
                <li><i class="bi bi-bar-chart-line"></i> Laporan & analitik lengkap</li>
            </ul>
        </div>
    </div>

    <!-- Form login panel (kanan) -->
    <div class="login-form-panel">
        <div class="login-form-wrapper">

            <!-- Mobile-only branding -->
            <div class="d-lg-none text-center mb-4">
                <div class="brand-icon-wrapper" style="width:56px;height:56px;font-size:1.5rem;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;background:var(--accent-orange);color:#fff;box-shadow:0 4px 16px rgba(249,115,22,0.25);">
                    <i class="bi bi-truck"></i>
                </div>
                <div style="font-size:1.25rem;font-weight:700;color:var(--text-dark);margin-top:0.75rem;">
                    HELIOS<span style="color:var(--accent-orange);">CARGO</span>
                </div>
            </div>

            <div class="login-greeting">
                <h2>Selamat Datang 👋</h2>
                <p>Masukkan kredensial untuk mengakses dashboard</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-login mb-3">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group-login">
                        <input type="text" name="username" class="form-control"
                            placeholder="Masukkan username"
                            value="<?= old('username') ?>" autofocus required>
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group-login">
                        <input type="password" name="password" id="passwordField" class="form-control"
                            placeholder="Masukkan password" required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
                </button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> HELIOSCARGO &mdash; All rights reserved.
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById('passwordField');
            const icon = document.getElementById('toggleIcon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>