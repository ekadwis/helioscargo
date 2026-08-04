<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manifest</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addManifestModal">
                Buat Manifest
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
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nomor manifest / driver...">
            </div>

            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Manifest</th>
                            <th>Asal</th>
                            <th>Tujuan Hub</th>
                            <th>Driver</th>
                            <th>Kendaraan</th>
                            <th>Total Paket</th>
                            <th>Total Berat</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="manifestTable">
                        <?php if (!empty($manifests)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($manifests as $m) : ?>
                                <?php
                                $originName = '-';
                                $destName   = '-';
                                foreach ($outlets as $o) {
                                    if ($o['id'] == $m['origin_outlet_id'])   $originName = $o['name'];
                                    if ($o['id'] == $m['destination_hub_id']) $destName   = $o['name'];
                                }
                                $statusClass = [
                                    'draft'     => 'bg-warning',
                                    'in_transit'=> 'bg-primary',
                                    'arrived'   => 'bg-success',
                                    'processed' => 'bg-secondary',
                                ][$m['status']] ?? 'bg-secondary';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $m['manifest_number'] ?></strong></td>
                                    <td><?= $originName ?></td>
                                    <td><?= $destName ?></td>
                                    <td><?= $m['driver_name'] ?? '-' ?></td>
                                    <td><?= $m['vehicle_number'] ?? '-' ?></td>
                                    <td><?= $m['total_shipments'] ?> paket</td>
                                    <td><?= number_format((float)$m['total_weight'], 2) ?> kg</td>
                                    <td><span class="badge <?= $statusClass ?>"><?= ucwords(str_replace('_', ' ', $m['status'])) ?></span></td>
                                    <td><?= date('d-m-Y', strtotime($m['created_at'])) ?></td>
                                    <td>
                                        <a href="/manifest/detail/<?= $m['id'] ?>" class="btn btn-sm btn-info">Detail</a>

                                        <!-- Tampilkan QR -->
                                        <button type="button" class="btn btn-sm btn-dark btn-show-qr"
                                            data-number="<?= $m['manifest_number'] ?>"
                                            data-origin="<?= htmlspecialchars($originName) ?>"
                                            data-dest="<?= htmlspecialchars($destName) ?>"
                                            data-driver="<?= htmlspecialchars($m['driver_name'] ?? '-') ?>"
                                            data-vehicle="<?= htmlspecialchars($m['vehicle_number'] ?? '-') ?>"
                                            data-total="<?= $m['total_shipments'] ?>">
                                            <i class="bi bi-qr-code"></i> QR
                                        </button>

                                        <!-- Update Status -->
                                        <button type="button" class="btn btn-sm btn-warning btn-update-status"
                                            data-id="<?= $m['id'] ?>"
                                            data-status="<?= $m['status'] ?>"
                                            data-number="<?= $m['manifest_number'] ?>">
                                            Update Status
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center">Belum ada manifest.</td>
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

<!-- MODAL BUAT MANIFEST -->
<div class="modal fade" id="addManifestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/manifest/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Buat Manifest Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Outlet Asal</label>
                            <select name="origin_outlet_id" id="origin_outlet_id" class="form-control" required>
                                <option value="">-- Pilih Outlet Asal --</option>
                                <?php foreach ($outlets as $o) : ?>
                                    <option value="<?= $o['id'] ?>"><?= $o['name'] ?> (<?= ucfirst($o['type']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tujuan Hub / Gudang</label>
                            <select name="destination_hub_id" class="form-control" required>
                                <option value="">-- Pilih Hub Tujuan --</option>
                                <?php foreach ($outlets as $o) : ?>
                                    <?php if (in_array($o['type'], ['hub', 'warehouse', 'sorting_center'])) : ?>
                                        <option value="<?= $o['id'] ?>"><?= $o['name'] ?> (<?= ucfirst($o['type']) ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Driver</label>
                            <input type="text" name="driver_name" class="form-control" placeholder="Contoh: Budi Santoso">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Kendaraan</label>
                            <input type="text" name="vehicle_number" class="form-control" placeholder="Contoh: B 1234 XYZ">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Pilih Shipment <small class="text-muted">(status draft/booked)</small></label>
                            <div id="shipmentLoadingInfo" class="text-muted small mb-2">Pilih outlet asal dulu untuk memuat daftar shipment.</div>
                            <div style="max-height:300px; overflow-y:auto; border:1px solid #dee2e6; border-radius:6px; padding:10px;">
                                <table class="table table-sm mb-0" id="shipmentPickTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;">
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th>AWB</th>
                                            <th>Barang</th>
                                            <th>Berat</th>
                                            <th>Status</th>
                                            <th>Pengirim</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shipmentPickBody">
                                        <tr><td colspan="6" class="text-center text-muted">Pilih outlet asal dulu.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <small>Dipilih: <strong id="selectedCount">0</strong> shipment |
                                Total berat: <strong id="selectedWeight">0</strong> kg</small>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Manifest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL QR CODE MANIFEST -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code Manifest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h6 class="text-primary fw-bold mb-1" id="qrModalNumber"></h6>
                <div id="qrModalCanvas" class="d-inline-block p-3 bg-white rounded border my-3"></div>
                <div class="text-muted small mb-1">
                    <i class="bi bi-geo-alt me-1"></i><span id="qrModalRoute"></span>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-person-badge me-1"></i><span id="qrModalDriver"></span>
                    &nbsp;|&nbsp;
                    <i class="bi bi-truck me-1"></i><span id="qrModalVehicle"></span>
                    &nbsp;|&nbsp;
                    <i class="bi bi-box-seam me-1"></i><span id="qrModalTotal"></span> paket
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnPrintQR">
                    <i class="bi bi-printer me-1"></i>Print QR
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPDATE STATUS -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStatusForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Update Status — <span id="modal_manifest_number" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Status Baru</label>
                    <select name="status" id="modal_new_status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="in_transit">In Transit — Kendaraan berangkat</option>
                        <option value="arrived">Arrived — Tiba di hub tujuan</option>
                        <option value="processed">Processed — Selesai diproses</option>
                    </select>
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
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
$(document).ready(function() {

    // Inisialisasi tabel dan pagination
    const tableBody   = document.getElementById("manifestTable");
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

    // Muat data shipment berdasarkan outlet via AJAX
    $('#origin_outlet_id').on('change', function() {
        const outletId = $(this).val();
        const tbody    = $('#shipmentPickBody');

        if (!outletId) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted">Pilih outlet asal dulu.</td></tr>');
            return;
        }

        tbody.html('<tr><td colspan="6" class="text-center">Memuat...</td></tr>');

        $.ajax({
            url: '/manifest/getShipments',
            method: 'GET',
            data: { outlet_id: outletId },
            success: function(res) {
                if (res.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center text-muted">Tidak ada shipment tersedia.</td></tr>');
                    return;
                }

                let html = '';
                res.forEach(function(s) {
                    html += `
                        <tr>
                            <td><input type="checkbox" name="shipment_ids[]" value="${s.id}" class="shipment-check" data-weight="${s.weight_kg}"></td>
                            <td><strong>${s.awb}</strong></td>
                            <td>${s.item_name}</td>
                            <td>${parseFloat(s.weight_kg).toFixed(2)} kg</td>
                            <td><span class="badge bg-warning">${s.current_status}</span></td>
                            <td>${s.sender_name ?? '-'}</td>
                        </tr>`;
                });
                tbody.html(html);
                updateSelectedInfo();
            },
            error: function() {
                tbody.html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>');
            }
        });
    });

    // Check all
    $(document).on('change', '#checkAll', function() {
        $('.shipment-check').prop('checked', $(this).is(':checked'));
        updateSelectedInfo();
    });

    $(document).on('change', '.shipment-check', function() {
        updateSelectedInfo();
    });

    function updateSelectedInfo() {
        const checked = $('.shipment-check:checked');
        let totalWeight = 0;
        checked.each(function() {
            totalWeight += parseFloat($(this).data('weight')) || 0;
        });
        $('#selectedCount').text(checked.length);
        $('#selectedWeight').text(totalWeight.toFixed(2));
    }

    // Handler untuk tampilkan QR manifest
    let qrInstance = null;
    $(document).on('click', '.btn-show-qr', function() {
        const number  = $(this).data('number');
        const origin  = $(this).data('origin');
        const dest    = $(this).data('dest');
        const driver  = $(this).data('driver');
        const vehicle = $(this).data('vehicle');
        const total   = $(this).data('total');

        $('#qrModalNumber').text(number);
        $('#qrModalRoute').text(origin + ' → ' + dest);
        $('#qrModalDriver').text(driver);
        $('#qrModalVehicle').text(vehicle);
        $('#qrModalTotal').text(total);

        // Reset canvas
        const canvas = document.getElementById('qrModalCanvas');
        canvas.innerHTML = '';

        if (qrInstance) { qrInstance.clear(); }
        qrInstance = new QRCode(canvas, {
            text: number,
            width: 220,
            height: 220,
            colorDark: "#1e3a5f",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        $('#btnPrintQR').off('click').on('click', function() {
            const img = canvas.querySelector('img');
            if (!img) { alert('QR belum siap, tunggu sebentar.'); return; }
            const win = window.open('', '_blank');
            win.document.write(`
                <html>
                <head><title>QR - ${number}</title></head>
                <body style="text-align:center;font-family:Arial,sans-serif;padding:40px;">
                    <h2 style="margin-bottom:5px;">MANIFEST</h2>
                    <h1 style="margin:0;color:#1e3a5f;">${number}</h1>
                    <div style="margin:20px auto;"><img src="${img.src}" style="width:220px;height:220px;"></div>
                    <p style="color:#666;">Scan QR ini di Scan Center untuk proses manifest</p>
                    <hr>
                    <small>${origin} &rarr; ${dest}</small><br>
                    <small>Driver: ${driver} | Kendaraan: ${vehicle} | ${total} paket</small>
                </body>
                </html>
            `);
            win.document.close();
            win.onload = function() { win.print(); };
        });

        $('#qrModal').modal('show');
    });

    // Handler untuk memperbarui status manifest
    $(document).on('click', '.btn-update-status', function() {
        const id     = $(this).data('id');
        const status = $(this).data('status');
        const number = $(this).data('number');

        $('#modal_manifest_number').text(number);
        $('#modal_new_status').val(status);
        $('#updateStatusForm').attr('action', '/manifest/updateStatus/' + id);
        $('#updateStatusModal').modal('show');
    });

});
</script>
<?= $this->endSection() ?>