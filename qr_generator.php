<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/lib/phpqrcode.php';

$qrImage = '';
$selectedDosen = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nidn = trim($_POST['nidn'] ?? '');
    $stmt = $pdo->prepare('SELECT * FROM dosen WHERE nidn = :nidn LIMIT 1');
    $stmt->execute(['nidn' => $nidn]);
    $selectedDosen = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($selectedDosen) {
        $outputDir = __DIR__ . '/qrcodes';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        $filename = $outputDir . '/' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $selectedDosen['nidn']) . '.png';
        QRcode::png($selectedDosen['nidn'], $filename, QR_ECLEVEL_L, 6, 2);
        $qrImage = 'qrcodes/' . basename($filename);
    }
}

$stmt = $pdo->query('SELECT nidn, nama FROM dosen ORDER BY nama ASC');
$allDosen = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">QR Code Generator</h2>
        <p class="text-muted">Buat QR Code unik untuk setiap NIDN dosen.</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-glass p-4 border-0">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Pilih Dosen</label>
                    <select name="nidn" class="form-select" required>
                        <option value="">Pilih salah satu</option>
                        <?php foreach ($allDosen as $dosen): ?>
                            <option value="<?php echo htmlspecialchars($dosen['nidn']); ?>" <?php echo isset($_POST['nidn']) && $_POST['nidn'] === $dosen['nidn'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dosen['nama'] . ' (' . $dosen['nidn'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Generate QR</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card card-glass p-4 border-0 text-center">
            <?php if ($selectedDosen): ?>
                <h5 class="mb-3">QR Code untuk <?php echo htmlspecialchars($selectedDosen['nama']); ?></h5>
                <img src="<?php echo htmlspecialchars($qrImage); ?>" alt="QR Code" class="img-fluid mb-3" style="max-width:260px;">
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo htmlspecialchars($qrImage); ?>" class="btn btn-outline-light" download="QR_<?php echo htmlspecialchars($selectedDosen['nidn']); ?>.png">
                        <i class="fa-solid fa-download me-2"></i>Download QR
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa-solid fa-print me-2"></i>Cetak QR
                    </button>
                </div>
            <?php else: ?>
                <div class="py-5">
                    <i class="fa-solid fa-qrcode fa-3x mb-3 text-muted"></i>
                    <p class="text-muted mb-0">Pilih dosen untuk membuat QR Code.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
