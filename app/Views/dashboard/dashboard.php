<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<!-- Welcome -->
<div class="card" style="margin-bottom:1.5rem; background:linear-gradient(135deg,var(--primary-navy) 0%,#2d4a6f 100%); border:none;">

  <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;color:white;">

    <div>
      <h2 style="font-size:1.5rem;font-weight:600;margin-bottom:0.5rem;">
        Selamat Datang, Admin! 👋
      </h2>

      <p style="opacity:0.8;margin:0;">
        Pantau semua aktivitas logistik Anda dari sini.
      </p>
    </div>

    <div style="font-size:3rem;opacity:0.3;">
      <i class="bi bi-truck"></i>
    </div>

  </div>
</div>

<!-- Stats -->
<div class="stats-grid">

  <div class="stat-card">

    <div class="stat-header">
      <div class="stat-icon blue">
        <i class="bi bi-box-seam"></i>
      </div>
      <span class="stat-trend up">+12%</span>
    </div>

    <div class="stat-value">
      1,284
    </div>

    <div class="stat-label">
      Total Pengiriman
    </div>

  </div>


  <div class="stat-card">

    <div class="stat-header">
      <div class="stat-icon orange">
        <i class="bi bi-people"></i>
      </div>
      <span class="stat-trend up">+8%</span>
    </div>

    <div class="stat-value">
      856
    </div>

    <div class="stat-label">
      Total Pelanggan
    </div>

  </div>


  <div class="stat-card">

    <div class="stat-header">
      <div class="stat-icon green">
        <i class="bi bi-currency-dollar"></i>
      </div>
      <span class="stat-trend up">+23%</span>
    </div>

    <div class="stat-value">
      Rp 245M
    </div>

    <div class="stat-label">
      Pendapatan Bulan Ini
    </div>

  </div>


  <div class="stat-card">

    <div class="stat-header">
      <div class="stat-icon purple">
        <i class="bi bi-truck"></i>
      </div>
      <span class="stat-trend down">-2%</span>
    </div>

    <div class="stat-value">
      47
    </div>

    <div class="stat-label">
      Pengiriman Aktif
    </div>

  </div>

</div>


<!-- Quick Actions -->
<div class="card" style="margin-bottom:1.5rem;">

  <div class="card-header">
    <h3 class="card-title">Aksi Cepat</h3>
  </div>

  <div class="card-body">

    <div class="quick-actions">

      <button class="quick-action-btn">
        <i class="bi bi-plus-circle"></i>
        <span>Barang Masuk</span>
      </button>

      <button class="quick-action-btn">
        <i class="bi bi-send"></i>
        <span>Barang Keluar</span>
      </button>

      <button class="quick-action-btn">
        <i class="bi bi-receipt"></i>
        <span>Buat Invoice</span>
      </button>

      <button class="quick-action-btn">
        <i class="bi bi-person-plus"></i>
        <span>Pelanggan Baru</span>
      </button>

    </div>

  </div>
</div>


<!-- Content Grid -->
<div class="content-grid">

  <!-- Pengiriman Terbaru -->
  <div class="card">

    <div class="card-header">
      <h3 class="card-title">Pengiriman Terbaru</h3>
      <a href="#" class="card-action">Lihat Semua</a>
    </div>

    <div style="overflow-x:auto;">

      <table class="data-table">

        <thead>
          <tr>
            <th>ID Pengiriman</th>
            <th>Pelanggan</th>
            <th>Tujuan</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>

          <tr>
            <td class="shipment-id">HC-2024-001</td>
            <td>PT Maju Jaya</td>
            <td>Jakarta</td>
            <td>
              <span class="status-badge delivered">
                Terkirim
              </span>
            </td>
          </tr>

          <tr>
            <td class="shipment-id">HC-2024-002</td>
            <td>CV Berkah Abadi</td>
            <td>Surabaya</td>
            <td>
              <span class="status-badge transit">
                Dalam Perjalanan
              </span>
            </td>
          </tr>

          <tr>
            <td class="shipment-id">HC-2024-003</td>
            <td>Toko Sejahtera</td>
            <td>Bandung</td>
            <td>
              <span class="status-badge processing">
                Diproses
              </span>
            </td>
          </tr>

          <tr>
            <td class="shipment-id">HC-2024-004</td>
            <td>PT Global Indo</td>
            <td>Medan</td>
            <td>
              <span class="status-badge pending">
                Menunggu
              </span>
            </td>
          </tr>

        </tbody>

      </table>

    </div>

  </div>


  <!-- Aktivitas Terbaru -->
  <div class="card">

    <div class="card-header">
      <h3 class="card-title">Aktivitas Terbaru</h3>
    </div>

    <div class="card-body">

      <div class="activity-list">

        <div class="activity-item">

          <div class="activity-icon incoming">
            <i class="bi bi-box-arrow-in-down"></i>
          </div>

          <div class="activity-content">
            <div class="activity-title">
              Barang masuk dari Supplier A
            </div>

            <div class="activity-time">
              5 menit yang lalu
            </div>
          </div>

        </div>


        <div class="activity-item">

          <div class="activity-icon outgoing">
            <i class="bi bi-box-arrow-up"></i>
          </div>

          <div class="activity-content">
            <div class="activity-title">
              Pengiriman HC-2024-002 berangkat
            </div>

            <div class="activity-time">
              15 menit yang lalu
            </div>
          </div>

        </div>


        <div class="activity-item">

          <div class="activity-icon invoice">
            <i class="bi bi-receipt"></i>
          </div>

          <div class="activity-content">
            <div class="activity-title">
              Invoice #INV-001 dibuat
            </div>

            <div class="activity-time">
              1 jam yang lalu
            </div>
          </div>

        </div>


        <div class="activity-item">

          <div class="activity-icon alert">
            <i class="bi bi-exclamation-triangle"></i>
          </div>

          <div class="activity-content">
            <div class="activity-title">
              Stok barang ABC rendah
            </div>

            <div class="activity-time">
              2 jam yang lalu
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<?= $this->endSection() ?>