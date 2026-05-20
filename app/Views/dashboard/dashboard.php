<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<?php
$statusConfig = [
    'draft'      => ['label' => 'Draft',      'class' => 'bg-warning'],
    'booked'     => ['label' => 'Booked',     'class' => 'bg-warning'],
    'picked_up'  => ['label' => 'Picked Up',  'class' => 'bg-success'],
    'in_transit' => ['label' => 'In Transit', 'class' => 'bg-primary'],
    'delivered'  => ['label' => 'Delivered',  'class' => 'bg-success'],
    'cancelled'  => ['label' => 'Cancelled',  'class' => 'bg-danger'],
];
?>

<!-- Welcome Banner -->
<div class="card mb-4" style="background:linear-gradient(135deg, var(--primary-navy) 0%, #2d4a6f 100%); border:none;">
    <div class="card-body d-flex align-items-center justify-content-between" style="color:white;">
        <div>
            <h2 style="font-size:1.4rem; font-weight:600; margin-bottom:0.3rem;">
                Selamat Datang, <?= session()->get('full_name') ?? 'Admin' ?>! 👋
            </h2>
            <p style="opacity:0.75; margin:0; font-size:0.9rem;">
                <?= date('l, d F Y') ?> &nbsp;·&nbsp;
                <?= session()->get('role') === 'superadmin' ? 'Super Administrator' : 'Administrator' ?>
            </p>
        </div>
        <div style="font-size:3rem; opacity:0.2;">
            <i class="bi bi-truck"></i>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Shipment</div>
                    <h3 class="mb-0"><?= number_format($totalShipment) ?></h3>
                </div>
                <div class="fs-2 text-primary"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Pelanggan</div>
                    <h3 class="mb-0"><?= number_format($totalPelanggan) ?></h3>
                </div>
                <div class="fs-2 text-warning"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Outlet Aktif</div>
                    <h3 class="mb-0"><?= $totalOutlet ?></h3>
                </div>
                <div class="fs-2 text-success"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Pendapatan Bulan Ini</div>
                    <h3 class="mb-0" style="font-size:1.1rem;">
                        Rp <?= number_format($pendapatanBulanIni, 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="fs-2 text-success"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Status Breakdown -->
<div class="row mb-4">
    <?php
    $statusIcons = [
        'draft'      => ['icon' => 'bi-pencil-square',    'color' => 'text-secondary'],
        'booked'     => ['icon' => 'bi-bookmark-check',   'color' => 'text-warning'],
        'picked_up'  => ['icon' => 'bi-bag-check',        'color' => 'text-info'],
        'in_transit' => ['icon' => 'bi-truck',             'color' => 'text-primary'],
        'delivered'  => ['icon' => 'bi-check-circle-fill','color' => 'text-success'],
        'cancelled'  => ['icon' => 'bi-x-circle-fill',    'color' => 'text-danger'],
    ];
    foreach ($statusCount as $st => $count) :
        $cfg  = $statusConfig[$st];
        $icon = $statusIcons[$st];
    ?>
    <div class="col-6 col-md-2 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body py-3">
                <div class="fs-3 <?= $icon['color'] ?>">
                    <i class="bi <?= $icon['icon'] ?>"></i>
                </div>
                <h4 class="mb-0 mt-1"><?= $count ?></h4>
                <small class="text-muted"><?= $cfg['label'] ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Grafik -->
<div class="row mb-4">
    <div class="col-md-7 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Shipment 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="chartShipment" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Status</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartStatus" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Aksi Cepat</h5>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="/shipment" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Shipment
            </a>
            <a href="/pelanggan" class="btn btn-warning">
                <i class="bi bi-person-plus me-1"></i> Data Pelanggan
            </a>
            <a href="/manifest" class="btn btn-info text-white">
                <i class="bi bi-boxes me-1"></i> Buat Manifest
            </a>
            <a href="/shipment-tracking" class="btn btn-success">
                <i class="bi bi-geo-alt me-1"></i> Tracking
            </a>
            <a href="/outlet" class="btn btn-secondary">
                <i class="bi bi-building me-1"></i> Outlet
            </a>
            <?php if (session()->get('role') === 'superadmin') : ?>
            <a href="/laporan" class="btn btn-dark">
                <i class="bi bi-bar-chart me-1"></i> Laporan
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tabel + Tracking -->
<div class="row">
    <!-- Shipment Terbaru -->
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Shipment Terbaru</h5>
                <a href="/shipment" class="btn btn-sm btn-light-secondary">Lihat Semua</a>
            </div>
            <div style="overflow-x:auto;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>AWB</th>
                            <th>Barang</th>
                            <th>Pengirim</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentShipments)) : ?>
                            <?php foreach ($recentShipments as $s) : ?>
                                <?php $cfg = $statusConfig[$s['current_status']] ?? ['label' => $s['current_status'], 'class' => 'bg-secondary']; ?>
                                <tr>
                                    <td><strong><?= $s['awb'] ?></strong></td>
                                    <td><?= $s['item_name'] ?></td>
                                    <td><?= $s['sender_name'] ?? '-' ?></td>
                                    <td><small><?= $s['tujuan'] ?? '-' ?></small></td>
                                    <td><span class="badge <?= $cfg['class'] ?>"><?= $cfg['label'] ?></span></td>
                                    <td><small>Rp <?= number_format((float)$s['total_amount'], 0, ',', '.') ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">Belum ada shipment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aktivitas Tracking Terbaru -->
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Aktivitas Tracking</h5>
                <a href="/shipment-tracking" class="btn btn-sm btn-light-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentTracking)) : ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($recentTracking as $t) : ?>
                            <li class="d-flex gap-3 px-3 py-2 border-bottom">
                                <div class="flex-shrink-0 mt-1">
                                    <span class="badge bg-primary rounded-circle p-2">●</span>
                                </div>
                                <div>
                                    <p class="mb-0 small">
                                        <strong><?= $t['awb'] ?></strong> —
                                        <?= ucwords(str_replace('_', ' ', $t['status'])) ?>
                                    </p>
                                    <p class="mb-0 text-muted" style="font-size:0.78rem;">
                                        <?= $t['description'] ? substr($t['description'], 0, 50) . '...' : '-' ?>
                                    </p>
                                    <p class="mb-0 text-muted" style="font-size:0.75rem;">
                                        <?= date('d M Y H:i', strtotime($t['created_at'])) ?>
                                    </p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <div class="text-center text-muted py-4">Belum ada aktivitas tracking.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {

    const chartLabels  = <?= $chartLabels ?>;
    const chartData    = <?= $chartData ?>;
    const revenueData  = <?= $revenueData ?>;
    const statusLabels = <?= json_encode(array_keys($statusCount)) ?>;
    const statusValues = <?= json_encode(array_values($statusCount)) ?>;

    // ---- Chart Shipment per Hari ----
    new Chart(document.getElementById('chartShipment'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Jumlah Shipment',
                    data: chartData,
                    backgroundColor: 'rgba(30, 58, 95, 0.7)',
                    borderRadius: 6,
                    yAxisID: 'y',
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    type: 'line',
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.datasetIndex === 1) {
                                return 'Pendapatan: Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                            return 'Shipment: ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                y:  { beginAtZero: true, position: 'left',  title: { display: true, text: 'Shipment' } },
                y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Pendapatan' },
                      grid: { drawOnChartArea: false } }
            }
        }
    });

    // ---- Chart Donut Status ----
    const statusColors = {
        draft:      '#ffc107',
        booked:     '#fd7e14',
        picked_up:  '#17a2b8',
        in_transit: '#007bff',
        delivered:  '#28a745',
        cancelled:  '#dc3545',
    };

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: statusLabels.map(s => s.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
            datasets: [{
                data: statusValues,
                backgroundColor: statusLabels.map(s => statusColors[s] ?? '#aaa'),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            },
            cutout: '65%',
        }
    });
});
</script>
<?= $this->endSection() ?>