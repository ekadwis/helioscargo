<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HELIOSCARGO — Solusi Pengiriman Cepat & Andal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
        body {
            font-family: 'Poppins', sans-serif;
        }

        .bg-navy {
            background: #1e3a5f;
        }

        .text-navy {
            color: #1e3a5f;
        }

        .btn-orange {
            background: #f97316;
            color: #fff;
        }

        .btn-orange:hover {
            background: #ea580c;
            color: #fff;
        }

        .section {
            padding: 80px 0;
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">

            <a class="navbar-brand fw-bold text-navy" href="#">
                HELIOSCARGO
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="menu" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto gap-lg-3">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tracking</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cek Tarif</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Promo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="bg-navy text-white section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-5 fw-bold mb-3">
                        Solusi Pengiriman <br>
                        <span class="text-warning">Cepat & Andal</span>
                    </h1>

                    <p class="mb-4">
                        HELIOSCARGO menyediakan layanan logistik terpercaya dengan jangkauan
                        seluruh Indonesia.
                    </p>

                    <a href="#" class="btn btn-orange btn-lg me-2">Lacak Pengiriman</a>
                    <a href="#" class="btn btn-outline-light btn-lg">Cek Tarif</a>
                </div>

                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="/img/truck_delivery.png" class="img-fluid" style="max-height:260px; border-radius: 20px;">
                </div>

            </div>
        </div>
    </section>

    <!-- ================= TRACKING ================= -->
    <section class="section bg-light">
        <div class="container text-center">

            <h2 class="fw-bold text-navy mb-3">Lacak Pengiriman Anda</h2>
            <p class="text-muted mb-4">
                Masukkan nomor resi untuk mengetahui status paket
            </p>

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card shadow-sm p-4">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" placeholder="Masukkan nomor resi">
                                <button class="btn btn-orange btn-lg">Lacak</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ================= CEK TARIF ================= -->
    <section class="section">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy">Hitung Biaya Pengiriman</h2>
                <p class="text-muted">Cek estimasi tarif pengiriman</p>
            </div>

            <div class="card shadow-sm p-4">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kota Asal</label>
                        <select class="form-select">
                            <option>Pilih Kota</option>
                            <option>Jakarta</option>
                            <option>Bandung</option>
                            <option>Surabaya</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kota Tujuan</label>
                        <select class="form-select">
                            <option>Pilih Kota</option>
                            <option>Jakarta</option>
                            <option>Bandung</option>
                            <option>Surabaya</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Layanan</label>
                        <select class="form-select">
                            <option>Express</option>
                            <option>Regular</option>
                            <option>Economy</option>
                        </select>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-dark btn-lg">Cek Tarif</button>
                </div>

            </div>

        </div>
    </section>

    <!-- ================= LAYANAN ================= -->
    <section class="section bg-light">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy">Pilihan Layanan Pengiriman</h2>
                <p class="text-muted">Berbagai layanan sesuai kebutuhan</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <h4 class="fw-bold text-navy">Express</h4>
                        <p class="text-warning fw-bold">1-2 Hari</p>
                        <p class="text-muted">Pengiriman super cepat prioritas tinggi.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <h4 class="fw-bold text-navy">Regular</h4>
                        <p class="fw-bold">3-5 Hari</p>
                        <p class="text-muted">Layanan standar harga terjangkau.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <h4 class="fw-bold text-navy">Economy</h4>
                        <p class="text-success fw-bold">5-7 Hari</p>
                        <p class="text-muted">Pengiriman hemat untuk non-urgent.</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================= PROMO ================= -->
    <section class="section">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy">Promo Spesial</h2>
                <p class="text-muted">Penawaran menarik untuk pengiriman Anda</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold text-navy">Diskon 20%</h5>
                            <p class="text-muted">
                                Nikmati potongan harga untuk pengiriman ke seluruh Pulau Jawa.
                            </p>
                            <span class="badge bg-warning text-dark">Berlaku hingga 30 Juni</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold text-navy">Gratis Asuransi</h5>
                            <p class="text-muted">
                                Perlindungan penuh tanpa biaya tambahan untuk barang berharga.
                            </p>
                            <span class="badge bg-success">Limited Offer</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold text-navy">Cashback Ongkir</h5>
                            <p class="text-muted">
                                Dapatkan cashback untuk pengiriman pertama Anda.
                            </p>
                            <span class="badge bg-primary">Member Baru</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- ================= BERITA ================= -->
    <section class="section bg-light">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy">Berita & Informasi</h2>
                <p class="text-muted">Update terbaru dari HELIOSCARGO</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d" class="card-img-top" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="fw-bold">Ekspansi Layanan ke Indonesia Timur</h5>
                            <p class="text-muted small">
                                HELIOSCARGO kini menjangkau wilayah Indonesia Timur dengan layanan cepat.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1605902711622-cfb43c44367f" class="card-img-top" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="fw-bold">Teknologi Tracking Terbaru</h5>
                            <p class="text-muted small">
                                Sistem pelacakan real-time kini lebih akurat dan cepat.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1553413077-190dd305871c" class="card-img-top" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="fw-bold">Promo Ongkir Nasional</h5>
                            <p class="text-muted small">
                                Diskon besar untuk pengiriman ke seluruh Indonesia.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- ================= CONTACT ================= -->
    <section class="section">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy">Hubungi Kami</h2>
                <p class="text-muted">Kami siap membantu kebutuhan pengiriman Anda</p>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100">

                        <h5 class="fw-bold text-navy mb-3">Informasi Kontak</h5>

                        <p><strong>Alamat:</strong><br>
                            Jl. Raya Logistik No. 123, Jakarta
                        </p>

                        <p><strong>Telepon:</strong><br>
                            (021) 1234 5678
                        </p>

                        <p><strong>Email:</strong><br>
                            info@HELIOSCARGO.com
                        </p>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100">

                        <h5 class="fw-bold text-navy mb-3">Kirim Pesan</h5>

                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Nama">
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email">
                            </div>

                            <div class="mb-3">
                                <textarea class="form-control" rows="4" placeholder="Pesan"></textarea>
                            </div>

                            <button class="btn btn-orange w-100">Kirim</button>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">

            <div class="row g-4">

                <div class="col-md-4">
                    <h5 class="fw-bold">HELIOSCARGO</h5>
                    <p class="text-white-50">
                        Solusi logistik terpercaya dengan jangkauan nasional dan layanan cepat.
                    </p>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Tracking</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Cek Tarif</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Promo</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">Layanan</h6>
                    <ul class="list-unstyled">
                        <li>Express</li>
                        <li>Regular</li>
                        <li>Economy</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">Kontak</h6>
                    <p class="text-white-50 mb-1">info@HELIOSCARGO.com</p>
                    <p class="text-white-50">(021) 1234 5678</p>
                </div>

            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-white-50">
                © 2026 HELIOSCARGO — All Rights Reserved
            </div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>