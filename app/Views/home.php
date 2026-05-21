<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HELIOSCARGO — Solusi Pengiriman Cepat & Andal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --navy: #1e3a5f;
            --navy-light: #2d4f7f;
            --orange: #f97316;
            --orange-hover: #ea580c;
        }

        /* NAVBAR */
        .navbar-custom { background: rgba(255,255,255,0.97); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.08); }
        .navbar-brand-logo { font-weight: 800; font-size: 1.4rem; color: var(--navy); letter-spacing: -0.5px; }
        .navbar-brand-logo span { color: var(--orange); }
        .nav-link-custom { color: #444; font-weight: 500; transition: color .2s; padding: 0.5rem 0.8rem !important; }
        .nav-link-custom:hover { color: var(--navy); }
        .btn-login { background: var(--navy); color: #fff; border-radius: 8px; padding: 0.4rem 1.2rem; font-weight: 600; }
        .btn-login:hover { background: var(--navy-light); color: #fff; }

        /* HERO */
        .hero-section {
            background: linear-gradient(135deg, var(--navy) 0%, #162d4a 60%, #0f1f35 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-badge { background: rgba(249,115,22,0.2); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); border-radius: 50px; padding: 6px 16px; font-size: 0.8rem; font-weight: 600; display: inline-block; margin-bottom: 1rem; }
        .hero-title { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 1.2rem; }
        .hero-title span { color: #fb923c; }
        .hero-subtitle { color: rgba(255,255,255,0.7); font-size: 1.05rem; margin-bottom: 2rem; line-height: 1.7; }
        .btn-orange { background: var(--orange); color: #fff; border-radius: 10px; font-weight: 600; padding: 0.75rem 1.8rem; border: none; transition: all .3s; }
        .btn-orange:hover { background: var(--orange-hover); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(249,115,22,0.4); }
        .btn-outline-white { border: 2px solid rgba(255,255,255,0.4); color: #fff; border-radius: 10px; font-weight: 600; padding: 0.75rem 1.8rem; transition: all .3s; }
        .btn-outline-white:hover { background: rgba(255,255,255,0.1); color: #fff; transform: translateY(-2px); }
        .hero-stats { display: flex; gap: 2rem; margin-top: 2.5rem; flex-wrap: wrap; }
        .hero-stat-item { text-align: center; }
        .hero-stat-num { font-size: 1.6rem; font-weight: 800; color: #fb923c; }
        .hero-stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.6); }
        .hero-float-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            color: #fff;
            transition: transform .3s;
        }
        .hero-float-card:hover { transform: translateY(-4px); }

        /* TRACKING SECTION */
        .section-track { background: #f8fafc; padding: 80px 0; }
        .track-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 2.5rem; }
        .track-input { border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.2rem; font-size: 1rem; transition: border .2s; }
        .track-input:focus { border-color: var(--navy); box-shadow: 0 0 0 4px rgba(30,58,95,0.1); }
        .btn-track { background: var(--navy); color: #fff; border-radius: 12px; padding: 0.85rem 2rem; font-weight: 700; border: none; transition: all .3s; }
        .btn-track:hover { background: var(--navy-light); transform: translateY(-2px); color: #fff; }

        /* CEK TARIF */
        .section-tarif { padding: 80px 0; }
        .tarif-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 2.5rem; }
        .tarif-result { background: linear-gradient(135deg, var(--navy), var(--navy-light)); color: #fff; border-radius: 16px; padding: 1.5rem 2rem; margin-top: 1.5rem; display: none; }
        .tarif-result.show { display: block; animation: fadeInUp .4s ease; }
        .result-price { font-size: 2rem; font-weight: 800; color: #fb923c; }
        .select2-container--default .select2-selection--single { border: 2px solid #e2e8f0; border-radius: 12px; height: 48px; line-height: 44px; padding: 0 12px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 44px; color: #333; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; }

        /* LAYANAN */
        .section-layanan { background: #f8fafc; padding: 80px 0; }
        .service-card { background: #fff; border-radius: 20px; padding: 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 2px solid transparent; transition: all .3s; height: 100%; }
        .service-card:hover { border-color: var(--navy); transform: translateY(-6px); box-shadow: 0 15px 40px rgba(30,58,95,0.15); }
        .service-icon { width: 64px; height: 64px; background: linear-gradient(135deg, var(--navy), var(--navy-light)); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem; font-size: 1.6rem; color: #fff; }
        .service-badge { display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }

        /* PROMO */
        .section-promo { padding: 80px 0; }
        .promo-card { border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: none; height: 100%; transition: transform .3s; background: #fff; border-left: 4px solid var(--orange); }
        .promo-card:hover { transform: translateY(-4px); }

        /* NEWS */
        .section-news { background: #f8fafc; padding: 80px 0; }
        .news-card { border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: none; height: 100%; transition: transform .3s; }
        .news-card:hover { transform: translateY(-6px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
        .news-card img { height: 200px; object-fit: cover; width: 100%; }

        /* CONTACT */
        .section-contact { padding: 80px 0; }
        .contact-form-input { border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 1rem; transition: border .2s; width: 100%; }
        .contact-form-input:focus { outline: none; border-color: var(--navy); box-shadow: 0 0 0 4px rgba(30,58,95,0.1); }
        .contact-info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .contact-info-icon { width: 44px; height: 44px; background: linear-gradient(135deg, var(--navy), var(--navy-light)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; flex-shrink: 0; }

        /* SECTION TITLE */
        .section-title { font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 800; color: var(--navy); margin-bottom: 0.5rem; }
        .section-subtitle { color: #64748b; font-size: 1rem; }
        .section-divider { width: 50px; height: 4px; background: var(--orange); border-radius: 2px; margin: 0.8rem auto 1.5rem; }

        /* FOOTER */
        .footer { background: #0f1f35; color: rgba(255,255,255,0.75); padding: 60px 0 30px; }
        .footer-brand { font-size: 1.5rem; font-weight: 800; color: #fff; }
        .footer-brand span { color: var(--orange); }
        .footer-link { color: rgba(255,255,255,0.6); text-decoration: none; display: block; margin-bottom: 0.5rem; transition: color .2s; }
        .footer-link:hover { color: #fb923c; }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp .6s ease forwards; }

        /* ALERT */
        .alert-track { border-radius: 12px; border: none; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand navbar-brand-logo" href="/">
            HELIOS<span>CARGO</span>
        </a>
        <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-lg-1">
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#tracking">Tracking</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#tarif">Cek Tarif</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#promo">Promo</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#berita">Berita</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#contact">Kontak</a></li>
            </ul>
            <a href="/login" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login Staff
            </a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section" id="home">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-fade-up">
                <div class="hero-badge">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Layanan Logistik #1 Indonesia
                </div>
                <h1 class="hero-title">
                    Kirim Paket<br>
                    <span>Cepat, Aman</span><br>
                    & Terpercaya
                </h1>
                <p class="hero-subtitle">
                    HELIOSCARGO menyediakan solusi logistik end-to-end dengan jangkauan ke seluruh Indonesia.
                    Pantau kiriman Anda secara real-time kapan saja, di mana saja.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#tracking" class="btn btn-orange btn-lg">
                        <i class="bi bi-search me-2"></i>Lacak Paket
                    </a>
                    <a href="#tarif" class="btn btn-outline-white btn-lg">
                        <i class="bi bi-calculator me-2"></i>Cek Tarif
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">10K+</div>
                        <div class="hero-stat-label">Paket Terkirim</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">34</div>
                        <div class="hero-stat-label">Provinsi</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">99%</div>
                        <div class="hero-stat-label">On-Time Delivery</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="hero-float-card">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;background:rgba(249,115,22,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">🚚</div>
                                <div>
                                    <div style="font-size:0.8rem;opacity:0.7;">Status Pengiriman</div>
                                    <div style="font-weight:700;">AWB000011 — In Transit</div>
                                </div>
                                <span style="margin-left:auto;background:rgba(34,197,94,0.2);color:#4ade80;border-radius:50px;padding:4px 12px;font-size:0.78rem;font-weight:600;">Live</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-float-card text-center">
                            <div style="font-size:1.8rem;">📦</div>
                            <div style="font-weight:700;font-size:1.1rem;">Next Day</div>
                            <div style="font-size:0.8rem;opacity:0.6;">1-2 Hari</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-float-card text-center">
                            <div style="font-size:1.8rem;">🛡️</div>
                            <div style="font-weight:700;font-size:1.1rem;">Asuransi</div>
                            <div style="font-size:0.8rem;opacity:0.6;">Barang Terlindungi</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hero-float-card">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-warning"></i>
                                <div style="font-size:0.85rem;">
                                    <strong>Jangkauan Nasional</strong> — 500+ kota di seluruh Indonesia
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRACKING -->
<section class="section-track" id="tracking">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Lacak Pengiriman</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Masukkan nomor resi untuk melihat status paket Anda secara real-time</p>
        </div>

        <?php if (session()->getFlashdata('track_error')) : ?>
            <div class="alert alert-danger alert-track text-center mb-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('track_error') ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="track-card">
                    <form action="/track" method="post">
                        <?= csrf_field() ?>
                        <label class="fw-600 mb-2" style="font-weight:600; color: var(--navy);">
                            <i class="bi bi-upc-scan me-1"></i> Nomor Resi / AWB
                        </label>
                        <div class="input-group gap-2">
                            <input type="text" name="awb" class="form-control track-input"
                                placeholder="Contoh: AWB000011"
                                value="<?= old('awb') ?>" required>
                            <button type="submit" class="btn btn-track">
                                <i class="bi bi-search me-1"></i> Lacak
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Nomor resi dapat ditemukan di struk atau email konfirmasi pengiriman
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CEK TARIF -->
<section class="section-tarif" id="tarif">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Cek Tarif Pengiriman</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Estimasi biaya pengiriman berdasarkan lokasi dan jenis layanan</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="tarif-card">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;">
                                <i class="bi bi-geo-alt me-1 text-navy"></i> Kota Asal
                            </label>
                            <select id="originSelect" class="form-control" style="width:100%;">
                                <option value="">Ketik untuk mencari...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;">
                                <i class="bi bi-geo-alt-fill me-1 text-orange"></i> Kota Tujuan
                            </label>
                            <select id="destSelect" class="form-control" style="width:100%;">
                                <option value="">Ketik untuk mencari...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;">
                                <i class="bi bi-box me-1"></i> Berat (kg)
                            </label>
                            <input type="number" id="weightInput" class="form-control track-input"
                                placeholder="Contoh: 2.5" min="0.1" step="0.1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;">
                                <i class="bi bi-truck me-1"></i> Jenis Layanan
                            </label>
                            <select id="serviceSelect" class="form-control track-input">
                                <option value="">-- Pilih Layanan --</option>
                                <?php foreach ($services as $s) : ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= $s['name'] ?> (<?= $s['sla_days_min'] ?>-<?= $s['sla_days_max'] ?> hari)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <button id="btnCekTarif" class="btn btn-orange btn-lg px-5">
                                <i class="bi bi-calculator me-2"></i> Hitung Tarif
                            </button>
                        </div>
                    </div>

                    <!-- Hasil -->
                    <div class="tarif-result" id="tarifResult">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div style="font-size:0.85rem;opacity:0.7;">Estimasi Biaya Pengiriman</div>
                                <div class="result-price" id="resultPrice">Rp 0</div>
                                <div style="font-size:0.85rem;opacity:0.8;" id="resultDetail"></div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div id="resultInfo" style="font-size:0.85rem;line-height:1.8;opacity:0.9;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="tarifError" class="alert alert-danger alert-track mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LAYANAN -->
<section class="section-layanan" id="layanan">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Pilihan Layanan</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Kami menyediakan berbagai layanan pengiriman sesuai kebutuhan Anda</p>
        </div>
        <div class="row g-4">
            <?php
            $serviceIcons = ['bi-lightning-charge', 'bi-truck', 'bi-box-seam', 'bi-shield-check', 'bi-alarm'];
            $serviceColors = ['#f97316', '#1e3a5f', '#7c3aed', '#059669', '#dc2626'];
            $i = 0;
            foreach ($services as $s) :
                $icon  = $serviceIcons[$i % count($serviceIcons)];
                $color = $serviceColors[$i % count($serviceColors)];
                $i++;
            ?>
            <div class="col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, <?= $color ?>, <?= $color ?>cc);">
                        <i class="bi <?= $icon ?>"></i>
                    </div>
                    <h5 class="fw-700" style="font-weight:700;color:var(--navy);"><?= $s['name'] ?></h5>
                    <div class="service-badge mb-3" style="background:<?= $color ?>20;color:<?= $color ?>;">
                        <?= $s['sla_days_min'] ?>-<?= $s['sla_days_max'] ?> Hari
                    </div>
                    <p class="text-muted small">
                        Layanan pengiriman <?= strtolower($s['name']) ?> dengan jaminan keamanan dan ketepatan waktu.
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROMO -->
<section class="section-promo" id="promo">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Promo Spesial</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Penawaran menarik untuk pengiriman Anda</p>
        </div>
        <div class="row g-4">
            <?php foreach ($promos as $p) : ?>
            <div class="col-md-4">
                <div class="promo-card">
                    <h5 class="fw-bold" style="color:var(--navy);"><?= $p['title'] ?></h5>
                    <p class="text-muted"><?= $p['description'] ?></p>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-<?= $p['badge_color'] ?> text-<?= $p['badge_color'] === 'warning' ? 'dark' : 'white' ?>">
                            <?= $p['badge_text'] ?>
                        </span>
                        <?php if ($p['valid_until']) : ?>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                s/d <?= date('d M Y', strtotime($p['valid_until'])) ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BERITA -->
<section class="section-news" id="berita">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Berita & Informasi</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Update terbaru dari HELIOSCARGO</p>
        </div>
        <div class="row g-4">
            <?php foreach ($news as $n) : ?>
            <div class="col-md-4">
                <div class="card news-card">
                    <?php if ($n['image_url']) : ?>
                        <img src="<?= $n['image_url'] ?>" alt="<?= $n['title'] ?>">
                    <?php endif; ?>
                    <div class="card-body p-4">
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d M Y', strtotime($n['published_at'])) ?>
                        </small>
                        <h5 class="fw-bold mt-2" style="color:var(--navy);"><?= $n['title'] ?></h5>
                        <p class="text-muted small"><?= $n['excerpt'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section class="section-contact" id="contact">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Hubungi Kami</h2>
            <div class="section-divider mx-auto"></div>
            <p class="section-subtitle">Tim kami siap membantu kebutuhan pengiriman Anda</p>
        </div>

        <?php if (session()->getFlashdata('contact_success')) : ?>
            <div class="alert alert-success alert-track text-center mb-4">
                <i class="bi bi-check-circle me-2"></i>
                <?= session()->getFlashdata('contact_success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('contact_error')) : ?>
            <div class="alert alert-danger alert-track text-center mb-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('contact_error') ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="color:var(--navy);">Alamat</div>
                        <div class="text-muted">Jl. Logistik Raya No. 123, Jakarta Utara</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="color:var(--navy);">Telepon</div>
                        <div class="text-muted">(021) 5551234</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="color:var(--navy);">Email</div>
                        <div class="text-muted">info@helioscargo.com</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="color:var(--navy);">Jam Operasional</div>
                        <div class="text-muted">Senin - Sabtu: 08.00 - 20.00 WIB</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="tarif-card">
                    <h5 class="fw-bold mb-4" style="color:var(--navy);">Kirim Pesan</h5>
                    <form action="/contact" method="post">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="contact-form-input"
                                    placeholder="Nama Lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="contact-form-input"
                                    placeholder="Alamat Email" required>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="contact-form-input"
                                    rows="5" placeholder="Tulis pesan Anda..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-orange w-100 btn-lg">
                                    <i class="bi bi-send me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="footer-brand mb-2">HELIOS<span>CARGO</span></div>
                <p style="color:rgba(255,255,255,0.55);font-size:0.9rem;line-height:1.7;">
                    Solusi logistik terpercaya dengan jangkauan nasional dan layanan pengiriman cepat ke seluruh Indonesia.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <div style="color:#fff;font-weight:600;margin-bottom:1rem;">Menu</div>
                <a href="#tracking" class="footer-link">Tracking</a>
                <a href="#tarif" class="footer-link">Cek Tarif</a>
                <a href="#layanan" class="footer-link">Layanan</a>
                <a href="#promo" class="footer-link">Promo</a>
            </div>
            <div class="col-6 col-lg-2">
                <div style="color:#fff;font-weight:600;margin-bottom:1rem;">Layanan</div>
                <?php foreach ($services as $s) : ?>
                    <a href="#layanan" class="footer-link"><?= $s['name'] ?></a>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-4">
                <div style="color:#fff;font-weight:600;margin-bottom:1rem;">Kontak</div>
                <div style="color:rgba(255,255,255,0.6);font-size:0.9rem;line-height:2;">
                    <i class="bi bi-envelope me-2"></i>info@helioscargo.com<br>
                    <i class="bi bi-telephone me-2"></i>(021) 5551234<br>
                    <i class="bi bi-geo-alt me-2"></i>Jakarta, Indonesia
                </div>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1);">
        <div class="text-center" style="color:rgba(255,255,255,0.4);font-size:0.85rem;">
            © 2026 HELIOSCARGO — All Rights Reserved
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    // Select2 AJAX untuk lokasi
    function initLocationSelect(selector, placeholder) {
        $(selector).select2({
            placeholder: placeholder,
            minimumInputLength: 2,
            ajax: {
                url: '/locations/search',
                dataType: 'json',
                delay: 300,
                data: params => ({ q: params.term }),
                processResults: data => ({ results: data }),
                cache: true
            },
            width: '100%'
        });
    }

    initLocationSelect('#originSelect', 'Ketik nama kota/kelurahan asal...');
    initLocationSelect('#destSelect',   'Ketik nama kota/kelurahan tujuan...');

    // Cek Tarif
    $('#btnCekTarif').on('click', function() {
        const originId  = $('#originSelect').val();
        const destId    = $('#destSelect').val();
        const weight    = $('#weightInput').val();
        const serviceId = $('#serviceSelect').val();

        $('#tarifError').hide();
        $('#tarifResult').removeClass('show');

        if (!originId || !destId || !weight || !serviceId) {
            $('#tarifError').text('Semua field wajib diisi.').show();
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Menghitung...');

        $.ajax({
            url: '/cek-tarif',
            method: 'POST',
            data: {
                origin_id:  originId,
                dest_id:    destId,
                weight:     weight,
                service_id: serviceId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.error) {
                    $('#tarifError').text(res.error).show();
                } else {
                    $('#resultPrice').text('Rp ' + res.total.toLocaleString('id-ID'));
                    $('#resultDetail').text('Rp ' + res.harga_per_kg.toLocaleString('id-ID') + '/kg × ' + res.berat + ' kg');
                    $('#resultInfo').html(`
                        <div><i class="bi bi-geo-alt me-1"></i><strong>Dari:</strong> ${res.origin}</div>
                        <div><i class="bi bi-geo-alt-fill me-1"></i><strong>Ke:</strong> ${res.dest}</div>
                        <div><i class="bi bi-truck me-1"></i><strong>Layanan:</strong> ${res.service} (${res.sla})</div>
                        <div><i class="bi bi-map me-1"></i><strong>Zona:</strong> ${res.zona}</div>
                    `);
                    $('#tarifResult').addClass('show');
                }
            },
            error: function() {
                $('#tarifError').text('Terjadi kesalahan, coba lagi.').show();
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="bi bi-calculator me-2"></i> Hitung Tarif');
            }
        });
    });

    // Smooth scroll navbar
    $('a[href^="#"]').on('click', function(e) {
        const target = $($(this).attr('href'));
        if (target.length) {
            e.preventDefault();
            $('html,body').animate({ scrollTop: target.offset().top - 70 }, 600);
        }
    });
});
</script>
</body>
</html>