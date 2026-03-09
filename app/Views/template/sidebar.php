<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <a href="#" class="sidebar-logo">
            <div class="logo-icon">
                <i class="bi bi-truck"></i>
            </div>

            <div class="logo-text">
                HELIOS<span>CARGO</span>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>

            <!-- Wajib, untuk active sidebar -->
            <?php
            $uri = service('uri');
            $menu = $uri->getSegment(1);
            ?>

            <a class="nav-item <?= ($menu == 'dashboard' || $menu == '') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-item <?= ($menu == 'pelanggan') ? 'active' : '' ?>" href="<?= base_url('pelanggan') ?>">
                <i class="bi bi-people-fill"></i>
                <span>Data Pelanggan</span>
            </a>

            <a class="nav-item <?= ($menu == 'shipment') ? 'active' : '' ?>" href="<?= base_url('shipment') ?>">
                <i class="bi bi-box-seam"></i>
                <span>Shipment</span>
            </a>

            <a class="nav-item <?= ($menu == 'shipment-tracking') ? 'active' : '' ?>" href="<?= base_url('shipment-tracking') ?>">
                <i class="bi bi-truck"></i>
                <span>Shipment Tracking</span>
            </a>

            <a class="nav-item <?= ($menu == 'laporan') ? 'active' : '' ?>" href="<?= base_url('laporan') ?>">
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Laporan</span>
            </a>

            <a class="nav-item <?= ($menu == 'invoice') ? 'active' : '' ?>" href="<?= base_url('invoice') ?>">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Invoice</span>
            </a>

        </div>

        <div class="nav-section">

            <div class="nav-section-title">
                System
            </div>

            <a class="nav-item" href="<?= base_url('users') ?>">
                <i class="bi bi-person-gear"></i>
                <span>Users</span>
            </a>

            <a class="nav-item" href="<?= base_url('settings') ?>">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>

            <a class="nav-item" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>

        </div>

    </nav>

    <div class="sidebar-footer">

        <div class="user-profile">

            <div class="user-avatar">
                AD
            </div>

            <div class="user-info">
                <div class="user-name">
                    Admin User
                </div>

                <div class="user-role">
                    Super Administrator
                </div>
            </div>

        </div>

    </div>

</aside>