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
        
        <?php
        $uri = service('uri');
        $segment1 = $uri->getSegment(1);
        $segment2 = $uri->getSegment(2);
        ?>

        <a class="nav-item <?= ($segment1 == 'dashboard' || $segment1 == '') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a class="nav-item <?= in_array($segment1, ['pelanggan', 'customers']) ? 'active' : '' ?>" href="<?= base_url('pelanggan') ?>">
            <i class="bi bi-people-fill"></i>
            <span>Data Pelanggan</span>
        </a>

        <!-- SHIPMENT (Fokus Outlet Saat Ini) -->
        <a class="nav-item <?= ($segment1 == 'shipment' && $segment2 != 'tracking') ? 'active' : '' ?>" href="<?= base_url('shipment') ?>">
            <i class="bi bi-box-seam"></i>
            <span>Shipment</span>
        </a>

        <!-- MANIFEST -->
        <a class="nav-item <?= ($segment1 == 'manifest') ? 'active' : '' ?>" href="<?= base_url('manifest') ?>">
            <i class="bi bi-boxes"></i>
            <span>Manifest / Master AWB</span>
        </a>

        <!-- SHIPMENT TRACKING (Global View) -->
        <a class="nav-item <?= ($segment1 == 'shipment-tracking' || ($segment1 == 'shipment' && $segment2 == 'tracking')) ? 'active' : '' ?>" href="<?= base_url('shipment-tracking') ?>">
            <i class="bi bi-truck"></i>
            <span>Shipment Tracking</span>
        </a>

        <!-- OUTLET & GUDANG -->
         
        <a class="nav-item <?= ($segment1 == 'outlet') ? 'active' : '' ?>" href="<?= base_url('outlet') ?>">
            <i class="bi bi-building"></i>
            <span>Outlet & Gudang</span>
        </a>

    </div>

    <div class="nav-section">
        <div class="nav-section-title">System</div>

        <a class="nav-item <?= ($segment1 == 'users') ? 'active' : '' ?>" href="<?= base_url('users') ?>">
            <i class="bi bi-person-gear"></i>
            <span>Manajemen User</span>
        </a>

        <a class="nav-item <?= ($segment1 == 'settings') ? 'active' : '' ?>" href="<?= base_url('settings') ?>">
            <i class="bi bi-gear-fill"></i>
            <span>Pengaturan</span>
        </a>

        <a class="nav-item text-danger" href="<?= base_url('logout') ?>">
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