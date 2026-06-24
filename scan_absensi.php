<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Scan Absensi</h2>
        <p class="text-muted">Arahkan kamera ke QR Code dosen untuk mencatat absensi.</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-glass p-4 border-0">
            <div id="reader" class="rounded-4 overflow-hidden" style="min-height: 420px;"></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-glass p-4 border-0">
            <h5 class="mb-3">Hasil Scan</h5>
            <div id="scanResult" class="alert alert-dark">Menunggu QR Code...</div>
            <p class="text-muted">Sistem akan mencatat absensi secara otomatis segera setelah QR Code terdeteksi.</p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
<script>
const resultBox = document.getElementById('scanResult');
const html5QrcodeScanner = new Html5Qrcode('reader');

function setResult(message, type = 'info') {
    const classes = {
        info: 'alert-dark',
        success: 'alert-success',
        warning: 'alert-warning',
        error: 'alert-danger'
    };
    resultBox.className = 'alert ' + (classes[type] || 'alert-dark');
    resultBox.textContent = message;
}

function onScanSuccess(decodedText) {
    setResult('Memproses: ' + decodedText, 'info');
    fetch('ajax_absensi.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'nidn=' + encodeURIComponent(decodedText)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setResult(data.message, 'success');
                Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', timer: 2200, showConfirmButton: false });
            } else {
                setResult(data.message, 'warning');
                Swal.fire({ icon: 'warning', title: data.message, toast: true, position: 'top-end', timer: 2200, showConfirmButton: false });
            }
            setTimeout(() => html5QrcodeScanner.resume(), 1500);
        })
        .catch(() => {
            setResult('Terjadi kesalahan saat mencatat absensi.', 'error');
        });
}

function onScanError(errorMessage) {
    console.debug('Scan error:', errorMessage);
}

Html5Qrcode.getCameras().then(cameras => {
    const cameraId = cameras.length ? cameras[0].id : null;
    if (!cameraId) {
        setResult('Tidak ada kamera yang terdeteksi.', 'error');
        return;
    }
    html5QrcodeScanner.start(
        { deviceId: { exact: cameraId } },
        { fps: 10, qrbox: 300 },
        onScanSuccess,
        onScanError
    ).catch(err => {
        setResult('Gagal mengakses kamera. Pastikan izin sudah diberikan.', 'error');
        console.error(err);
    });
}).catch(err => {
    setResult('Gagal memuat perangkat kamera.', 'error');
    console.error(err);
});
</script>
