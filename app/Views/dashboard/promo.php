<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manajemen Promo</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromoModal">
                Tambah Promo
            </button>
        </div>

        <div class="card-body">

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Badge</th>
                            <th>Berlaku s/d</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($promos)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($promos as $p) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $p['title'] ?></strong></td>
                                    <td><small><?= substr($p['description'], 0, 60) ?>...</small></td>
                                    <td>
                                        <span class="badge bg-<?= $p['badge_color'] ?> text-<?= $p['badge_color'] === 'warning' ? 'dark' : 'white' ?>">
                                            <?= $p['badge_text'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= $p['valid_until'] ? date('d M Y', strtotime($p['valid_until'])) : '-' ?></small>
                                    </td>
                                    <td>
                                        <?php if ((int)$p['is_active']) : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning btn-edit-promo"
                                            data-id="<?= $p['id'] ?>"
                                            data-title="<?= ($p['title']) ?>"
                                            data-description="<?= ($p['description']) ?>"
                                            data-badge_text="<?= ($p['badge_text']) ?>"
                                            data-badge_color="<?= $p['badge_color'] ?>"
                                            data-valid_until="<?= $p['valid_until'] ?>"
                                            data-is_active="<?= $p['is_active'] ?>">
                                            Edit
                                        </button>
                                        <form action="/promo/delete/<?= $p['id'] ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus promo ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="8" class="text-center">Belum ada promo.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="addPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/promo/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= view('dashboard/_promo_form') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPromoForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= view('dashboard/_promo_form') ?>
                    <div class="mt-2" id="currentImagePreview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-edit-promo', function() {
        const btn = this;
        const id  = btn.getAttribute('data-id');

        $('#editPromoForm')[0].reset();
        $('#editPromoForm').attr('action', '/promo/update/' + id);
        $('#editPromoForm [name="title"]').val(btn.getAttribute('data-title'));
        $('#editPromoForm [name="description"]').val(btn.getAttribute('data-description'));
        $('#editPromoForm [name="badge_text"]').val(btn.getAttribute('data-badge_text'));
        $('#editPromoForm [name="badge_color"]').val(btn.getAttribute('data-badge_color'));
        $('#editPromoForm [name="valid_until"]').val(btn.getAttribute('data-valid_until'));
        $('#editPromoForm [name="is_active"]').prop('checked', btn.getAttribute('data-is_active') == '1');

        const img = btn.getAttribute('data-image');
        if (img) {
            $('#currentImagePreview').html(`
                <small class="text-muted">Gambar saat ini:</small><br>
                <img src="${img}" style="height:60px;border-radius:6px;margin-top:4px;">
                <small class="text-muted d-block mt-1">Upload baru untuk mengganti.</small>
            `);
        } else {
            $('#currentImagePreview').html('');
        }

        $('#editPromoModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>