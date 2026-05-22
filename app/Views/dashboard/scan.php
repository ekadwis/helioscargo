<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="page-content active">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Header -->
            <div class="card mb-3" style="background:linear-gradient(135deg,#1e3a5f,#2d4f7f);border:none;">
                <div class="card-body d-flex align-items-center justify-content-between" style="color:#fff;">
                    <div>
                        <h4 class="mb-1 fw-bold">
                            <i class="bi bi-upc-scan me-2"></i>Scan Center
                        </h4>
                        <p class="mb-0" style="opacity:0.75;font-size:0.9rem;">
                            Outlet: <strong><?= session()->get('full_name') ?></strong>
                        </p>
                    </div>
                    <div style="font-size:2.5rem;opacity:0.2;">
                        <i class="bi bi-upc"></i>
                    </div>
                </div>
            </div>

            <!-- Mode Selector -->
            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label fw-bold">Mode Scan</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-mode btn-outline-primary active-mode"
                            data-mode="manifest_in">
                            <i class="bi bi-box-arrow-in-down me-1"></i>
                            Manifest Masuk
                        </button>
                        <button type="button" class="btn btn-mode btn-outline-warning"
                            data-mode="manifest_out">
                            <i class="bi bi-box-arrow-up me-1"></i>
                            Manifest Keluar
                        </button>
                        <button type="button" class="btn btn-mode btn-outline-success"
                            data-mode="awb">
                            <i class="bi bi-box-seam me-1"></i>
                            Scan AWB
                        </button>
                    </div>

                    <!-- Info mode -->
                    <div class="mt-2 p-2 rounded" id="modeInfo"
                        style="background:#eff6ff;border:1px solid #bfdbfe;font-size:0.85rem;color:#1e40af;">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="modeInfoText">Scan barcode manifest untuk mencatat kedatangan di outlet ini.</span>
                    </div>
                </div>
            </div>

            <!-- Status AWB (hanya muncul di mode AWB) -->
            <div class="card mb-3" id="awbStatusCard" style="display:none;">
                <div class="card-body">
                    <label class="form-label fw-bold">Status Update ke</label>
                    <select id="awbStatusSelect" class="form-control">
                        <option value="arrived_at_hub">Tiba di Hub</option>
                        <option value="arrived_at_destination">Tiba di Outlet Tujuan</option>
                        <option value="out_for_delivery">Dibawa Kurir</option>
                        <option value="delivered">Terkirim ke Penerima</option>
                        <option value="failed_delivery">Gagal Terkirim</option>
                        <option value="returned">Dikembalikan</option>
                    </select>
                </div>
            </div>

            <!-- Input Scan -->
            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label fw-bold" id="scanLabel">
                        Scan Barcode Manifest
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#1e3a5f;color:#fff;">
                            <i class="bi bi-upc-scan"></i>
                        </span>
                        <input type="text" id="scanInput" class="form-control form-control-lg"
                            placeholder="Arahkan scanner ke barcode..."
                            autocomplete="off" autofocus>
                        <button type="button" class="btn btn-primary" id="btnManualScan">
                            Proses
                        </button>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="bi bi-lightning-charge me-1"></i>
                        Scanner hardware akan otomatis memproses setelah scan.
                    </small>
                </div>
            </div>

            <!-- Log Hasil Scan -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-1"></i>Log Scan
                        <span class="badge bg-primary ms-1" id="scanCount">0</span>
                    </h5>
                    <button type="button" class="btn btn-sm btn-light-secondary" id="btnClearLog">
                        <i class="bi bi-trash me-1"></i>Clear
                    </button>
                </div>
                <div class="card-body p-0" style="max-height:400px;overflow-y:auto;">
                    <div id="scanLog">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-upc fs-2 d-block mb-2"></i>
                            Belum ada scan. Mulai scan barcode.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {

    let currentMode = 'manifest_in';
    let scanCount   = 0;

    const modeInfo = {
        manifest_in:  'Scan barcode manifest untuk mencatat kedatangan paket di outlet ini.',
        manifest_out: 'Scan barcode manifest untuk mencatat keberangkatan ke tujuan.',
        awb:          'Scan barcode AWB untuk update status paket individual.',
    };

    const modeLabels = {
        manifest_in:  'Scan Barcode Manifest (Masuk)',
        manifest_out: 'Scan Barcode Manifest (Keluar)',
        awb:          'Scan Barcode AWB',
    };

    // Selalu focus ke input scan
    function refocus() {
        setTimeout(() => $('#scanInput').focus(), 100);
    }
    refocus();

    // Klik di mana saja → focus ke input
    $(document).on('click', function(e) {
        if (!$(e.target).is('button, select, a')) {
            refocus();
        }
    });

    // Ganti mode
    $('.btn-mode').on('click', function() {
        currentMode = $(this).data('mode');

        $('.btn-mode').removeClass('active-mode')
            .removeClass('btn-primary btn-warning btn-success')
            .addClass(function() {
                const m = $(this).data('mode');
                return m === 'manifest_in' ? 'btn-outline-primary' :
                       m === 'manifest_out' ? 'btn-outline-warning' : 'btn-outline-success';
            });

        $(this).addClass('active-mode')
            .removeClass('btn-outline-primary btn-outline-warning btn-outline-success')
            .addClass(currentMode === 'manifest_in' ? 'btn-primary' :
                      currentMode === 'manifest_out' ? 'btn-warning' : 'btn-success');

        $('#modeInfoText').text(modeInfo[currentMode]);
        $('#scanLabel').text(modeLabels[currentMode]);
        $('#awbStatusCard').toggle(currentMode === 'awb');
        $('#scanInput').val('').attr('placeholder',
            currentMode === 'awb' ? 'Scan AWB...' : 'Scan Manifest...'
        );

        refocus();
    });

    // Proses scan — trigger saat Enter (otomatis dari scanner hardware)
    $('#scanInput').on('keypress', function(e) {
        if (e.which === 13) {
            processScan();
        }
    });

    $('#btnManualScan').on('click', processScan);

    function processScan() {
        const barcode = $('#scanInput').val().trim();
        if (!barcode) return;

        const data = {
            mode:    currentMode,
            barcode: barcode,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>',
        };

        if (currentMode === 'awb') {
            data.awb_status = $('#awbStatusSelect').val();
        }

        // Disable input sementara
        $('#scanInput').prop('disabled', true);

        $.ajax({
            url: '/scan/process',
            method: 'POST',
            data: data,
            success: function(res) {
                addLog(res, barcode);
                $('#scanInput').val('').prop('disabled', false);
                refocus();
            },
            error: function() {
                addLog({ success: false, message: 'Koneksi error, coba lagi.' }, barcode);
                $('#scanInput').val('').prop('disabled', false);
                refocus();
            }
        });
    }

    function addLog(res, barcode) {
        scanCount++;
        $('#scanCount').text(scanCount);

        const time    = new Date().toLocaleTimeString('id-ID');
        const bgColor = res.success ? '#f0fdf4' : '#fef2f2';
        const border  = res.success ? '#bbf7d0' : '#fecaca';
        const icon    = res.success ? '✅' : '❌';

        // Hapus placeholder kalau ada
        if ($('#scanLog .text-center').length) {
            $('#scanLog').empty();
        }

        const logItem = `
            <div class="px-3 py-2 border-bottom d-flex align-items-start gap-3"
                style="background:${bgColor};border-left:4px solid ${border} !important;animation:fadeIn .3s ease;">
                <div style="font-size:1.2rem;margin-top:2px;">${icon}</div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;color:#94a3b8;">${time}</div>
                    <div style="font-size:0.9rem;">${res.message}</div>
                    ${res.success && res.mode === 'awb' && res.shipment ? `
                        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">
                            ${res.shipment.item_name}
                        </div>
                    ` : ''}
                    ${res.success && res.count ? `
                        <div style="font-size:0.8rem;color:#64748b;">${res.count} shipment diupdate</div>
                    ` : ''}
                </div>
            </div>
        `;

        // Tambah di atas
        $('#scanLog').prepend(logItem);
    }

    $('#btnClearLog').on('click', function() {
        scanCount = 0;
        $('#scanCount').text(0);
        $('#scanLog').html(`
            <div class="text-center text-muted py-5">
                <i class="bi bi-upc fs-2 d-block mb-2"></i>
                Belum ada scan. Mulai scan barcode.
            </div>
        `);
        refocus();
    });
});
</script>

<style>
.active-mode { font-weight: 600; }
@keyframes fadeIn { from { opacity:0; transform:translateX(-10px); } to { opacity:1; transform:translateX(0); } }
</style>
<?= $this->endSection() ?>