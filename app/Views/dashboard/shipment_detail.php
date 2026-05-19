<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">

    <?php
    // Helper lookup
    $findById = function($arr, $id, $field) {
        foreach ($arr as $item) {
            if ($item['id'] == $id) return $item[$field] ?? '-';
        }
        return '-';
    };
    ?>

    <div class="row">
        <!-- Info Shipment -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Detail Shipment — <strong><?= $shipment['awb'] ?></strong></h3>
                    <?php
                    $status = $shipment['current_status'];
                    $badgeClass = 'bg-secondary';
                    if (in_array($status, ['picked_up', 'delivered'])) $badgeClass = 'bg-success';
                    elseif ($status === 'in_transit') $badgeClass = 'bg-primary';
                    elseif (in_array($status, ['draft', 'booked'])) $badgeClass = 'bg-warning';
                    elseif ($status === 'cancelled') $badgeClass = 'bg-danger';
                    ?>
                    <span class="badge <?= $badgeClass ?> fs-6"><?= str_replace('_', ' ', $status) ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Pengirim</h6>
                            <p class="mb-1"><strong><?= $findById($customers, $shipment['sender_customer_id'], 'name') ?></strong></p>
                            <p class="text-muted small mb-3"><?= $findById($locations, $shipment['origin_location_id'], 'kelurahan') ?>, <?= $findById($locations, $shipment['origin_location_id'], 'kecamatan') ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Penerima</h6>
                            <p class="mb-1"><strong><?= $findById($customers, $shipment['receiver_customer_id'], 'name') ?></strong></p>
                            <p class="text-muted small mb-3"><?= $findById($locations, $shipment['destination_location_id'], 'kelurahan') ?>, <?= $findById($locations, $shipment['destination_location_id'], 'kecamatan') ?></p>
                        </div>

                        <div class="col-12"><hr></div>

                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Barang</small>
                            <p class="mb-0"><strong><?= $shipment['item_name'] ?></strong></p>
                            <p class="text-muted small"><?= $shipment['item_desc'] ?: '-' ?></p>
                        </div>
                        <div class="col-md-2 mb-3">
                            <small class="text-muted">Qty</small>
                            <p class="mb-0"><strong><?= $shipment['qty'] ?></strong></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">Berat</small>
                            <p class="mb-0"><strong><?= number_format($shipment['weight_kg'], 2) ?> kg</strong></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <small class="text-muted">Dimensi (cm)</small>
                            <p class="mb-0"><strong><?= (int)$shipment['length_cm'] ?>×<?= (int)$shipment['width_cm'] ?>×<?= (int)$shipment['height_cm'] ?></strong></p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Service</small>
                            <p class="mb-0"><strong><?= $findById($services, $shipment['service_id'], 'name') ?></strong></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Fragile</small>
                            <p class="mb-0">
                                <?php if ((int)$shipment['is_fragile']) : ?>
                                    <span class="badge bg-danger">Fragile</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Est. Tiba</small>
                            <p class="mb-0"><strong><?= $shipment['estimated_delivery_date'] ? date('d-m-Y', strtotime($shipment['estimated_delivery_date'])) : '-' ?></strong></p>
                        </div>

                        <div class="col-12"><hr></div>

                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Pickup Outlet</small>
                            <p class="mb-0"><strong><?= $findById($outlets, $shipment['pickup_outlet_id'], 'name') ?></strong></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Delivery Outlet</small>
                            <p class="mb-0"><strong><?= $findById($outlets, $shipment['delivery_outlet_id'], 'name') ?></strong></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Current Outlet</small>
                            <p class="mb-0"><strong><?= $findById($outlets, $shipment['current_outlet_id'], 'name') ?></strong></p>
                        </div>

                        <div class="col-12"><hr></div>

                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Ongkir</small>
                            <p class="mb-0"><strong>Rp <?= number_format($shipment['shipping_fee'], 0, ',', '.') ?></strong></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Asuransi</small>
                            <p class="mb-0"><strong>Rp <?= number_format($shipment['insurance_fee'], 0, ',', '.') ?></strong></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Total</small>
                            <p class="mb-0 text-success"><strong>Rp <?= number_format($shipment['total_amount'], 0, ',', '.') ?></strong></p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Payment Status</small>
                            <p class="mb-0">
                                <?php
                                $payClass = ['unpaid' => 'bg-danger', 'paid' => 'bg-success', 'cod' => 'bg-warning'];
                                ?>
                                <span class="badge <?= $payClass[$shipment['payment_status']] ?? 'bg-secondary' ?>">
                                    <?= strtoupper($shipment['payment_status']) ?>
                                </span>
                            </p>
                        </div>
                        <?php if ($shipment['payment_status'] === 'cod') : ?>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">COD Amount</small>
                            <p class="mb-0"><strong>Rp <?= number_format($shipment['cod_amount'], 0, ',', '.') ?></strong></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="/shipment/edit/<?= $shipment['id'] ?>" class="btn btn-warning">Edit Shipment</a>
                    <a href="/shipment" class="btn btn-light-secondary">Kembali</a>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tracking History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($trackings)) : ?>
                        <ul class="list-unstyled">
                            <?php foreach (array_reverse($trackings) as $track) : ?>
                                <li class="d-flex gap-3 mb-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <span class="badge bg-primary rounded-circle p-2">●</span>
                                    </div>
                                    <div>
                                        <p class="mb-0"><strong><?= str_replace('_', ' ', $track['status']) ?></strong></p>
                                        <p class="mb-0 text-muted small"><?= $track['description'] ?></p>
                                        <p class="mb-0 text-muted small"><?= date('d-m-Y H:i', strtotime($track['created_at'])) ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-muted text-center">Belum ada tracking.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>