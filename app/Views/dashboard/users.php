<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="page-content active" id="page-user">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="card-title mb-0">Manajemen User</h3>

            <button type="button" class="btn btn-primary" onclick="openAddUserModal()">
                <i class="bi bi-plus-lg"></i> Tambah User
            </button>
        </div>

        <div class="card-body">

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

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div style="max-width: 300px; width: 100%;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama / email / role / status...">
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <select class="form-select form-select-sm" id="filterRole" style="width: 180px;">
                        <option value="">Semua Role</option>
                        <option value="SUPER_ADMIN">Super Admin</option>
                        <option value="MANAGER">Manager</option>
                        <option value="STAFF">Staff</option>
                    </select>

                    <select class="form-select form-select-sm" id="filterStatus" style="width: 180px;">
                        <option value="">Semua Status</option>
                        <option value="AKTIF">Aktif</option>
                        <option value="NON_AKTIF">Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terakhir Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="userTable">
                        <tr data-role="SUPER_ADMIN" data-status="AKTIF">
                            <td>1</td>
                            <td>USR-001</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; background: #f97316; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        AD
                                    </div>
                                    <strong>Admin User</strong>
                                </div>
                            </td>
                            <td>admin@helioscargo.com</td>
                            <td>
                                <span class="badge" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                                    Super Admin
                                </span>
                            </td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>Hari ini, 09:15</td>
                            <td>
                                <button class="btn btn-sm btn-warning" id="edit-user-1" onclick="editUser('USR-001')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" id="delete-user-1" onclick="deleteUser('USR-001')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-role="MANAGER" data-status="AKTIF">
                            <td>2</td>
                            <td>USR-002</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; background: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        BS
                                    </div>
                                    <strong>Budi Santoso</strong>
                                </div>
                            </td>
                            <td>budi@helioscargo.com</td>
                            <td>
                                <span class="badge bg-primary">
                                    Manager
                                </span>
                            </td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>Kemarin, 17:30</td>
                            <td>
                                <button class="btn btn-sm btn-warning" id="edit-user-2" onclick="editUser('USR-002')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" id="delete-user-2" onclick="deleteUser('USR-002')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-role="STAFF" data-status="NON_AKTIF">
                            <td>3</td>
                            <td>USR-003</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; background: #22c55e; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        SR
                                    </div>
                                    <strong>Siti Rahayu</strong>
                                </div>
                            </td>
                            <td>siti@helioscargo.com</td>
                            <td>
                                <span class="badge bg-secondary">
                                    Staff
                                </span>
                            </td>
                            <td><span class="badge bg-danger">Non-Aktif</span></td>
                            <td>5 hari lalu</td>
                            <td>
                                <button class="btn btn-sm btn-warning" id="edit-user-3" onclick="editUser('USR-003')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" id="delete-user-3" onclick="deleteUser('USR-003')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-role="STAFF" data-status="AKTIF">
                            <td>4</td>
                            <td>USR-004</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; background: #ec4899; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        DP
                                    </div>
                                    <strong>Dewi Putri</strong>
                                </div>
                            </td>
                            <td>dewi@helioscargo.com</td>
                            <td>
                                <span class="badge bg-secondary">
                                    Staff
                                </span>
                            </td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>Hari ini, 08:05</td>
                            <td>
                                <button class="btn btn-sm btn-warning" id="edit-user-4" onclick="editUser('USR-004')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" id="delete-user-4" onclick="deleteUser('USR-004')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-role="MANAGER" data-status="AKTIF">
                            <td>5</td>
                            <td>USR-005</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; background: #14b8a6; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        RN
                                    </div>
                                    <strong>Rian Nugraha</strong>
                                </div>
                            </td>
                            <td>rian@helioscargo.com</td>
                            <td>
                                <span class="badge bg-primary">
                                    Manager
                                </span>
                            </td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>2 jam lalu</td>
                            <td>
                                <button class="btn btn-sm btn-warning" id="edit-user-5" onclick="editUser('USR-005')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" id="delete-user-5" onclick="deleteUser('USR-005')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small id="tableInfo"></small>
                    </div>

                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Dummy Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form onsubmit="return saveDummyUser(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="SUPER_ADMIN">Super Admin</option>
                            <option value="MANAGER">Manager</option>
                            <option value="STAFF">Staff</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" required>
                            <option value="AKTIF">Aktif</option>
                            <option value="NON_AKTIF">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const tableBody = document.getElementById("userTable");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    const searchInput = document.getElementById("searchInput");
    const filterRole = document.getElementById("filterRole");
    const filterStatus = document.getElementById("filterStatus");
    const pagination = document.getElementById("pagination");
    const tableInfo = document.getElementById("tableInfo");

    let filteredRows = [...rows];
    let currentPage = 1;
    const rowsPerPage = 10;

    function applyFilters() {
        const keyword = searchInput.value.toLowerCase();
        const role = filterRole.value;
        const status = filterStatus.value;

        filteredRows = rows.filter(row => {
            const textMatch = row.innerText.toLowerCase().includes(keyword);
            const roleMatch = !role || row.dataset.role === role;
            const statusMatch = !status || row.dataset.status === status;

            return textMatch && roleMatch && statusMatch;
        });

        currentPage = 1;
        showPage();
    }

    function showPage() {
        rows.forEach(row => row.style.display = "none");

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        filteredRows.slice(start, end).forEach(row => {
            row.style.display = "";
        });

        tableInfo.innerText =
            `Menampilkan ${filteredRows.length === 0 ? 0 : start + 1} - ${Math.min(end, filteredRows.length)} dari ${filteredRows.length} data`;

        createPagination();
    }

    function createPagination() {
        pagination.innerHTML = "";

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

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

    function openAddUserModal() {
        const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
        modal.show();
    }

    function saveDummyUser(event) {
        event.preventDefault();

        const btn = event.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        btn.innerHTML = 'Tersimpan!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');

            const modalEl = document.getElementById('addUserModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            event.target.reset();
        }, 1000);

        return false;
    }

    function editUser(userId) {
        alert('Dummy edit user: ' + userId);
    }

    function deleteUser(userId) {
        if (confirm('Yakin ingin menghapus user ' + userId + '?')) {
            alert('Dummy hapus user: ' + userId);
        }
    }

    searchInput.addEventListener("keyup", applyFilters);
    filterRole.addEventListener("change", applyFilters);
    filterStatus.addEventListener("change", applyFilters);

    showPage();
</script>
<?= $this->endSection(); ?>