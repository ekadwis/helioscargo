<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 9px; color: #000; width: 226px; }

    .header { background: #1e3a5f; color: #fff; text-align: center; padding: 8px 6px; }
    .header-brand { font-size: 16px; font-weight: 900; letter-spacing: 1px; }
    .header-tagline { font-size: 7px; opacity: 0.8; margin-top: 2px; }

    .awb-section { text-align: center; padding: 8px 6px; border-bottom: 2px dashed #ccc; }
    .awb-label { font-size: 7px; color: #666; text-transform: uppercase; letter-spacing: 1px; }
    .awb-number { font-size: 14px; font-weight: 900; letter-spacing: 2px; margin: 4px 0; }
    .barcode-wrap { margin: 4px 0; }
    .service-badge { display: inline-block; background: #f97316; color: #fff; font-size: 8px; font-weight: 700; padding: 3px 10px; border-radius: 4px; margin-top: 3px; }

    .section { padding: 6px; border-bottom: 1px solid #eee; }
    .section-title { font-size: 7px; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 4px; font-weight: 700; }
    .name { font-size: 11px; font-weight: 700; color: #1e3a5f; }
    .address { font-size: 8px; color: #333; line-height: 1.4; margin-top: 2px; }
    .phone { font-size: 8px; color: #666; margin-top: 1px; }

    .item-section { padding: 6px; border-bottom: 2px dashed #ccc; }
    .item-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
    .item-label { color: #666; }
    .item-value { font-weight: 700; }

    .payment-section { padding: 6px; border-bottom: 1px solid #eee; }
    .total-row { display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; }
    .total-label { color: #333; }
    .total-value { color: #1e3a5f; }

    .qr-section { text-align: center; padding: 6px; border-bottom: 1px solid #eee; }
    .qr-label { font-size: 7px; color: #666; margin-bottom: 4px; }

    .footer { text-align: center; padding: 6px; font-size: 7px; color: #999; }
    .fragile-badge { background: #ef4444; color: #fff; font-size: 8px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-top: 3px; }

    .divider { border: none; border-top: 1px dashed #ccc; margin: 0; }
    .text-center { text-align: center; }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-brand">HELIOSCARGO</div>
    <div class="header-tagline">Solusi Pengiriman Cepat & Andal</div>
</div>

<!-- AWB + BARCODE -->
<div class="awb-section">
    <div class="awb-label">Nomor Resi</div>
    <div class="awb-number"><?= $shipment['awb'] ?></div>
    <div class="barcode-wrap"><?= $barcode ?></div>
    <div class="service-badge">
        <?= $shipment['service_name'] ?> &bull; <?= $shipment['sla_days_min'] ?>-<?= $shipment['sla_days_max'] ?> Hari
    </div>
    <?php if ((int)$shipment['is_fragile']) : ?>
        <div class="fragile-badge">⚠ FRAGILE</div>
    <?php endif; ?>
</div>

<!-- PENERIMA -->
<div class="section">
    <div class="section-title">Kepada / Penerima</div>
    <div class="name"><?= $shipment['receiver_name'] ?></div>
    <div class="address">
        <?= $shipment['receiver_address'] ?><br>
        <?= $shipment['dest_kel'] ?>, <?= $shipment['dest_kec'] ?><br>
        <?= $shipment['dest_kab'] ?>, <?= $shipment['dest_prov'] ?>
        <?= $shipment['dest_kodepos'] ? ' - ' . $shipment['dest_kodepos'] : '' ?>
    </div>
    <div class="phone">📞 <?= $shipment['receiver_phone'] ?></div>
</div>

<!-- PENGIRIM -->
<div class="section">
    <div class="section-title">Dari / Pengirim</div>
    <div class="name"><?= $shipment['sender_name'] ?></div>
    <div class="address">
        <?= $shipment['sender_address'] ?><br>
        <?= $shipment['origin_kel'] ?>, <?= $shipment['origin_kec'] ?><br>
        <?= $shipment['origin_kab'] ?>, <?= $shipment['origin_prov'] ?>
    </div>
    <div class="phone">📞 <?= $shipment['sender_phone'] ?></div>
</div>

<!-- DETAIL BARANG -->
<div class="item-section">
    <div class="section-title">Detail Barang</div>
    <div class="item-row">
        <span class="item-label">Barang</span>
        <span class="item-value"><?= $shipment['item_name'] ?></span>
    </div>
    <div class="item-row">
        <span class="item-label">Qty</span>
        <span class="item-value"><?= $shipment['qty'] ?> pcs</span>
    </div>
    <div class="item-row">
        <span class="item-label">Berat</span>
        <span class="item-value"><?= number_format((float)$shipment['weight_kg'], 2) ?> kg</span>
    </div>
    <div class="item-row">
        <span class="item-label">Dimensi</span>
        <span class="item-value">
            <?= (int)$shipment['length_cm'] ?>x<?= (int)$shipment['width_cm'] ?>x<?= (int)$shipment['height_cm'] ?> cm
        </span>
    </div>
    <?php if ($shipment['estimated_delivery_date']) : ?>
    <div class="item-row">
        <span class="item-label">Est. Tiba</span>
        <span class="item-value"><?= date('d M Y', strtotime($shipment['estimated_delivery_date'])) ?></span>
    </div>
    <?php endif; ?>
</div>

<!-- PEMBAYARAN -->
<div class="payment-section">
    <div class="section-title">Pembayaran</div>
    <div class="item-row" style="font-size:8px;">
        <span class="item-label">Ongkir</span>
        <span>Rp <?= number_format((float)$shipment['shipping_fee'], 0, ',', '.') ?></span>
    </div>
    <div class="item-row" style="font-size:8px;">
        <span class="item-label">Asuransi</span>
        <span>Rp <?= number_format((float)$shipment['insurance_fee'], 0, ',', '.') ?></span>
    </div>
    <hr class="divider" style="margin:4px 0;">
    <div class="total-row">
        <span class="total-label">TOTAL</span>
        <span class="total-value">Rp <?= number_format((float)$shipment['total_amount'], 0, ',', '.') ?></span>
    </div>
    <div style="text-align:right;margin-top:2px;">
        <span style="font-size:7px;background:<?= $shipment['payment_status'] === 'paid' ? '#dcfce7' : ($shipment['payment_status'] === 'cod' ? '#fef3c7' : '#fee2e2') ?>;
            color:<?= $shipment['payment_status'] === 'paid' ? '#166534' : ($shipment['payment_status'] === 'cod' ? '#92400e' : '#991b1b') ?>;
            padding:2px 6px;border-radius:4px;font-weight:700;">
            <?= strtoupper($shipment['payment_status']) ?>
        </span>
    </div>
</div>

<!-- QR CODE -->
<div class="qr-section">
    <div class="qr-label">Scan untuk lacak kiriman</div>
    <img src="<?= $qrUrl ?>" width="80" height="80" alt="QR Tracking">
    <div style="font-size:7px;color:#94a3b8;margin-top:2px;">
        Track: <?= base_url('tracking/' . $shipment['awb']) ?>
    </div>
</div>

<!-- INFO OUTLET -->
<div class="section text-center">
    <div style="font-size:7px;color:#666;">
        Dikirim dari <strong><?= $shipment['outlet_name'] ?></strong><br>
        <?= date('d M Y H:i', strtotime($shipment['created_at'])) ?> WIB
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    Terima kasih telah menggunakan HELIOSCARGO<br>
    Pertanyaan? Hubungi kami di info@helioscargo.com
</div>

</body>
</html>