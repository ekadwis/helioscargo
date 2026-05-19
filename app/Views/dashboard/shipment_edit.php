<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Edit Shipment — <strong><?= $shipment['awb'] ?></strong></h3>
            <a href="/shipment" class="btn btn-light-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <form action="/shipment/update/<?= $shipment['id'] ?>" method="post">
                <?= csrf_field() ?>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pengirim</label>
                        <select name="sender_customer_id" class="form-control" required>
                            <?php foreach ($customers as $c) : ?>
                                <option value="<?= $c['id'] ?>" <?= $shipment['sender_customer_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penerima</label>
                        <select name="receiver_customer_id" class="form-control" required>
                            <?php foreach ($customers as $c) : ?>
                                <option value="<?= $c['id'] ?>" <?= $shipment['receiver_customer_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi Asal</label>
                        <select name="origin_location_id" class="form-control select2-edit" required>
                            <?php foreach ($locations as $loc) : ?>
                                <option value="<?= $loc['id'] ?>" <?= $shipment['origin_location_id'] == $loc['id'] ? 'selected' : '' ?>>
                                    <?= $loc['kelurahan'] . ', ' . $loc['kecamatan'] . ', ' . $loc['kabupaten'] . ' - ' . $loc['kodepos'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi Tujuan</label>
                        <select name="destination_location_id" class="form-control select2-edit" required>
                            <?php foreach ($locations as $loc) : ?>
                                <option value="<?= $loc['id'] ?>" <?= $shipment['destination_location_id'] == $loc['id'] ? 'selected' : '' ?>>
                                    <?= $loc['kelurahan'] . ', ' . $loc['kecamatan'] . ', ' . $loc['kabupaten'] . ' - ' . $loc['kodepos'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-control" required>
                            <?php foreach ($services as $s) : ?>
                                <option value="<?= $s['id'] ?>" <?= $shipment['service_id'] == $s['id'] ? 'selected' : '' ?>>
                                    <?= $s['name'] ?> (<?= $s['sla_days_min'] ?>-<?= $s['sla_days_max'] ?> hari)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="current_status" class="form-control" required>
                            <?php foreach (['draft','booked','picked_up','in_transit','delivered','cancelled'] as $s) : ?>
                                <option value="<?= $s ?>" <?= $shipment['current_status'] === $s ? 'selected' : '' ?>>
                                    <?= ucwords(str_replace('_', ' ', $s)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="item_name" class="form-control" value="<?= $shipment['item_name'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" class="form-control" value="<?= $shipment['qty'] ?>" min="1" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi Barang</label>
                        <textarea name="item_desc" class="form-control" rows="2"><?= $shipment['item_desc'] ?></textarea>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" class="form-control" value="<?= $shipment['weight_kg'] ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Panjang (cm)</label>
                        <input type="number" step="0.01" name="length_cm" class="form-control" value="<?= $shipment['length_cm'] ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lebar (cm)</label>
                        <input type="number" step="0.01" name="width_cm" class="form-control" value="<?= $shipment['width_cm'] ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tinggi (cm)</label>
                        <input type="number" step="0.01" name="height_cm" class="form-control" value="<?= $shipment['height_cm'] ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ongkir</label>
                        <input type="number" step="0.01" name="shipping_fee" class="form-control" value="<?= $shipment['shipping_fee'] ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Asuransi</label>
                        <input type="number" step="0.01" name="insurance_fee" class="form-control" value="<?= $shipment['insurance_fee'] ?>">
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_fragile" value="1" id="is_fragile"
                                <?= (int)$shipment['is_fragile'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_fragile">Barang Fragile</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pickup Outlet</label>
                        <select name="pickup_outlet_id" class="form-control" required>
                            <?php foreach ($outlets as $o) : ?>
                                <option value="<?= $o['id'] ?>" <?= $shipment['pickup_outlet_id'] == $o['id'] ? 'selected' : '' ?>>
                                    <?= $o['name'] ?> (<?= ucfirst($o['type']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery Outlet</label>
                        <select name="delivery_outlet_id" class="form-control" required>
                            <?php foreach ($outlets as $o) : ?>
                                <option value="<?= $o['id'] ?>" <?= $shipment['delivery_outlet_id'] == $o['id'] ? 'selected' : '' ?>>
                                    <?= $o['name'] ?> (<?= ucfirst($o['type']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Est. Delivery Date</label>
                        <input type="date" name="estimated_delivery_date" class="form-control"
                            value="<?= $shipment['estimated_delivery_date'] ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control" required>
                            <?php foreach (['unpaid' => 'Unpaid', 'paid' => 'Paid', 'cod' => 'COD'] as $val => $label) : ?>
                                <option value="<?= $val ?>" <?= $shipment['payment_status'] === $val ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">COD Amount</label>
                        <input type="number" name="cod_amount" class="form-control" value="<?= $shipment['cod_amount'] ?>">
                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Shipment</button>
                    <a href="/shipment" class="btn btn-light-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2-edit').select2({
        placeholder: 'Cari lokasi...',
        allowClear: true,
        width: '100%'
    });
});
</script>

<?= $this->endSection(); ?>