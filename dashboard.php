<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query('SELECT COUNT(*) FROM dosen');
$total_dosen = $stmt->fetchColumn();

$today = date('Y-m-d');
$stmt = $pdo->prepare('SELECT COUNT(*) FROM absensi WHERE tanggal = :tanggal');
$stmt->execute(['tanggal' => $today]);
$total_hadir = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM absensi WHERE tanggal = :tanggal AND status = 'Terlambat'");
$stmt->execute(['tanggal' => $today]);
$total_terlambat = $stmt->fetchColumn();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-muted">Ringkasan absensi harian dan statistik dosen.</p>
    </div>
    <div class="clock-display text-end text-info" id="liveClock">--:--:--</div>
</div>
<div class="row g-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-glass p-3 h-100 border-0">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-uppercase text-muted mb-1">Total Dosen</h6>
                    <h2 class="mb-0"><?php echo number_format($total_dosen); ?></h2>
                </div>
                <i class="fa-solid fa-user-tie fa-2x text-primary"></i>
            </div>
            <p class="text-muted small">Jumlah dosen terdaftar dalam sistem.</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-glass p-3 h-100 border-0">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-uppercase text-muted mb-1">Hadir Hari Ini</h6>
                    <h2 class="mb-0"><?php echo number_format($total_hadir); ?></h2>
                </div>
                <i class="fa-solid fa-calendar-check fa-2x text-success"></i>
            </div>
            <p class="text-muted small">Rekap absensi dosen untuk hari ini.</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-glass p-3 h-100 border-0">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-uppercase text-muted mb-1">Terlambat Hari Ini</h6>
                    <h2 class="mb-0"><?php echo number_format($total_terlambat); ?></h2>
                </div>
                <i class="fa-solid fa-clock fa-2x text-warning"></i>
            </div>
            <p class="text-muted small">Jumlah dosen yang tercatat terlambat.</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-glass p-3 h-100 border-0">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-uppercase text-muted mb-1">Status Sistem</h6>
                    <h2 class="mb-0">Online</h2>
                </div>
                <i class="fa-solid fa-server fa-2x text-info"></i>
            </div>
            <p class="text-muted small">Sistem absensi siap digunakan.</p>
        </div>
    </div>
</div>

<div class="card card-glass mt-4 p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Ringkasan Hari Ini</h5>
            <p class="text-muted mb-0">Lihat statistik real-time absensi dosen.</p>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 bg-translucent rounded-3">
                <h6 class="text-muted">Dosen Terdaftar</h6>
                <strong><?php echo number_format($total_dosen); ?></strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-translucent rounded-3">
                <h6 class="text-muted">Absensi Hari Ini</h6>
                <strong><?php echo number_format($total_hadir); ?></strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-translucent rounded-3">
                <h6 class="text-muted">Terlambat</h6>
                <strong><?php echo number_format($total_terlambat); ?></strong>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script>
function updateClock() {
    const date = new Date();
    const timeString = date.toLocaleTimeString('id-ID', { hour12: false });
    document.getElementById('liveClock').textContent = timeString;
}
updateClock();
setInterval(updateClock, 1000);
</script>
