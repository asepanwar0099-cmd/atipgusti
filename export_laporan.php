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

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_absensi_' . $start . '_sampai_' . $end . '.xls');
// Menggunakan format HTML agar dapat dibuka sebagai file Excel sederhana.

echo "<table border=1><tr><th>#</th><th>Nama Dosen</th><th>NIDN</th><th>Tanggal</th><th>Jam Masuk</th><th>Status</th></tr>";
foreach ($records as $index => $row) {
    echo '<tr>';
    echo '<td>' . ($index + 1) . '</td>';
    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nidn']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tanggal']) . '</td>';
    echo '<td>' . htmlspecialchars($row['jam_masuk']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '</tr>';
}
echo '</table>';
