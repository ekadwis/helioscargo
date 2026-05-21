<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking <?= $awb ?> — HELIOSCARGO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { --navy: #1e3a5f; --orange: #f97316; }
        body { background: #f8fafc; }
        .navbar-brand { font-weight: 800; color: var(--navy) !important; }
        .navbar-brand span { color: var(--orange); }

        .status-banner {
            background: linear-gradient(135deg, var(--navy), #2d4f7f);
            color: #fff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .awb-text { font-size: 1.6rem; font-weight: 800; letter-spacing: 1px; }
        .status-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .info-card { background: #fff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 1.5rem; }
        .info-label { font-size: 0.78rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: #1e293b; }

        /* Timeline */
        .timeline { position: relative; padding-left: 2rem; }
        .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: #e2e8f0; }
        .timeline-item { position: relative; margin-bottom: 1.5rem; }
        .timeline-item:last-child { margin-bottom: 0; }
        .timeline-dot {
            position: absolute;
            left: -2rem;
            top: 4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--navy);
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .timeline-item:first-child .timeline-dot { background: var(--orange); box-shadow: 0 0 0 2px var(--orange); }
        .timeline-date { font-size: 0.78rem; color: #94a3b8; margin-bottom: 2px; }
        .timeline-status { font-weight: 700; color: var(--navy); }
        .timeline-desc { font-size: 0.85rem; color: #64748b; }
        .timeline-loc { font-size: 0.78rem; color: #94a3b8; }
        .timeline-card { background: #f8fafc; border-radius: 12px; padding: 1rem 1.2rem; }
        .timeline-item:first-child .timeline-card { background: #fff8f0; border: 1px solid #fed7aa; }
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
    ?>

    <!-- Status Banner -->
    <div class="status-banner">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div style="font-size:0.8rem;opacity:0.7;margin-bottom:4px;">Nomor Resi</div>
                <div class="awb-text"><?= $shipment['awb'] ?></div>
                <div style="margin-top:0.5rem;">
                    <span class="status-pill" style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['color'] ?>;">
                        <?= $cfg['label'] ?>
                    </span>
                </div>
            </div>
            <div class="text-end">
                <div style="font-size:0.8rem;opacity:0.7;">Layanan</div>
                <div style="font-weight:700;"><?= $shipment['service_name'] ?? '-' ?></div>
                <?php if ($shipment['estimated_delivery_date']) : ?>
                    <div style="font-size:0.8rem;opacity:0.7;margin-top:4px;">Est. Tiba</div>
                    <div style="font-weight:600;"><?= date('d M Y', strtotime($shipment['estimated_delivery_date'])) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info Paket -->
    <div class="info-card">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="info-label">Barang</div>
                <div class="info-value"><?= $shipment['item_name'] ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Berat</div>
                <div class="info-value"><?= number_format((float)$shipment['weight_kg'], 2) ?> kg</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Qty</div>
                <div class="info-value"><?= $shipment['qty'] ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Outlet Saat Ini</div>
                <div class="info-value"><?= $shipment['current_outlet_name'] ?? '-' ?></div>
            </div>
            <div class="col-md-6">
                <div class="info-label">Pengirim</div>
                <div class="info-value"><?= $shipment['sender_name'] ?? '-' ?></div>
                <div style="font-size:0.82rem;color:#64748b;">
                    <?= $shipment['origin_kel'] ?>, <?= $shipment['origin_kab'] ?>, <?= $shipment['origin_prov'] ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-label">Penerima</div>
                <div class="info-value"><?= $shipment['receiver_name'] ?? '-' ?></div>
                <div style="font-size:0.82rem;color:#64748b;">
                    <?= $shipment['dest_kel'] ?>, <?= $shipment['dest_kab'] ?>, <?= $shipment['dest_prov'] ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Tracking -->
    <div class="info-card">
        <h5 class="fw-bold mb-4" style="color:var(--navy);">
            <i class="bi bi-clock-history me-2"></i>History Tracking
        </h5>

        <?php if (!empty($trackings)) : ?>
            <div class="timeline">
                <?php foreach (array_reverse($trackings) as $t) : ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-card">
                            <div class="timeline-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= date('d M Y, H:i', strtotime($t['created_at'])) ?> WIB
                            </div>
                            <div class="timeline-status">
                                <?= ucwords(str_replace('_', ' ', $t['status'])) ?>
                            </div>
                            <?php if ($t['description']) : ?>
                                <div class="timeline-desc"><?= $t['description'] ?></div>
                            <?php endif; ?>
                            <?php if ($t['kelurahan']) : ?>
                                <div class="timeline-loc">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?= $t['kelurahan'] ?>, <?= $t['kecamatan'] ?>, <?= $t['kabupaten'] ?>
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