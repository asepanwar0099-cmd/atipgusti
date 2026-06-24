<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-d');

$stmt = $pdo->prepare(
    'SELECT a.*, d.nama, d.nidn FROM absensi a JOIN dosen d ON a.dosen_id = d.id WHERE a.tanggal BETWEEN :start AND :end ORDER BY a.tanggal DESC, a.jam_masuk DESC'
);
$stmt->execute(['start' => $start, 'end' => $end]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Laporan Absensi</h2>
        <p class="text-muted">Riwayat absensi dosen berdasarkan rentang tanggal.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-light" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Cetak PDF</button>
        <a href="export_laporan.php?start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>" class="btn btn-primary"><i class="fa-solid fa-file-csv me-2"></i>Ekspor Excel</a>
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
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr><td colspan="6" class="text-center py-4">Tidak ada data absensi di periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($records as $index => $row): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <a class="link-info fw-semibold" style="text-decoration:none;"
                                   href="dosen_absensi_detail.php?dosen_id=<?php echo urlencode($row['id']); ?>&start=<?php echo urlencode($start); ?>&end=<?php echo urlencode($end); ?>">
                                    <?php echo htmlspecialchars($row['nama']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($row['nidn']); ?></td>
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
