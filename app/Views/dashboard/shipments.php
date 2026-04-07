<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Shipment</h3>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShipmentModal">
                Tambah Shipment
            </button>
        </div>

        <div class="card-body">

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

            <div class="mb-3" style="max-width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari AWB / barang / status">
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
                            <th>Dimensi</th>
                            <th>Fragile</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="shipmentTable">
                        <?php if (!empty($shipments)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($shipments as $shipment) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($shipment['awb']) ?></strong></td>
                                    <td><?= esc($shipment['item_name']) ?></td>
                                    <td><?= esc($shipment['qty']) ?></td>
                                    <td><?= number_format((float)$shipment['weight_kg'], 2) ?> kg</td>
                                    <td>
                                        <?= number_format((float)$shipment['length_cm'], 0) ?>x
                                        <?= number_format((float)$shipment['width_cm'], 0) ?>x
                                        <?= number_format((float)$shipment['height_cm'], 0) ?>
                                    </td>
                                    <td>
                                        <?php if ((int)$shipment['is_fragile'] === 1) : ?>
                                            <span class="badge bg-danger">Fragile</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>Rp <?= number_format((float)$shipment['total_amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $status = $shipment['current_status'];
                                        $badgeClass = 'bg-secondary';

                                        if ($status === 'DELIVERED') {
                                            $badgeClass = 'bg-success';
                                        } elseif ($status === 'IN_TRANSIT') {
                                            $badgeClass = 'bg-primary';
                                        } elseif ($status === 'CREATED') {
                                            $badgeClass = 'bg-warning';
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= esc(str_replace('_', ' ', $status)) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($shipment['created_at'])) ?></td>
                                    <td>
                                        <a href="/shipment/detail/<?= $shipment['id'] ?>" class="btn btn-sm btn-info" id="detail-<?= $shipment['id'] ?>">
                                            Detail
                                        </a>

                                        <a href="/shipment/edit/<?= $shipment['id'] ?>" class="btn btn-sm btn-warning" id="edit-<?= $shipment['id'] ?>">
                                            Edit
                                        </a>

                                        <form action="/shipment/delete/<?= $shipment['id'] ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger" id="delete-<?= $shipment['id'] ?>" onclick="return confirm('Yakin ingin menghapus shipment ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center">Data shipment belum ada.</td>
                            </tr>
                        <?php endif; ?>
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

<!-- MODAL TAMBAH SHIPMENT -->
<div class="modal fade" id="addShipmentModal" tabindex="-1" aria-labelledby="addShipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="/shipment/store" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="addShipmentModalLabel">Tambah Shipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pengirim</label>
                            <select name="sender_customer_id" class="form-control" required>
                                <option value="">-- Pilih Pengirim --</option>
                                <?php foreach ($customers as $customer) : ?>
                                    <option value="<?= $customer['id'] ?>">
                                        <?= esc($customer['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penerima</label>
                            <select name="receiver_customer_id" class="form-control" required>
                                <option value="">-- Pilih Penerima --</option>
                                <?php foreach ($customers as $customer) : ?>
                                    <option value="<?= $customer['id'] ?>">
                                        <?= esc($customer['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Asal</label>
                            <input type="text" class="form-control location-search" list="originLocationList" placeholder="Cari lokasi asal..." autocomplete="off" required>
                            <input type="hidden" name="origin_location_id" id="origin_location_id" required>

                            <datalist id="originLocationList">
                                <?php foreach ($locations as $location) : ?>
                                    <option data-id="<?= $location['id'] ?>" value="<?= esc($location['kelurahan'] . ', ' . $location['kecamatan'] . ', ' . $location['kabupaten'] . ', ' . $location['provinsi'] . ' - ' . $location['kodepos']) ?>">
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Tujuan</label>
                            <input type="text" class="form-control location-search" list="destinationLocationList" placeholder="Cari lokasi tujuan..." autocomplete="off" required>
                            <input type="hidden" name="destination_location_id" id="destination_location_id" required>

                            <datalist id="destinationLocationList">
                                <?php foreach ($locations as $location) : ?>
                                    <option data-id="<?= $location['id'] ?>" value="<?= esc($location['kelurahan'] . ', ' . $location['kecamatan'] . ', ' . $location['kabupaten'] . ', ' . $location['provinsi'] . ' - ' . $location['kodepos']) ?>">
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service</label>
                            <select name="service_id" id="service_id" class="form-control" required>
                                <option value="">-- Pilih Service --</option>
                                <?php foreach ($services as $service) : ?>
                                    <option value="<?= $service['id'] ?>" data-name="<?= strtolower($service['name']) ?>">

                                        <?= esc($service['name']) ?>
                                        (<?= esc($service['sla_days_min']) ?>-<?= esc($service['sla_days_max']) ?> hari)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Deskripsi Barang</label>
                            <textarea name="item_desc" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" step="0.01" name="weight_kg" id="weight_kg" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai Barang</label>
                            <input type="number" step="0.01" name="declared_value" class="form-control" value="0">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Panjang (cm)</label>
                            <input type="number" step="0.01" name="length_cm" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Lebar (cm)</label>
                            <input type="number" step="0.01" name="width_cm" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tinggi (cm)</label>
                            <input type="number" step="0.01" name="height_cm" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ongkir</label>
                            <input type="number" step="0.01" name="shipping_fee" id="shipping_fee" class="form-control" value="0" readonly required>

                            <small id="ongkirPreview" class="text-muted"></small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Asuransi</label>
                            <input type="number" step="0.01" name="insurance_fee" class="form-control" value="0" required>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_fragile" value="1" id="is_fragile">
                                <label class="form-check-label" for="is_fragile">
                                    Barang Fragile
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Shipment</button>
                </div>
            </form>
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

        const visibleRows = filteredRows.filter(row => !row.querySelector("td[colspan]"));
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        visibleRows.forEach(row => row.style.display = "none");
        visibleRows.slice(start, end).forEach(row => {
            row.style.display = "";
        });

        const emptyRow = tableBody.querySelector("td[colspan]");
        if (emptyRow) {
            const emptyTr = emptyRow.closest("tr");
            if (visibleRows.length === 0) {
                emptyTr.style.display = "";
            } else {
                emptyTr.style.display = "none";
            }
        }

        tableInfo.innerText =
            `Menampilkan ${visibleRows.length === 0 ? 0 : start + 1} - ${Math.min(end, visibleRows.length)} dari ${visibleRows.length} data`;

        createPagination(visibleRows.length);
    }

    $('#ongkirPreview').html('Menghitung...');

    function hitungOngkir() {

        let service = $('#service_id option:selected').data('name');
        let berat = $('#weight_kg').val();

        console.log(service, berat);

        if (!service || !berat) return;

        $.ajax({
    url: '/cek_ongkir',
    method: 'POST',
    data: {
        service: service,
        berat: berat
    },
    success: function(res) {

        console.log(res); // DEBUG

        $('#shipping_fee').val(res.total);

        $('#ongkirPreview').html(`
            Harga/kg : Rp ${res.harga_per_kg.toLocaleString()} <br>
            Total    : <b>Rp ${res.total.toLocaleString()}</b>
        `);
    },
    error: function(xhr) {
        console.log(xhr.responseText);
        alert('Error ongkir, cek console');
    }
});
    }

    $('#service_id, #weight_kg').on('change keyup', function() {
        hitungOngkir();
    });

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

        filteredRows = rows.filter(row =>
            row.innerText.toLowerCase().includes(keyword)
        );

        currentPage = 1;
        showPage();
    });

    showPage();

    function bindLocationInput(textInputSelector, datalistSelector, hiddenInputSelector) {
        const textInput = document.querySelector(textInputSelector);
        const datalist = document.querySelector(datalistSelector);
        const hiddenInput = document.querySelector(hiddenInputSelector);

        textInput.addEventListener('input', function() {
            const options = datalist.querySelectorAll('option');
            let found = false;

            options.forEach(option => {
                if (option.value === this.value) {
                    hiddenInput.value = option.dataset.id;
                    found = true;
                }
            });

            if (!found) {
                hiddenInput.value = '';
            }
        });
    }

    bindLocationInput('input[list="originLocationList"]', '#originLocationList', '#origin_location_id');
    bindLocationInput('input[list="destinationLocationList"]', '#destinationLocationList', '#destination_location_id');
</script>

<?= $this->endSection(); ?>