<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manajemen Berita</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                Tambah Berita
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
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Excerpt</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($news)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($news as $n) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php if ($n['image_url']) : ?>
                                            <img src="<?= $n['image_url'] ?>" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">
                                        <?php else : ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= $n['title'] ?></strong></td>
                                    <td><small><?= substr($n['excerpt'], 0, 60) ?>...</small></td>
                                    <td>
                                        <?php if ((int)$n['is_published']) : ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?= date('d M Y', strtotime($n['published_at'])) ?></small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning btn-edit-news"
                                            data-id="<?= $n['id'] ?>"
                                            data-title="<?= esc($n['title']) ?>"
                                            data-excerpt="<?= esc($n['excerpt']) ?>"
                                            data-content="<?= esc($n['content']) ?>"
                                            data-is_published="<?= $n['is_published'] ?>"
                                            data-image="<?= $n['image_url'] ?>">
                                            Edit
                                        </button>
                                        <form action="/news/delete/<?= $n['id'] ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus berita ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="7" class="text-center">Belum ada berita.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/news/store" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= view('dashboard/_news_form') ?>
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
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editNewsForm" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= view('dashboard/_news_form') ?>
                    <div class="mt-2" id="currentNewsImagePreview"></div>
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
    $(document).on('click', '.btn-edit-news', function() {
        const btn = this;
        const id  = btn.getAttribute('data-id');

        $('#editNewsForm')[0].reset();
        $('#editNewsForm').attr('action', '/news/update/' + id);
        $('#editNewsForm [name="title"]').val(btn.getAttribute('data-title'));
        $('#editNewsForm [name="excerpt"]').val(btn.getAttribute('data-excerpt'));
        $('#editNewsForm [name="content"]').val(btn.getAttribute('data-content'));
        $('#editNewsForm [name="is_published"]').prop('checked', btn.getAttribute('data-is_published') == '1');

        const img = btn.getAttribute('data-image');
        if (img) {
            $('#currentNewsImagePreview').html(`
                <small class="text-muted">Gambar saat ini:</small><br>
                <img src="${img}" style="height:60px;border-radius:6px;margin-top:4px;">
                <small class="text-muted d-block mt-1">Upload baru untuk mengganti.</small>
            `);
        } else {
            $('#currentNewsImagePreview').html('');
        }

        $('#editNewsModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>