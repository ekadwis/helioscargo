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


    <?= $this->include('template/footer') ?>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>