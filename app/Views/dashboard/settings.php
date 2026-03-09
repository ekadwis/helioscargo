<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="page-content active" id="page-settings">

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show auto-alert" role="alert">
            <?php
            $errorData = session()->getFlashdata('error');
            if (is_array($errorData)) :
                foreach ($errorData as $err) :
            ?>
                    <div><?= esc($err) ?></div>
                <?php endforeach; ?>
            <?php else : ?>
                <div><?= esc($errorData) ?></div>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show auto-alert" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-building me-2"></i>Informasi Perusahaan
                    </h3>
                </div>

                <div class="card-body">
                    <form id="formSettings" onsubmit="return saveCompanySettings(event)">
                        <div class="mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="companyNameInput" value="HELIOSCARGO">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="3">Jl. Logistik Raya No. 123, Jakarta Utara</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" value="021-5551234">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="info@helioscargo.com">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-bell me-2"></i>Notifikasi
                    </h3>
                </div>

                <div class="card-body">
                    <form id="formNotifications" onsubmit="return saveNotificationSettings(event)">
                        <div class="d-flex flex-column gap-3">
                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span>Notifikasi pengiriman baru</span>
                            </label>

                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span>Notifikasi invoice jatuh tempo</span>
                            </label>

                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" style="width: 18px; height: 18px;">
                                <span>Notifikasi stok rendah</span>
                            </label>

                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span>Email ringkasan harian</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">
                            <i class="bi bi-save me-1"></i> Simpan Notifikasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-danger">
                        <i class="bi bi-shield-exclamation me-2"></i>Zona Bahaya
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-muted" style="font-size: 0.9rem;">
                        Tindakan berikut tidak dapat dibatalkan.
                    </p>

                    <button class="btn btn-outline-danger" onclick="resetAllData()">
                        <i class="bi bi-trash me-1"></i> Reset Semua Data
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function handleDummySave(button) {
        const originalText = button.innerHTML;

        button.disabled = true;
        button.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersimpan';
        button.classList.remove('btn-primary');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-primary');
        }, 1500);
    }

    function saveCompanySettings(event) {
        event.preventDefault();
        const button = event.target.querySelector('button[type="submit"]');
        handleDummySave(button);
        return false;
    }

    function saveAppearanceSettings(event) {
        event.preventDefault();
        const button = event.target.querySelector('button[type="submit"]');
        handleDummySave(button);
        return false;
    }

    function saveNotificationSettings(event) {
        event.preventDefault();
        const button = event.target.querySelector('button[type="submit"]');
        handleDummySave(button);
        return false;
    }

    function resetAllData() {
        const confirmed = confirm('Yakin ingin reset semua data? Tindakan ini tidak dapat dibatalkan.');
        if (confirmed) {
            alert('Dummy reset semua data berhasil dijalankan.');
        }
    }
</script>
<?= $this->endSection(); ?>