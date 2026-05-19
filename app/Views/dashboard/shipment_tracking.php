<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Shipment Tracking</h3>
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
                <input type="text" id="searchInput" class="form-control" placeholder="Cari AWB / barang / status...">
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Berat</th>
                            <th>Lokasi Terakhir</th>
                            <th>Status Shipment</th>
                            <th>Status Tracking</th>
                            <th>Keterangan</th>
                            <th>Last Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="trackingTable">
                        <?php if (!empty($shipments)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($shipments as $s) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $s['awb'] ?></strong></td>
                                    <td><?= $s['item_name'] ?></td>
                                    <td><?= $s['qty'] ?></td>
                                    <td><?= number_format((float)$s['weight_kg'], 2) ?> kg</td>
                                    <td><?= $s['current_location'] ?? '-' ?></td>
                                    <td>
                                        <?php
                                        $cs = $s['current_status'];
                                        $csClass = 'bg-secondary';
                                        if (in_array($cs, ['picked_up', 'delivered'])) $csClass = 'bg-success';
                                        elseif ($cs === 'in_transit') $csClass = 'bg-primary';
                                        elseif (in_array($cs, ['draft', 'booked'])) $csClass = 'bg-warning';
                                        elseif ($cs === 'cancelled') $csClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $csClass ?>">
                                            <?= ucwords(str_replace('_', ' ', $cs)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($s['tracking_status']) : ?>
                                            <span class="badge bg-info">
                                                <?= ucwords(str_replace('_', ' ', $s['tracking_status'])) ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?= $s['description'] ?? '-' ?></small>
                                    </td>
                                    <td>
                                        <small><?= $s['last_update'] ? date('d-m-Y H:i', strtotime($s['last_update'])) : '-' ?></small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateTrackingModal"
                                            data-id="<?= $s['shipment_id'] ?>"
                                            data-awb="<?= $s['awb'] ?>">
                                            Update
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data tracking.</td>
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

<!-- MODAL UPDATE TRACKING -->
<div class="modal fade" id="updateTrackingModal" tabindex="-1" aria-labelledby="updateTrackingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/shipment/updateTracking" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="shipment_id" id="modal_shipment_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="updateTrackingModalLabel">
                        Update Tracking — <span id="modal_awb" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status Tracking</label>
                        <select name="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="picked_up">Picked Up — Paket diambil dari pengirim</option>
                            <option value="manifested">Manifested — Masuk manifest pengiriman</option>
                            <option value="in_transit">In Transit — Dalam perjalanan</option>
                            <option value="arrived_at_hub">Arrived at Hub — Tiba di gudang/hub</option>
                            <option value="out_for_delivery">Out for Delivery — Dibawa kurir</option>
                            <option value="delivered">Delivered — Terkirim ke penerima</option>
                            <option value="failed_delivery">Failed Delivery — Gagal terkirim</option>
                            <option value="returned">Returned — Dikembalikan ke pengirim</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan <small class="text-muted">(opsional)</small></label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Contoh: Paket tiba di DC Cakung pukul 21.00"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {

        // Isi modal dengan data shipment yang dipilih
        $('#updateTrackingModal').on('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            console.log('btn:', btn);
            console.log('data-id:', btn ? btn.getAttribute('data-id') : 'NULL');
            $('#modal_shipment_id').val(btn.getAttribute('data-id'));
            $('#modal_awb').text(btn.getAttribute('data-awb'));
        });

        // TABLE + PAGINATION
        const tableBody = document.getElementById("trackingTable");
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

<?= $this->endSection(); ?>