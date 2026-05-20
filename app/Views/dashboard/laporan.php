<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Shipment</div>
                        <h3 class="mb-0"><?= $totalShipment ?></h3>
                    </div>
                    <div class="fs-2 text-primary"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Pendapatan</div>
                        <h3 class="mb-0">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h3>
                    </div>
                    <div class="fs-2 text-success"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Delivered</div>
                        <h3 class="mb-0"><?= $totalDelivered ?></h3>
                    </div>
                    <div class="fs-2 text-warning"><i class="bi bi-truck"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Berat</div>
                        <h3 class="mb-0"><?= number_format($totalBerat, 2) ?> kg</h3>
                    </div>
                    <div class="fs-2 text-info"><i class="bi bi-speedometer2"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-3">Laporan Pengiriman</h3>

            <!-- Filter Form -->
            <form method="get" action="/laporan" id="filterForm">
                <div class="d-flex gap-2 flex-wrap align-items-end">

                    <div>
                        <label class="form-label mb-1 small">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="<?= $startDate ?>" style="width:160px;">
                    </div>

                    <div>
                        <label class="form-label mb-1 small">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="<?= $endDate ?>" style="width:160px;">
                    </div>

                    <div>
                        <label class="form-label mb-1 small">Status</label>
                        <select name="status" class="form-select form-select-sm" style="width:160px;">
                            <option value="">Semua Status</option>
                            <?php foreach (['draft', 'booked', 'picked_up', 'in_transit', 'delivered', 'cancelled'] as $s) : ?>
                                <option value="<?= $s ?>" <?= $filterStatus === $s ? 'selected' : '' ?>>
                                    <?= ucwords(str_replace('_', ' ', $s)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="form-label mb-1 small">Outlet</label>
                        <select name="outlet_id" class="form-select form-select-sm" style="width:180px;">
                            <option value="">Semua Outlet</option>
                            <?php foreach ($outlets as $o) : ?>
                                <option value="<?= $o['id'] ?>" <?= $filterOutlet == $o['id'] ? 'selected' : '' ?>>
                                    <?= $o['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="/laporan" class="btn btn-light-secondary btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        <a href="/laporan/export?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&status=<?= $filterStatus ?>&outlet_id=<?= $filterOutlet ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </a>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-body">

            <div class="mb-3 d-flex justify-content-between align-items-center">
                <input type="text" id="searchInput" class="form-control" style="max-width:300px;"
                    placeholder="Cari AWB / barang / pengirim...">
                <small class="text-muted">
                    Periode: <strong><?= date('d M Y', strtotime($startDate)) ?></strong>
                    s/d <strong><?= date('d M Y', strtotime($endDate)) ?></strong>
                </small>
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Tujuan</th>
                            <th>Service</th>
                            <th>Outlet</th>
                            <th>Berat</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="reportTable">
                        <?php if (!empty($shipments)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($shipments as $s) : ?>
                                <?php
                                $cs = $s['current_status'];
                                $csClass = 'bg-secondary';
                                if (in_array($cs, ['picked_up', 'delivered'])) $csClass = 'bg-success';
                                elseif ($cs === 'in_transit') $csClass = 'bg-primary';
                                elseif (in_array($cs, ['draft', 'booked'])) $csClass = 'bg-warning';
                                elseif ($cs === 'cancelled') $csClass = 'bg-danger';

                                $payClass = ['unpaid' => 'bg-danger', 'paid' => 'bg-success', 'cod' => 'bg-warning'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $s['awb'] ?></strong></td>
                                    <td><?= $s['item_name'] ?></td>
                                    <td><?= $s['sender_name'] ?? '-' ?></td>
                                    <td><?= $s['receiver_name'] ?? '-' ?></td>
                                    <td>
                                        <small>
                                            <?= $s['dest_kelurahan'] ?>,
                                            <?= $s['dest_kecamatan'] ?>,
                                            <?= $s['dest_kabupaten'] ?>
                                        </small>
                                    </td>
                                    <td><?= $s['service_name'] ?? '-' ?></td>
                                    <td><?= $s['outlet_name'] ?? '-' ?></td>
                                    <td><?= number_format((float)$s['weight_kg'], 2) ?> kg</td>
                                    <td>Rp <?= number_format((float)$s['total_amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= $csClass ?>">
                                            <?= ucwords(str_replace('_', ' ', $cs)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $payClass[$s['payment_status']] ?? 'bg-secondary' ?>">
                                            <?= strtoupper($s['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td><small><?= date('d-m-Y H:i', strtotime($s['created_at'])) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="13" class="text-center text-muted py-4">
                                    Tidak ada data untuk periode dan filter yang dipilih.
                                </td>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        const tableBody = document.getElementById("reportTable");
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
    });
</script>
<?= $this->endSection() ?>