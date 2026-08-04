<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<?php
// Helper lookup
$findById = function ($arr, $id, $field) {
    foreach ($arr as $item) {
        if ($item['id'] == $id) return $item[$field] ?? '-';
    }
    return '-';
};

$statusConfig = [
    'draft'      => ['label' => 'Draft',       'color' => '#fbbf24', 'bg' => 'rgba(251,191,36,0.2)'],
    'booked'     => ['label' => 'Booked',      'color' => '#f97316', 'bg' => 'rgba(249,115,22,0.2)'],
    'picked_up'  => ['label' => 'Picked Up',   'color' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.2)'],
    'in_transit' => ['label' => 'In Transit',  'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,0.2)'],
    'delivered'  => ['label' => 'Delivered',   'color' => '#22c55e', 'bg' => 'rgba(34,197,94,0.2)'],
    'cancelled'  => ['label' => 'Cancelled',   'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.2)'],
];
$cs  = $shipment['current_status'];
$cfg = $statusConfig[$cs] ?? ['label' => $cs, 'color' => '#94a3b8', 'bg' => 'rgba(148,163,184,0.2)'];

// Mapping status ke 3 step progress (ala Shopee)
$stepOrder = ['draft' => 1, 'booked' => 1, 'picked_up' => 1, 'in_transit' => 2, 'delivered' => 3];
$currentStep = $stepOrder[$cs] ?? 1;
$isCancelled = ($cs === 'cancelled');

function stepClass($stepNumber, $currentStep, $isCancelled)
{
    if ($isCancelled) return '';
    if ($stepNumber < $currentStep) return 'done';
    if ($stepNumber === $currentStep) return 'active';
    return '';
}
?>

<style>
    .sd-wrap * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sd-wrap {
        --navy: #1e3a5f;
        --orange: #f97316;
        --green: #16a34a;
        --green-soft: #d1fae5;
        --gray-line: #d9dee5;
        max-width: 900px;
        margin: 0 auto;
    }

    .sd-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .sd-status-banner {
        background: linear-gradient(135deg, var(--navy), #2d4f7f);
        color: #fff;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .sd-awb-text {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .sd-status-pill {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .sd-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
    }

    .sd-info-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .sd-info-value {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.05rem;
    }

    /* Progress Steps */
    .sd-progress-caption {
        font-weight: 700;
        color: var(--green);
        font-size: 1.05rem;
        margin-bottom: 1.25rem;
    }

    .sd-progress-caption.is-cancelled {
        color: #ef4444;
    }

    .sd-step-track {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        padding: 0 6px;
    }

    .sd-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .sd-step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid var(--gray-line);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: #94a3b8;
        transition: all .2s;
    }

    .sd-step-line {
        position: absolute;
        top: 15px;
        left: calc(50% + 18px);
        right: calc(-50% + 18px);
        height: 3px;
        background: var(--gray-line);
        z-index: 1;
    }

    .sd-step:last-child .sd-step-line {
        display: none;
    }

    .sd-step.done .sd-step-circle {
        background: var(--green);
        border-color: var(--green);
        color: #fff;
    }

    .sd-step.done .sd-step-line {
        background: var(--green);
    }

    .sd-step.active .sd-step-circle {
        background: var(--green);
        border-color: var(--green);
        box-shadow: 0 0 0 4px var(--green-soft);
        color: #fff;
    }

    .sd-step-label {
        margin-top: 8px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #64748b;
        text-align: center;
    }

    .sd-step.done .sd-step-label,
    .sd-step.active .sd-step-label {
        color: var(--green);
    }

    /* Timeline */
    .sd-timeline-row {
        display: flex;
        gap: 1rem;
    }

    .sd-timeline-col-date {
        width: 68px;
        flex-shrink: 0;
        text-align: right;
        font-size: 0.85rem;
        color: #94a3b8;
        padding-top: 1px;
        font-weight: 500;
    }

    .sd-timeline-row:first-child .sd-timeline-col-date {
        color: #0f172a;
        font-weight: 700;
    }

    .sd-timeline-col-line {
        width: 16px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sd-timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #cbd5e1;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .sd-timeline-row:first-child .sd-timeline-dot {
        background: var(--green);
        width: 14px;
        height: 14px;
    }

    .sd-timeline-connector {
        width: 2px;
        flex: 1;
        background: #e2e8f0;
        margin-top: 2px;
    }

    .sd-timeline-row:last-child .sd-timeline-connector {
        display: none;
    }

    .sd-timeline-col-content {
        padding-bottom: 1.75rem;
        flex: 1;
    }

    .sd-timeline-desc {
        font-size: 1rem;
        color: #475569;
        line-height: 1.5;
        font-weight: 500;
    }

    .sd-timeline-row:first-child .sd-timeline-desc {
        color: var(--green);
        font-weight: 700;
        font-size: 1.05rem;
    }

    .sd-timeline-loc {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 3px;
        font-weight: 500;
    }

    .sd-timeline-row:first-child .sd-timeline-loc {
        color: #334155;
        font-weight: 600;
    }
</style>

