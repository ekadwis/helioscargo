<?= $this->include('template/header') ?>
<?= $this->include('template/sidebar') ?>

<div class="main-content">
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="menu-toggle" id="menuToggle">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title"><?= $title ?? '' ?></h1>
        </div>
    </header>

    <main class="content-area">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="dashboard-footer">
        © 2026 HELIOSCARGO. All rights reserved.
    </footer>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<!-- Section khusus scripts dari tiap view -->
<?= $this->renderSection('scripts') ?>

</body>
</html>