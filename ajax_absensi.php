<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$nidn = trim($_POST['nidn'] ?? '');
if ($nidn === '') {
    echo json_encode(['success' => false, 'message' => 'Data QR Code tidak valid.']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, nama FROM dosen WHERE nidn = :nidn LIMIT 1');
$stmt->execute(['nidn' => $nidn]);
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);
// Pastikan QR kode valid dan terdaftar di database.
if (!$dosen) {
    echo json_encode(['success' => false, 'message' => 'Dosen tidak ditemukan.']);
    exit;
}

$tanggal = date('Y-m-d');
$jam = date('H:i:s');
$status = $jam > '07:30:00' ? 'Terlambat' : 'Hadir';

$stmt = $pdo->prepare('SELECT COUNT(*) FROM absensi WHERE dosen_id = :dosen_id AND tanggal = :tanggal');
stmt->execute(['dosen_id' => $dosen['id'], 'tanggal' => $tanggal]);
$exist = $stmt->fetchColumn();
if ($exist) {
    echo json_encode(['success' => false, 'message' => 'Absensi sudah tercatat untuk hari ini.']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO absensi (dosen_id, tanggal, jam_masuk, status) VALUES (:dosen_id, :tanggal, :jam_masuk, :status)');
$stmt->execute(['dosen_id' => $dosen['id'], 'tanggal' => $tanggal, 'jam_masuk' => $jam, 'status' => $status]);

$message = "Absensi berhasil tercatat untuk {$dosen['nama']} ({$status}).";
echo json_encode(['success' => true, 'message' => $message]);
