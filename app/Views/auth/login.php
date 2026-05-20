<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HELIOSCARGO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        .login-logo {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="card login-card p-4">
        <div class="text-center mb-4">
            <div class="login-logo text-primary">🚚 HELIOSCARGO</div>
            <p class="text-muted mt-1 mb-0">Sistem Manajemen Pengiriman</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger py-2">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                    value="<?= old('username') ?>" autofocus required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>