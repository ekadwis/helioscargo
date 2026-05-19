<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Outlets</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOutletModal">
                Tambah Outlet
            </button>
        </div>

        <div class="card-body">

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php $errors = session()->getFlashdata('error');
                    if (is_array($errors)) :
                        foreach ($errors as $err) : ?>
                            <div><?= $err ?></div>
                        <?php endforeach;
                    else : ?>
                        <div><?= $errors ?></div>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari kode / nama / tipe...">
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="outletTable">
                        <?php if (!empty($outlets)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($outlets as $outlet) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $outlet['code'] ?></strong></td>
                                    <td><?= $outlet['name'] ?></td>
                                    <td>
                                        <?php
                                        $typeLabel = [
                                            'outlet'         => ['label' => 'Outlet',          'class' => 'bg-primary'],
                                            'hub'            => ['label' => 'Hub',              'class' => 'bg-warning'],
                                            'warehouse'      => ['label' => 'Warehouse',        'class' => 'bg-info'],
                                            'sorting_center' => ['label' => 'Sorting Center',   'class' => 'bg-secondary'],
                                        ];
                                        $t = $typeLabel[$outlet['type']] ?? ['label' => $outlet['type'], 'class' => 'bg-secondary'];
                                        ?>
                                        <span class="badge <?= $t['class'] ?>"><?= $t['label'] ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        // Cari nama lokasi dari array $locations
                                        $lokasiLabel = '-';
                                        foreach ($locations as $loc) {
                                            if ($loc['id'] == $outlet['location_id']) {
                                                $lokasiLabel = $loc['kelurahan'] . ', ' . $loc['kecamatan'];
                                                break;
                                            }
                                        }
                                        echo $lokasiLabel;
                                        ?>
                                    </td>
                                    <td><?= $outlet['address'] ?? '-' ?></td>
                                    <td><?= $outlet['phone'] ?? '-' ?></td>
                                    <td>
                                        <?php if ((int)$outlet['is_active'] === 1) : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/outlet/edit/<?= $outlet['id'] ?>" class="btn btn-sm btn-warning">Edit</a>

                                        <form action="/outlet/delete/<?= $outlet['id'] ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus outlet ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center">Data outlet belum ada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small id="tableInfo"></small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH OUTLET -->
<div class="modal fade" id="addOutletModal" tabindex="-1" aria-labelledby="addOutletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/outlet/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addOutletModalLabel">Tambah Outlet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Outlet</label>
                            <input type="text" name="code" class="form-control" placeholder="Contoh: OTK001" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Outlet</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="outlet">Outlet</option>
                                <option value="hub">Hub</option>
                                <option value="warehouse">Warehouse</option>
                                <option value="sorting_center">Sorting Center</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <select name="location_id" class="form-control select2-outlet-location">
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($locations as $location) : ?>
                                    <option value="<?= $location['id'] ?>">
                                        <?= $location['kelurahan'] . ', ' . $location['kecamatan'] . ', ' . $location['kabupaten'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // TABLE + PAGINATION
        const tableBody = document.getElementById("outletTable");
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        const searchInput = document.getElementById("searchInput");
        const pagination = document.getElementById("pagination");
        const tableInfo = document.getElementById("tableInfo");

        let filteredRows = [...rows];
        let currentPage = 1;
        const rowsPerPage = 10;

        function showPage() {
            rows.forEach(row => row.style.display = "none");
            const visibleRows = filteredRows.filter(row => !row.querySelector("td[colspan]"));
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            visibleRows.forEach(row => row.style.display = "none");
            visibleRows.slice(start, end).forEach(row => row.style.display = "");

            const emptyRow = tableBody.querySelector("td[colspan]");
            if (emptyRow) {
                emptyRow.closest("tr").style.display = visibleRows.length === 0 ? "" : "none";
            }

            tableInfo.innerText = `Menampilkan ${visibleRows.length === 0 ? 0 : start + 1} - ${Math.min(end, visibleRows.length)} dari ${visibleRows.length} data`;
            createPagination(visibleRows.length);
        }

        function createPagination(totalData) {
            pagination.innerHTML = "";
            const totalPages = Math.ceil(totalData / rowsPerPage);
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = `page-item ${i === currentPage ? "active" : ""}`;
                const a = document.createElement("a");
                a.className = "page-link";
                a.href = "#";
                a.textContent = i;
                a.addEventListener("click", function(e) {
                    e.preventDefault();
                    currentPage = i;
                    showPage();
                });
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        searchInput.addEventListener("keyup", function() {
            const keyword = this.value.toLowerCase();
            filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(keyword));
            currentPage = 1;
            showPage();
        });

        showPage();

        // SELECT2 LOKASI DI MODAL
        $('#addOutletModal').on('shown.bs.modal', function() {
            $('.select2-outlet-location').select2({
                dropdownParent: $('#addOutletModal'),
                placeholder: 'Cari lokasi...',
                allowClear: true,
                width: '100%'
            });
        });
    });
</script>
<?= $this->endSection(); ?>