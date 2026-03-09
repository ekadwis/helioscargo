<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="page-content active" id="page-shipment-tracking">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Shipment Tracking</h3>
        </div>

        <div class="card-body">
            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari AWB / barang / lokasi...">
            </div>
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

            <!-- Table -->
            <div style="overflow-x:auto;">
                <table class="data-table table table-hover">

                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Berat</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>

                    <tbody id="shipmentTable">

                        <?php foreach ($shipments as $index => $s) : ?>

                            <tr>

                                <td><?= $index + 1 ?></td>

                                <td>
                                    <strong><?= esc($s['awb']) ?></strong>
                                </td>

                                <td><?= esc($s['item_name']) ?></td>

                                <td><?= esc($s['qty']) ?></td>

                                <td><?= esc($s['weight_kg']) ?> kg</td>

                                <td><?= esc($s['current_location']) ?></td>

                                <td>

                                    <span class="status-badge">

                                        <?= esc($s['current_status']) ?>

                                    </span>

                                </td>

                                <td>

                                    <form action="/shipment/updateTracking" method="post" onsubmit="return confirmUpdate()">

                                        <input type="hidden" name="shipment_id" value="<?= $s['shipment_id'] ?>">

                                        <select name="status">
                                            <option value="CREATED">Created</option>
                                            <option value="PICKED_UP">Picked Up</option>
                                            <option value="IN_TRANSIT">In Transit</option>
                                            <option value="ARRIVED_AT_BRANCH">Arrived at Branch</option>
                                            <option value="OUT_FOR_DELIVERY">Out for Delivery</option>
                                            <option value="DELIVERED">Delivered</option>
                                        </select>

                                        <button type="submit">Update Status</button>

                                    </form>

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

<script>
    const tableBody = document.getElementById("shipmentTable");
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
    function confirmUpdate() {
        return confirm("Yakin ingin update status shipment ini?");
    }
</script>
<?= $this->endSection(); ?>