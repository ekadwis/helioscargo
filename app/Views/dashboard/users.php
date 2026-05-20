<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="mb-0 text-primary"><?= $totalUsers ?></h2>
                    <p class="text-muted mb-0">Total User</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="mb-0 text-danger"><?= $totalSuperadmin ?></h2>
                    <p class="text-muted mb-0">Superadmin</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="mb-0 text-success"><?= $totalAdmin ?></h2>
                    <p class="text-muted mb-0">Admin</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manajemen User</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Tambah User
            </button>
        </div>

        <div class="card-body">

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

            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari username / nama / outlet...">
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Outlet</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <?php if (!empty($users)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $u) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $u['full_name'] ?? '-' ?></strong></td>
                                    <td><?= $u['username'] ?></td>
                                    <td>
                                        <span class="badge <?= $u['role'] === 'superadmin' ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= $u['role'] === 'superadmin' ? 'Superadmin' : 'Admin' ?>
                                        </span>
                                    </td>
                                    <td><?= $u['outlet_name'] ?? '-' ?></td>
                                    <td>
                                        <?php if ((int)$u['is_active']) : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning btn-edit-user"
                                            data-id="<?= $u['id'] ?>"
                                            data-full_name="<?= $u['full_name'] ?>"
                                            data-username="<?= $u['username'] ?>"
                                            data-role="<?= $u['role'] ?>"
                                            data-outlet_id="<?= $u['outlet_id'] ?>"
                                            data-is_active="<?= $u['is_active'] ?>">
                                            Edit
                                        </button>

                                        <?php if ($u['id'] != session()->get('user_id')) : ?>
                                            <form action="/users/delete/<?= $u['id'] ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus user <?= $u['username'] ?>?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <span class="text-muted small">(Anda)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small id="tableInfo"></small>
                    <nav><ul class="pagination pagination-sm mb-0" id="pagination"></ul></nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/users/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Outlet</label>
                            <select name="outlet_id" class="form-control" required>
                                <option value="">-- Pilih Outlet --</option>
                                <?php foreach ($outlets as $o) : ?>
                                    <option value="<?= $o['id'] ?>"><?= $o['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
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

<!-- MODAL EDIT USER -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" id="edit_username" class="form-control" disabled>
                            <small class="text-muted">Username tidak bisa diubah.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="edit_role" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Outlet</label>
                            <select name="outlet_id" id="edit_outlet_id" class="form-control" required>
                                <?php foreach ($outlets as $o) : ?>
                                    <option value="<?= $o['id'] ?>"><?= $o['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label" for="edit_is_active">Aktif</label>
                            </div>
                        </div>

                    </div>
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

    // TABLE + PAGINATION
    const tableBody   = document.getElementById("userTable");
    const rows        = Array.from(tableBody.querySelectorAll("tr"));
    const searchInput = document.getElementById("searchInput");
    const pagination  = document.getElementById("pagination");
    const tableInfo   = document.getElementById("tableInfo");

    let filteredRows = [...rows];
    let currentPage  = 1;
    const rowsPerPage = 10;

    function showPage() {
        rows.forEach(row => row.style.display = "none");
        const visibleRows = filteredRows.filter(row => !row.querySelector("td[colspan]"));
        const start = (currentPage - 1) * rowsPerPage;
        const end   = start + rowsPerPage;
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

    // EDIT USER MODAL
    $(document).on('click', '.btn-edit-user', function() {
        const btn = this;
        $('#edit_full_name').val(btn.getAttribute('data-full_name'));
        $('#edit_username').val(btn.getAttribute('data-username'));
        $('#edit_role').val(btn.getAttribute('data-role'));
        $('#edit_outlet_id').val(btn.getAttribute('data-outlet_id'));
        $('#edit_is_active').prop('checked', btn.getAttribute('data-is_active') == '1');
        $('#editUserForm').attr('action', '/users/update/' + btn.getAttribute('data-id'));
        $('#editUserModal').modal('show');
    });

});
</script>
<?= $this->endSection() ?>