<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-d');

$stmt = $pdo->prepare(
    'SELECT a.dosen_id, d.nama, d.nidn, COUNT(*) AS terlambat_count
     FROM absensi a
     JOIN dosen d ON a.dosen_id = d.id
     WHERE a.tanggal BETWEEN :start AND :end
       AND a.status = :status
     GROUP BY a.dosen_id, d.nama, d.nidn
     ORDER BY terlambat_count DESC, d.nama ASC'
);
$stmt->execute([
    'start' => $start,
    'end' => $end,
    'status' => 'Terlambat'
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Rekap Terlambat per Dosen</h2>
        <p class="text-muted">Periode: <?php echo htmlspecialchars($start); ?> s/d <?php echo htmlspecialchars($end); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="laporan.php?start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>" class="btn btn-outline-light">
            <i class="fa-solid fa-list me-2"></i>Laporan Absensi
        </a>
    </div>
</div>

<div class="card card-glass p-4 border-0 mb-4">
    <form class="row g-3 align-items-end" method="get">
        <div class="col-md-4">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start" class="form-control" value="<?php echo htmlspecialchars($start); ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end" class="form-control" value="<?php echo htmlspecialchars($end); ?>" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card card-glass border-0">
    <div class="table-responsive">
        <table class="table table-dark table-striped align-middle mb-0">
            <thead class="table-secondary text-dark">
            <tr>
                <th>#</th>
                <th>Nama Dosen</th>
                <th>NIDN</th>
                <th>Total Terlambat</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="5" class="text-center py-4">Tidak ada data terlambat pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['nidn']); ?></td>
                        <td><span class="badge bg-warning text-dark"><?php echo (int)$row['terlambat_count']; ?></span></td>
                        <td>
                            <a class="btn btn-sm btn-outline-light"
                               href="dosen_absensi_detail.php?dosen_id=<?php echo urlencode($row['dosen_id']); ?>&start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>">
                                <i class="fa-solid fa-eye me-1"></i>Lihat Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
