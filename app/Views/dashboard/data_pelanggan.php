<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="page-content active" id="page-pelanggan">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Pelanggan</h3>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan" style="padding:0.5rem 1rem;font-size:0.85rem;">
                <i class="bi bi-plus-lg"></i> Tambah Pelanggan
            </button>
        </div>

        <div class="card-body">

            <div style="overflow-x:auto">
                <form method="get" style="margin-bottom:1rem;">
                    <div class="input-group" style="max-width:300px;">
                        <div class="mb-3" style="max-width:300px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari pelanggan...">
                        </div>
                    </div>
                </form>

                <?php if (session()->getFlashdata('error')) : ?>

                    <div class="alert alert-danger alert-dismissible fade show auto-alert" role="alert">

                        <?php foreach (session()->getFlashdata('error') as $err) : ?>
                            <div><?= $err ?></div>
                        <?php endforeach; ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>

                <?php endif; ?>


                <?php if (session()->getFlashdata('success')) : ?>

                    <div class="alert alert-success alert-dismissible fade show auto-alert" role="alert">

                        <?= session()->getFlashdata('success') ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>

                <?php endif; ?>

                <table class="data-table table table-hover">

                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Nama Pelanggan</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Kota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="customerTable">

                        <?php foreach ($customers as $index => $c) : ?>

                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <strong><?= esc($c['name']) ?></strong>
                                </td>
                                <td><?= esc($c['email']) ?></td>
                                <td><?= esc($c['phone']) ?></td>
                                <td><?= esc($c['kabupaten']) ?></td>
                                <td>
                                    <span class="status-badge delivered">Aktif</span>
                                </td>

                                <td>
                                    <button class="btn btn-secondary editCustomerBtn" data-id="<?= $c['id'] ?>" data-name="<?= esc($c['name']) ?>" data-email="<?= esc($c['email']) ?>" data-phone="<?= esc($c['phone']) ?>" data-address="<?= esc($c['address']) ?>" data-type="<?= esc($c['type']) ?>" data-location="<?= esc($c['location_id']) ?>" style="padding:0.25rem 0.5rem;font-size:0.75rem;" data-bs-toggle="modal" data-bs-target="#modalEditCustomer">

                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <a href="<?= base_url('pelanggan/delete/' . $c['id']) ?>" class="btn btn-danger" style="padding:0.25rem 0.5rem;font-size:0.75rem;" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">

                                        <i class="bi bi-trash"></i>
                                    </a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

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

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="<?= base_url('pelanggan/create') ?>" method="post">
                    <?= csrf_field(); ?>

                    <!-- INPUT PENGIRIM -->
                    <div class="border rounded p-3 mb-4">
                        <h5 class="mb-3">Input Pengirim</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="sender_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe</label>
                                <select name="sender_type" class="form-select">
                                    <option value="Perusahaan">Perusahaan</option>
                                    <option value="Perorangan">Individu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="sender_email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="sender_phone" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="sender_address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Lokasi</label>
                                <select name="sender_location_id" class="form-select">
                                    <option value="">Pilih lokasi...</option>
                                    <option value="6">Jakarta Selatan</option>
                                    <option value="7">Bandung</option>
                                    <option value="8">Surabaya</option>
                                    <option value="9">Yogyakarta</option>
                                    <option value="10">Medan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- INPUT PENERIMA -->
                    <div class="border rounded p-3 mb-3">
                        <h5 class="mb-3">Input Penerima</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="receiver_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe</label>
                                <select name="receiver_type" class="form-select">
                                    <option value="Perusahaan">Perusahaan</option>
                                    <option value="Perorangan">Individu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="receiver_email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="receiver_phone" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="receiver_address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Lokasi</label>
                                <select name="receiver_location_id" class="form-select">
                                    <option value="">Pilih lokasi...</option>
                                    <option value="6">Jakarta Selatan</option>
                                    <option value="7">Bandung</option>
                                    <option value="8">Surabaya</option>
                                    <option value="9">Yogyakarta</option>
                                    <option value="10">Medan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan Pengirim & Penerima
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<!-- Modal Edit Pelanggan -->
<div class="modal fade" id="modalEditCustomer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="<?= base_url('pelanggan/update') ?>" method="post">

                    <?= csrf_field(); ?>

                    <input type="hidden" name="id" id="edit_id">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="type" id="edit_type" class="form-select">
                                <option value="Perusahaan">Perusahaan</option>
                                <option value="Perorangan">Individu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Telepon</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Alamat</label>
                            <textarea name="address" id="edit_address" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Lokasi</label>

                            <select name="location_id" id="edit_location" class="form-select">

                                <option value="6">Jakarta Selatan</option>
                                <option value="7">Bandung</option>
                                <option value="8">Surabaya</option>
                                <option value="9">Yogyakarta</option>
                                <option value="10">Medan</option>

                            </select>

                        </div>

                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    const tableBody = document.getElementById("customerTable");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    const searchInput = document.getElementById("searchInput");
    const pagination = document.getElementById("pagination");
    const tableInfo = document.getElementById("tableInfo");

    let filteredRows = [...rows];
    let currentPage = 1;
    const rowsPerPage = 10;

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

    searchInput.addEventListener("keyup", function() {

        const keyword = this.value.toLowerCase();

        filteredRows = rows.filter(row =>
            row.innerText.toLowerCase().includes(keyword)
        );

        currentPage = 1;

        showPage();
    });

    showPage();
</script>

<script>
    new TomSelect("#locationSelect", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: "Cari lokasi..."
    });

    document.querySelectorAll(".editCustomerBtn").forEach(btn => {

        btn.addEventListener("click", function() {

            document.getElementById("edit_id").value = this.dataset.id;
            document.getElementById("edit_name").value = this.dataset.name;
            document.getElementById("edit_email").value = this.dataset.email;
            document.getElementById("edit_phone").value = this.dataset.phone;
            document.getElementById("edit_address").value = this.dataset.address;
            document.getElementById("edit_type").value = this.dataset.type;
            document.getElementById("edit_location").value = this.dataset.location;

        });

    });
</script>
<script>
    setTimeout(() => {

        document.querySelectorAll(".auto-alert").forEach(alert => {

            alert.classList.remove("show");

            setTimeout(() => {
                alert.remove();
            }, 500);

        });

    }, 5000);
</script>

<?= $this->endSection() ?>