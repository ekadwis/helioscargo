<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="page-content active" id="page-invoice">

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Buat Invoice Baru</h3>
        </div>

        <div class="card-body">
            <form id="formInvoice" onsubmit="return submitDummyInvoice(event)">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pelanggan</label>
                        <select class="form-control" name="customer_name" required>
                            <option value="">Pilih Pelanggan</option>
                            <option value="PT Maju Jaya">PT Maju Jaya</option>
                            <option value="CV Berkah Abadi">CV Berkah Abadi</option>
                            <option value="Toko Sejahtera">Toko Sejahtera</option>
                            <option value="PT Global Indo">PT Global Indo</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Invoice</label>
                        <input type="date" class="form-control" name="invoice_date" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Invoice</label>
                        <input type="text" class="form-control" name="invoice_number" value="INV-2026-004" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" class="form-control" name="total_amount" placeholder="Masukkan total invoice" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="invoice_status" required>
                            <option value="BELUM_LUNAS">Belum Lunas</option>
                            <option value="LUNAS">Lunas</option>
                            <option value="JATUH_TEMPO">Jatuh Tempo</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Buat Invoice
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="card-title mb-0">Daftar Invoice</h3>

            <div class="d-flex gap-2 flex-wrap">
                <select class="form-select form-select-sm" style="width: 180px;" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="LUNAS">Lunas</option>
                    <option value="BELUM_LUNAS">Belum Lunas</option>
                    <option value="JATUH_TEMPO">Jatuh Tempo</option>
                </select>
            </div>
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

            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari no invoice / pelanggan / status...">
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="invoiceTable">
                        <tr data-status="LUNAS">
                            <td>1</td>
                            <td><strong>INV-2026-001</strong></td>
                            <td>PT Maju Jaya</td>
                            <td>09-03-2026</td>
                            <td>09-04-2026</td>
                            <td><strong>Rp 15.500.000</strong></td>
                            <td><span class="badge bg-success">LUNAS</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" id="detail-invoice-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" id="print-invoice-1" onclick="window.print()">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="BELUM_LUNAS">
                            <td>2</td>
                            <td><strong>INV-2026-002</strong></td>
                            <td>CV Berkah Abadi</td>
                            <td>08-03-2026</td>
                            <td>08-04-2026</td>
                            <td><strong>Rp 8.200.000</strong></td>
                            <td><span class="badge bg-warning text-dark">BELUM LUNAS</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" id="detail-invoice-2">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" id="print-invoice-2" onclick="window.print()">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="JATUH_TEMPO">
                            <td>3</td>
                            <td><strong>INV-2026-003</strong></td>
                            <td>Toko Sejahtera</td>
                            <td>01-03-2026</td>
                            <td>05-03-2026</td>
                            <td><strong>Rp 3.750.000</strong></td>
                            <td><span class="badge bg-danger">JATUH TEMPO</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" id="detail-invoice-3">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" id="print-invoice-3" onclick="window.print()">
                                    <i class="bi bi-printer"></i>
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

<script>
    const tableBody = document.getElementById("invoiceTable");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    const searchInput = document.getElementById("searchInput");
    const filterStatus = document.getElementById("filterStatus");
    const pagination = document.getElementById("pagination");
    const tableInfo = document.getElementById("tableInfo");

    let filteredRows = [...rows];
    let currentPage = 1;
    const rowsPerPage = 10;

    function applyFilters() {
        const keyword = searchInput.value.toLowerCase();
        const status = filterStatus.value;

        filteredRows = rows.filter(row => {
            const textMatch = row.innerText.toLowerCase().includes(keyword);
            const statusMatch = !status || row.dataset.status === status;
            return textMatch && statusMatch;
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

    function submitDummyInvoice(event) {
        event.preventDefault();

        const form = document.getElementById('formInvoice');
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersimpan!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
            form.reset();
        }, 1500);

        return false;
    }

    searchInput.addEventListener("keyup", applyFilters);
    filterStatus.addEventListener("change", applyFilters);

    showPage();
</script>
<?= $this->endSection(); ?>