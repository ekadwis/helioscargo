<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Outlet</h3>
        </div>
        <div class="card-body">
            <form action="/outlet/update/<?= $outlet['id'] ?>" method="post">
                <?= csrf_field() ?>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Outlet</label>
                        <input type="text" name="code" class="form-control" value="<?= $outlet['code'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Outlet</label>
                        <input type="text" name="name" class="form-control" value="<?= $outlet['name'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-control" required>
                            <?php foreach (['outlet' => 'Outlet', 'hub' => 'Hub', 'warehouse' => 'Warehouse', 'sorting_center' => 'Sorting Center'] as $val => $label) : ?>
                                <option value="<?= $val ?>" <?= $outlet['type'] === $val ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi</label>
                        <select name="location_id" class="form-control select2-edit-location">
                            <option value="">-- Pilih Lokasi --</option>
                            <?php foreach ($locations as $location) : ?>
                                <option value="<?= $location['id'] ?>" <?= $outlet['location_id'] == $location['id'] ? 'selected' : '' ?>>
                                    <?= $location['kelurahan'] . ', ' . $location['kecamatan'] . ', ' . $location['kabupaten'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2"><?= $outlet['address'] ?></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control" value="<?= $outlet['phone'] ?>">
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                <?= (int)$outlet['is_active'] === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/outlet" class="btn btn-light-secondary ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2-edit-location').select2({
        placeholder: 'Cari lokasi...',
        allowClear: true,
        width: '100%'
    });
});
</script>

<?= $this->endSection(); ?>