<div class="page-content active">
    <div class="sd-wrap">

        <!-- Tombol Aksi -->
        <div class="sd-actions">
            <a href="/shipment/edit/<?= $shipment['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Edit Shipment
            </a>
            <a href="/shipment/resi/<?= $shipment['id'] ?>" class="btn btn-success" target="_blank">
                <i class="bi bi-printer me-1"></i> Cetak Resi
            </a>
            <a href="/shipment" class="btn btn-light-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Banner Status -->
        <div class="sd-status-banner">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <div style="font-size:0.8rem;opacity:0.7;margin-bottom:4px;">Nomor Resi</div>
                    <div class="sd-awb-text"><?= $shipment['awb'] ?></div>
                    <div style="margin-top:0.5rem;">
                        <span class="sd-status-pill" style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['color'] ?>;">
                            <?= $cfg['label'] ?>
                        </span>
                    </div>
                </div>
                <div class="text-end">
                    <div style="font-size:0.8rem;opacity:0.7;">Layanan</div>
                    <div style="font-weight:700;"><?= $findById($services, $shipment['service_id'], 'name') ?></div>
                    <?php if ($shipment['estimated_delivery_date']) : ?>
                        <div style="font-size:0.8rem;opacity:0.7;margin-top:4px;">Est. Tiba</div>
                        <div style="font-weight:600;"><?= date('d M Y', strtotime($shipment['estimated_delivery_date'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Progress 3 Step -->
        <div class="sd-info-card">
            <?php if ($isCancelled) : ?>
                <div class="sd-progress-caption is-cancelled">
                    <i class="bi bi-x-circle-fill me-1"></i> Pesanan dibatalkan
                </div>
            <?php else : ?>
                <div class="sd-progress-caption fs-2">
                    <?php if ($currentStep === 3) : ?>
                        Pesanan telah tiba
                    <?php elseif ($currentStep === 2) : ?>
                        Pesanan sedang menuju alamatmu
                    <?php else : ?>
                        Pesanan sedang dikirim
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="sd-step-track">
                <div class="sd-step <?= stepClass(1, $currentStep, $isCancelled) ?>">
                    <div class="sd-step-circle">
                        <?php if (stepClass(1, $currentStep, $isCancelled) === 'done') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="sd-step-line"></div>
                    <div class="sd-step-label">Sedang Dikirim</div>
                </div>
                <div class="sd-step <?= stepClass(2, $currentStep, $isCancelled) ?>">
                    <div class="sd-step-circle">
                        <?php if (stepClass(2, $currentStep, $isCancelled) === 'done') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="sd-step-line"></div>
                    <div class="sd-step-label">Menuju Alamatmu</div>
                </div>
                <div class="sd-step <?= stepClass(3, $currentStep, $isCancelled) ?>">
                    <div class="sd-step-circle">
                        <?php if (stepClass(3, $currentStep, $isCancelled) === 'done' || stepClass(3, $currentStep, $isCancelled) === 'active') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="sd-step-label">Pesanan Tiba</div>
                </div>
            </div>
        </div>

        <!-- Info Barang & Pengirim/Penerima -->
        <div class="sd-info-card">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="sd-info-label">Barang</div>
                    <div class="sd-info-value"><?= $shipment['item_name'] ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sd-info-label">Berat</div>
                    <div class="sd-info-value"><?= number_format($shipment['weight_kg'], 2) ?> kg</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sd-info-label">Qty</div>
                    <div class="sd-info-value"><?= $shipment['qty'] ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sd-info-label">Outlet Saat Ini</div>
                    <div class="sd-info-value"><?= $findById($outlets, $shipment['current_outlet_id'], 'name') ?></div>
                </div>
                <div class="col-md-6">
                    <div class="sd-info-label">Pengirim</div>
                    <div class="sd-info-value"><?= $findById($customers, $shipment['sender_customer_id'], 'name') ?></div>
                    <div style="font-size:0.9rem;color:#475569;font-weight:500;">
                        Kel. <?= $findById($locations, $shipment['origin_location_id'], 'kelurahan') ?>, <?= $findById($locations, $shipment['origin_location_id'], 'kecamatan') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sd-info-label">Penerima</div>
                    <div class="sd-info-value"><?= $findById($customers, $shipment['receiver_customer_id'], 'name') ?></div>
                    <div style="font-size:0.9rem;color:#475569;font-weight:500;">
                        Kel. <?= $findById($locations, $shipment['destination_location_id'], 'kelurahan') ?>, <?= $findById($locations, $shipment['destination_location_id'], 'kecamatan') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Tracking -->
        <div class="sd-info-card">
            <h5 class="fw-bold mb-4" style="color:var(--navy);">
                <i class="bi bi-clock-history me-2"></i>History Tracking
            </h5>

            <?php if (!empty($trackings)) : ?>
                <?php foreach (array_reverse($trackings) as $track) : ?>
                    <div class="sd-timeline-row">
                        <div class="sd-timeline-col-date">
                            <?= date('d M', strtotime($track['created_at'])) ?><br>
                            <?= date('H:i', strtotime($track['created_at'])) ?>
                        </div>
                        <div class="sd-timeline-col-line">
                            <div class="sd-timeline-dot"></div>
                            <div class="sd-timeline-connector"></div>
                        </div>
                        <div class="sd-timeline-col-content">
                            <div class="sd-timeline-desc">
                                <?= str_replace('_', ' ', $track['status']) ?>
                                <?php if ($track['description']) : ?>
                                    . <?= $track['description'] ?>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($track['kelurahan'])) : ?>
                                <div class="sd-timeline-loc">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Kel. <?= $track['kelurahan'] ?>, Kec. <?= $track['kecamatan'] ?>, <?= $track['kabupaten'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-muted text-center mb-0">Belum ada tracking.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>