<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking <?= esc($awb) ?> — HELIOSCARGO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --navy: #1e3a5f;
            --orange: #f97316;
            --green: #16a34a;
            --green-soft: #d1fae5;
            --gray-line: #d9dee5;
        }

        body {
            background: #f8fafc;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--navy) !important;
        }

        .navbar-brand span {
            color: var(--orange);
        }

        .status-banner {
            background: linear-gradient(135deg, var(--navy), #2d4f7f);
            color: #fff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .awb-text {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .status-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .info-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-size: 0.78rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
        }

        /* ===== Progress Steps (ala Shopee) ===== */
        .progress-caption {
            font-weight: 700;
            color: var(--green);
            font-size: 1.05rem;
            margin-bottom: 1.25rem;
        }

        .progress-caption.is-cancelled {
            color: #ef4444;
        }

        .step-track {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding: 0 6px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .step-circle {
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

        .step-line {
            position: absolute;
            top: 15px;
            left: calc(50% + 18px);
            right: calc(-50% + 18px);
            height: 3px;
            background: var(--gray-line);
            z-index: 1;
        }

        .step:last-child .step-line {
            display: none;
        }

        .step.done .step-circle {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .step.done .step-line {
            background: var(--green);
        }

        .step.active .step-circle {
            background: var(--green);
            border-color: var(--green);
            box-shadow: 0 0 0 4px var(--green-soft);
            color: #fff;
        }

        .step-label {
            margin-top: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #94a3b8;
            text-align: center;
        }

        .step.done .step-label,
        .step.active .step-label {
            color: var(--green);
        }

        /* ===== Timeline (ala Shopee) ===== */
        .timeline {
            position: relative;
        }

        .timeline-row {
            display: flex;
            gap: 1rem;
        }

        .timeline-col-date {
            width: 62px;
            flex-shrink: 0;
            text-align: right;
            font-size: 0.78rem;
            color: #cbd5e1;
            padding-top: 1px;
        }

        .timeline-row:first-child .timeline-col-date {
            color: #1e293b;
            font-weight: 600;
        }

        .timeline-col-line {
            width: 16px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            margin-top: 4px;
            flex-shrink: 0;
        }

        .timeline-row:first-child .timeline-dot {
            background: var(--green);
            width: 14px;
            height: 14px;
        }

        .timeline-connector {
            width: 2px;
            flex: 1;
            background: #e2e8f0;
            margin-top: 2px;
        }

        .timeline-row:last-child .timeline-connector {
            display: none;
        }

        .timeline-col-content {
            padding-bottom: 1.75rem;
            flex: 1;
        }

        .timeline-desc {
            font-size: 0.92rem;
            color: #cbd5e1;
            line-height: 1.5;
        }

        .timeline-row:first-child .timeline-desc {
            color: var(--green);
            font-weight: 600;
        }

        .timeline-loc {
            font-size: 0.78rem;
            color: #cbd5e1;
            margin-top: 2px;
        }

        .timeline-row:first-child .timeline-loc {
            color: #64748b;
            font-weight: 500;
        }

        .timeline-proof-link {
            display: inline-block;
            margin-top: 4px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .timeline-proof-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">HELIOS<span>CARGO</span></a>
            <a href="/" class="btn btn-sm ms-auto" style="background:var(--navy);color:#fff;border-radius:8px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container py-4" style="max-width:800px;">

        <?php
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

        // ==== Mapping status ke 3 step progress (ala Shopee) ====
        // Step 1: Sedang Dikirim  -> draft, booked, picked_up
        // Step 2: Menuju Alamatmu -> in_transit
        // Step 3: Pesanan Tiba    -> delivered
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

        <div class="status-banner">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <div style="font-size:0.8rem;opacity:0.7;margin-bottom:4px;">Nomor Resi</div>
                    <div class="awb-text"><?= esc($shipment['awb']) ?></div>
                    <div style="margin-top:0.5rem;">
                        <span class="status-pill" style="background:<?= esc($cfg['bg']) ?>;color:<?= esc($cfg['color']) ?>;">
                            <?= esc($cfg['label']) ?>
                        </span>
                    </div>
                </div>
                <div class="text-end">
                    <div style="font-size:0.8rem;opacity:0.7;">Layanan</div>
                    <div style="font-weight:700;"><?= esc($shipment['service_name']) ?? '-' ?></div>
                    <?php if ($shipment['estimated_delivery_date']) : ?>
                        <div style="font-size:0.8rem;opacity:0.7;margin-top:4px;">Est. Tiba</div>
                        <div style="font-weight:600;"><?= date('d M Y', strtotime($shipment['estimated_delivery_date'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== Progress 3 Step ala Shopee ===== -->
        <div class="info-card">
            <?php if ($isCancelled) : ?>
                <div class="progress-caption is-cancelled">
                    <i class="bi bi-x-circle-fill me-1"></i> Pesanan dibatalkan
                </div>
            <?php else : ?>
                <div class="progress-caption">
                    <?php if ($currentStep === 3 && !empty($trackings)) : ?>
                        Pesanan tiba pada <?= date('d M', strtotime($trackings[count($trackings) - 1]['created_at'])) ?>
                    <?php elseif ($currentStep === 2) : ?>
                        Pesanan sedang menuju alamatmu
                    <?php else : ?>
                        Pesanan sedang dikirim
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="step-track">
                <div class="step <?= stepClass(1, $currentStep, $isCancelled) ?>">
                    <div class="step-circle">
                        <?php if (stepClass(1, $currentStep, $isCancelled) === 'done') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-label">Sedang Dikirim</div>
                </div>
                <div class="step <?= stepClass(2, $currentStep, $isCancelled) ?>">
                    <div class="step-circle">
                        <?php if (stepClass(2, $currentStep, $isCancelled) === 'done') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-label">Menuju Alamatmu</div>
                </div>
                <div class="step <?= stepClass(3, $currentStep, $isCancelled) ?>">
                    <div class="step-circle">
                        <?php if (stepClass(3, $currentStep, $isCancelled) === 'done' || stepClass(3, $currentStep, $isCancelled) === 'active') : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div class="step-label">Pesanan Tiba</div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="info-label">Barang</div>
                    <div class="info-value"><?= esc($shipment['item_name']) ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label">Berat</div>
                    <div class="info-value"><?= number_format((float)$shipment['weight_kg'], 2) ?> kg</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label">Qty</div>
                    <div class="info-value"><?= esc($shipment['qty']) ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label">Outlet Saat Ini</div>
                    <div class="info-value"><?= esc($shipment['current_outlet_name']) ?? '-' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Pengirim</div>
                    <div class="info-value"><?= esc($shipment['sender_name']) ?? '-' ?></div>
                    <div style="font-size:0.82rem;color:#64748b;">
                        <?= esc($shipment['origin_kel']) ?>, <?= esc($shipment['origin_kab']) ?>, <?= esc($shipment['origin_prov']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Penerima</div>
                    <div class="info-value"><?= esc($shipment['receiver_name']) ?? '-' ?></div>
                    <div style="font-size:0.82rem;color:#64748b;">
                        <?= esc($shipment['dest_kel']) ?>, <?= esc($shipment['dest_kab']) ?>, <?= esc($shipment['dest_prov']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Timeline ala Shopee (terbaru di atas, hijau; lama pudar abu-abu) ===== -->
        <div class="info-card">
            <h5 class="fw-bold mb-4" style="color:var(--navy);">
                <i class="bi bi-clock-history me-2"></i>History Tracking
            </h5>

            <?php if (!empty($trackings)) : ?>
                <div class="timeline">
                    <?php
                    // Urutkan terbaru paling atas
                    $sorted = array_reverse($trackings);
                    foreach ($sorted as $t) :
                    ?>
                        <div class="timeline-row">
                            <div class="timeline-col-date">
                                <?= date('d M', strtotime($t['created_at'])) ?><br>
                                <?= date('H:i', strtotime($t['created_at'])) ?>
                            </div>
                            <div class="timeline-col-line">
                                <div class="timeline-dot"></div>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-col-content">
                                <div class="timeline-desc">
                                    <?= ucwords(str_replace('_', ' ', $t['status'])) ?>
                                    <?php if ($t['description']) : ?>
                                        . <?= esc($t['description']) ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($t['kelurahan']) : ?>
                                    <div class="timeline-loc">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?= esc($t['kelurahan']) ?>, <?= esc($t['kecamatan']) ?>, <?= esc($t['kabupaten']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center text-muted py-3">
                    <i class="bi bi-clock fs-3 d-block mb-2"></i>
                    Belum ada history tracking untuk paket ini.
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-3">
            <a href="/#tracking" class="btn btn-orange">
                <i class="bi bi-search me-1"></i> Lacak Resi Lain
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>