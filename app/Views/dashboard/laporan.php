<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="page-content active" id="page-laporan">

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Shipment</div>
                        <h3 class="mb-0">128</h3>
                    </div>
                    <div class="fs-2 text-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Pendapatan</div>
                        <h3 class="mb-0">Rp 7.850.000</h3>
                    </div>
                    <div class="fs-2 text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Delivered</div>
                        <h3 class="mb-0">74</h3>
                    </div>
                    <div class="fs-2 text-warning">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="card-title mb-0">Laporan Pengiriman</h3>

            <div class="d-flex gap-2 flex-wrap">
                <select class="form-select form-select-sm" style="width: 180px;" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="CREATED">CREATED</option>
                    <option value="PICKED_UP">PICKED UP</option>
                    <option value="IN_TRANSIT">IN TRANSIT</option>
                    <option value="ARRIVED_AT_BRANCH">ARRIVED AT BRANCH</option>
                    <option value="OUT_FOR_DELIVERY">OUT FOR DELIVERY</option>
                    <option value="DELIVERED">DELIVERED</option>
                </select>

                <input type="month" id="filterMonth" class="form-control form-control-sm" style="width: 180px;">

                <button type="button" class="btn btn-primary btn-sm" id="btnExport">
                    <i class="bi bi-download"></i> Export
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari AWB / barang / customer / tujuan...">
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show auto-alert" role="alert">
                    <?php
                    $errorData = session()->getFlashdata('error');
                    if (is_array($errorData)) :
                        foreach ($errorData as $err) :
                    ?>
                            <div><?= esc($err) ?></div>
                        <?php
                        endforeach;
                    else :
                        ?>
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

            <div style="overflow-x:auto;">
                <table class="data-table table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Tujuan</th>
                            <th>Service</th>
                            <th>Berat</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tbody id="reportTable">

                        <tr data-status="DELIVERED" data-month="2026-03">
                            <td>1</td>
                            <td><strong>AWB000001</strong></td>
                            <td>Laptop</td>
                            <td>Budi Santoso</td>
                            <td>Andi Wijaya</td>
                            <td>GAMBIR, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Express</td>
                            <td>2.50 kg</td>
                            <td>Rp 60.000</td>
                            <td><span class="badge bg-success">DELIVERED</span></td>
                            <td>09-03-2026 13:36</td>
                        </tr>

                        <tr data-status="IN_TRANSIT" data-month="2026-03">
                            <td>2</td>
                            <td><strong>AWB000002</strong></td>
                            <td>Baju</td>
                            <td>Siti Rahma</td>
                            <td>Dewi Lestari</td>
                            <td>KEBON KELAPA, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Regular</td>
                            <td>1.20 kg</td>
                            <td>Rp 25.000</td>
                            <td><span class="badge bg-primary">IN TRANSIT</span></td>
                            <td>09-03-2026 13:36</td>
                        </tr>

                        <tr data-status="CREATED" data-month="2026-03">
                            <td>3</td>
                            <td><strong>AWB000003</strong></td>
                            <td>Sepatu</td>
                            <td>Rina Marlina</td>
                            <td>Fajar Nugroho</td>
                            <td>PETOJO UTARA, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Express</td>
                            <td>1.80 kg</td>
                            <td>Rp 30.000</td>
                            <td><span class="badge bg-warning text-dark">CREATED</span></td>
                            <td>09-03-2026 13:36</td>
                        </tr>

                        <tr data-status="DELIVERED" data-month="2026-03">
                            <td>4</td>
                            <td><strong>AWB000004</strong></td>
                            <td>Dokumen</td>
                            <td>PT Sinar Jaya</td>
                            <td>CV Berkah Abadi</td>
                            <td>DURI PULO, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Economy</td>
                            <td>0.50 kg</td>
                            <td>Rp 20.000</td>
                            <td><span class="badge bg-success">DELIVERED</span></td>
                            <td>09-03-2026 13:36</td>
                        </tr>

                        <tr data-status="IN_TRANSIT" data-month="2026-03">
                            <td>5</td>
                            <td><strong>AWB000005</strong></td>
                            <td>Handphone</td>
                            <td>Toko Maju</td>
                            <td>Rizky Pratama</td>
                            <td>CIDENG, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Regular</td>
                            <td>0.80 kg</td>
                            <td>Rp 60.000</td>
                            <td><span class="badge bg-primary">IN TRANSIT</span></td>
                            <td>09-03-2026 13:36</td>
                        </tr>

                        <tr data-status="OUT_FOR_DELIVERY" data-month="2026-02">
                            <td>6</td>
                            <td><strong>AWB000006</strong></td>
                            <td>Tablet</td>
                            <td>PT Angkasa</td>
                            <td>Nadia Putri</td>
                            <td>PETOJO SELATAN, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Express</td>
                            <td>1.10 kg</td>
                            <td>Rp 55.000</td>
                            <td><span class="badge bg-info text-dark">OUT FOR DELIVERY</span></td>
                            <td>27-02-2026 10:15</td>
                        </tr>

                        <tr data-status="ARRIVED_AT_BRANCH" data-month="2026-02">
                            <td>7</td>
                            <td><strong>AWB000007</strong></td>
                            <td>Printer</td>
                            <td>CV Sentosa</td>
                            <td>PT Nusantara</td>
                            <td>BENDUNGAN HILIR, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Economy</td>
                            <td>4.20 kg</td>
                            <td>Rp 85.000</td>
                            <td><span class="badge bg-dark">ARRIVED AT BRANCH</span></td>
                            <td>26-02-2026 15:20</td>
                        </tr>

                        <tr data-status="PICKED_UP" data-month="2026-02">
                            <td>8</td>
                            <td><strong>AWB000008</strong></td>
                            <td>Buku</td>
                            <td>Maya Sari</td>
                            <td>Galih Ramadhan</td>
                            <td>GAMBIR, JAKARTA PUSAT, DKI JAKARTA</td>
                            <td>Regular</td>
                            <td>2.00 kg</td>
                            <td>Rp 35.000</td>
                            <td><span class="badge bg-secondary">PICKED UP</span></td>
                            <td>25-02-2026 08:45</td>
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

<script>
    const tableBody = document.getElementById("reportTable");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    const searchInput = document.getElementById("searchInput");
    const filterStatus = document.getElementById("filterStatus");
    const filterMonth = document.getElementById("filterMonth");
    const pagination = document.getElementById("pagination");
    const tableInfo = document.getElementById("tableInfo");
    const btnExport = document.getElementById("btnExport");

    let filteredRows = [...rows];
    let currentPage = 1;
    const rowsPerPage = 10;

    function applyFilters() {
        const keyword = searchInput.value.toLowerCase();
        const status = filterStatus.value;
        const month = filterMonth.value;

        filteredRows = rows.filter(row => {
            const textMatch = row.innerText.toLowerCase().includes(keyword);
            const statusMatch = !status || row.dataset.status === status;
            const monthMatch = !month || row.dataset.month === month;
            return textMatch && statusMatch && monthMatch;
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

    searchInput.addEventListener("keyup", applyFilters);
    filterStatus.addEventListener("change", applyFilters);
    filterMonth.addEventListener("change", applyFilters);

    btnExport.addEventListener("click", function () {
        window.print();
    });

    showPage();
</script>
<?= $this->endSection(); ?>