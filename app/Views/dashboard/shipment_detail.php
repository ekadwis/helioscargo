<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">

    <?php
    // Helper lookup
    $findById = function ($arr, $id, $field) {
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

                        <div class="col-12">
                            <hr>
                        </div>

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

                        <div class="col-12">
                            <hr>
                        </div>

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

                        <div class="col-12">
                            <hr>
                        </div>

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
                    <a href="/shipment/resi/<?= $shipment['id'] ?>" class="btn btn-success" target="_blank">
                        <i class="bi bi-printer me-1"></i> Cetak Resi
                    </a>
                    <a href="/shipment" class="btn btn-light-secondary">Kembali</a>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="col-md-4">
            <style>
                .sc-timeline {
                    position: relative;
                }

                .sc-timeline-row {
                    display: flex;
                    gap: 0.75rem;
                }

                .sc-timeline-col-line {
                    width: 14px;
                    flex-shrink: 0;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .sc-timeline-dot {
                    width: 11px;
                    height: 11px;
                    border-radius: 50%;
                    background: #cbd5e1;
                    margin-top: 4px;
                    flex-shrink: 0;
                }

                .sc-timeline-row:first-child .sc-timeline-dot {
                    background: #16a34a;
                    width: 13px;
                    height: 13px;
                }

                .sc-timeline-connector {
                    width: 2px;
                    flex: 1;
                    background: #e2e8f0;
                    margin-top: 2px;
                }

                .sc-timeline-row:last-child .sc-timeline-connector {
                    display: none;
                }

                .sc-timeline-col-content {
                    padding-bottom: 1.4rem;
                    flex: 1;
                }

                .sc-timeline-status {
                    font-size: 0.88rem;
                    font-weight: 600;
                    color: #cbd5e1;
                    margin-bottom: 1px;
                }

                .sc-timeline-desc {
                    font-size: 0.8rem;
                    color: #cbd5e1;
                    margin-bottom: 1px;
                }

                .sc-timeline-date {
                    font-size: 0.75rem;
                    color: #cbd5e1;
                }

                .sc-timeline-row:first-child .sc-timeline-status {
                    color: #16a34a;
                }

                .sc-timeline-row:first-child .sc-timeline-desc {
                    color: #64748b;
                }

                .sc-timeline-row:first-child .sc-timeline-date {
                    color: #94a3b8;
                }
            </style>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tracking History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($trackings)) : ?>
                        <div class="sc-timeline">
                            <?php foreach (array_reverse($trackings) as $track) : ?>
                                <div class="sc-timeline-row">
                                    <div class="sc-timeline-col-line">
                                        <div class="sc-timeline-dot"></div>
                                        <div class="sc-timeline-connector"></div>
                                    </div>
                                    <div class="sc-timeline-col-content">
                                        <p class="sc-timeline-status mb-0"><?= str_replace('_', ' ', $track['status']) ?></p>
                                        <p class="sc-timeline-desc mb-0"><?= $track['description'] ?></p>
                                        <p class="sc-timeline-date mb-0"><?= date('d-m-Y H:i', strtotime($track['created_at'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="text-muted text-center">Belum ada tracking.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>