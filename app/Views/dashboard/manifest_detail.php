<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <?php
    $findById = function($arr, $id, $field) {
        foreach ($arr as $item) {
            if ($item['id'] == $id) return $item[$field] ?? '-';
        }
        return '-';
    };

    $statusClass = [
        'draft'      => 'bg-warning',
        'in_transit' => 'bg-primary',
        'arrived'    => 'bg-success',
        'processed'  => 'bg-secondary',
    ][$manifest['status']] ?? 'bg-secondary';
    ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                Detail Manifest — <strong><?= $manifest['manifest_number'] ?></strong>
            </h3>
            <span class="badge <?= $statusClass ?> fs-6">
                <?= ucwords(str_replace('_', ' ', $manifest['status'])) ?>
            </span>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <small class="text-muted">Outlet Asal</small>
                    <p class="mb-0"><strong><?= $findById($outlets, $manifest['origin_outlet_id'], 'name') ?></strong></p>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Hub Tujuan</small>
                    <p class="mb-0"><strong><?= $findById($outlets, $manifest['destination_hub_id'], 'name') ?></strong></p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Driver</small>
                    <p class="mb-0"><strong><?= $manifest['driver_name'] ?? '-' ?></strong></p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Kendaraan</small>
                    <p class="mb-0"><strong><?= $manifest['vehicle_number'] ?? '-' ?></strong></p>
                </div>
                <div class="col-md-1">
                    <small class="text-muted">Total Paket</small>
                    <p class="mb-0"><strong><?= $manifest['total_shipments'] ?></strong></p>
                </div>
                <div class="col-md-1">
                    <small class="text-muted">Total Berat</small>
                    <p class="mb-0"><strong><?= number_format((float)$manifest['total_weight'], 2) ?> kg</strong></p>
                </div>
            </div>

            <?php if ($manifest['departed_at']) : ?>
            <div class="row mb-3">
                <div class="col-md-3">
                    <small class="text-muted">Berangkat</small>
                    <p class="mb-0"><?= date('d-m-Y H:i', strtotime($manifest['departed_at'])) ?></p>
                </div>
                <?php if ($manifest['arrived_at']) : ?>
                <div class="col-md-3">
                    <small class="text-muted">Tiba</small>
                    <p class="mb-0"><?= date('d-m-Y H:i', strtotime($manifest['arrived_at'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <hr>
            <h5>Daftar Shipment</h5>
            <div style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Berat</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($shipments)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($shipments as $s) : ?>
                                <?php
                                $cs = $s['current_status'];
                                $csClass = 'bg-secondary';
                                if (in_array($cs, ['picked_up','delivered'])) $csClass = 'bg-success';
                                elseif ($cs === 'in_transit') $csClass = 'bg-primary';
                                elseif (in_array($cs, ['draft','booked'])) $csClass = 'bg-warning';
                                elseif ($cs === 'cancelled') $csClass = 'bg-danger';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $s['awb'] ?></strong></td>
                                    <td><?= $s['item_name'] ?></td>
                                    <td><?= $s['qty'] ?></td>
                                    <td><?= number_format((float)$s['weight_kg'], 2) ?> kg</td>
                                    <td><?= $findById($customers, $s['sender_customer_id'], 'name') ?></td>
                                    <td><?= $findById($customers, $s['receiver_customer_id'], 'name') ?></td>
                                    <td><span class="badge <?= $csClass ?>"><?= ucwords(str_replace('_', ' ', $cs)) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="8" class="text-center">Tidak ada shipment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            <a href="/manifest" class="btn btn-light-secondary">Kembali</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>