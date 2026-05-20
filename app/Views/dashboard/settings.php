<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">

        <!-- Profil Akun — semua role bisa akses -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Profil Akun
                    </h3>
                </div>
                <div class="card-body">
                    <form action="/settings/profile" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control"
                                value="<?= session()->get('username') ?>" disabled>
                            <small class="text-muted">Username tidak bisa diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control"
                                value="<?= $user['full_name'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control"
                                value="<?= session()->get('role') === 'superadmin' ? 'Super Administrator' : 'Administrator' ?>"
                                disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Password Baru
                                <small class="text-muted">(kosongkan jika tidak diubah)</small>
                            </label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Minimal 6 karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Outlet</label>
                            <input type="text" class="form-control" disabled
                                value="<?= $user['outlet_id'] ?? '-' ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informasi Perusahaan — superadmin only -->
        <div class="col-lg-7 mb-4">
            <?php if (session()->get('role') === 'superadmin') : ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-building me-2"></i>Informasi Perusahaan
                    </h3>
                </div>
                <div class="card-body">
                    <form action="/settings/company" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="company_name" class="form-control"
                                value="<?= $settings['company_name'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="company_address" class="form-control" rows="3"><?= $settings['company_address'] ?? '' ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="company_phone" class="form-control"
                                    value="<?= $settings['company_phone'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="company_email" class="form-control"
                                    value="<?= $settings['company_email'] ?? '' ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            <?php else : ?>
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-lock fs-1"></i>
                    <p class="mt-3">Pengaturan perusahaan hanya bisa diakses oleh Superadmin.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?= $this->endSection() ?>