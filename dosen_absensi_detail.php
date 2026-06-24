<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$dosen_id = intval($_GET['dosen_id'] ?? 0);
$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-d');

if ($dosen_id <= 0) {
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/sidebar.php';
    ?>
    <div class="alert alert-danger mt-4">dosen_id tidak valid.</div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT id, nama, nidn FROM dosen WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $dosen_id]);
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare(
    'SELECT a.tanggal, a.jam_masuk, a.status
     FROM absensi a
     WHERE a.dosen_id = :dosen_id
       AND a.tanggal BETWEEN :start AND :end
     ORDER BY a.tanggal DESC, a.jam_masuk DESC'
);
$stmt->execute([
    'dosen_id' => $dosen_id,
    'start' => $start,
    'end' => $end
]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detail Absensi Dosen</h2>
        <p class="text-muted mb-0">
            <?php if ($dosen): ?>
                <?php echo htmlspecialchars($dosen['nama']); ?> (<?php echo htmlspecialchars($dosen['nidn']); ?>)
            <?php else: ?>
                Dosen tidak ditemukan
            <?php endif; ?>
            - Periode: <?php echo htmlspecialchars($start); ?> s/d <?php echo htmlspecialchars($end); ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-light"
           href="laporan.php?start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Laporan
        </a>
        <a class="btn btn-outline-light"
           href="terlambat_per_dosen.php?start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>">
            <i class="fa-solid fa-clock me-2"></i>Kembali ke Rekap Terlambat
        </a>
    </div>
</div>

<div class="card card-glass border-0">
    <div class="table-responsive">
        <table class="table table-dark table-striped align-middle mb-0">
            <thead class="table-secondary text-dark">
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($records)): ?>
                <tr><td colspan="4" class="text-center py-4">Tidak ada data absensi pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach ($records as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($row['jam_masuk']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Hadir'): ?>
                                <span class="badge bg-success">Hadir</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Terlambat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
