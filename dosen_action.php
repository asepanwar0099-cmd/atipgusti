<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nidn = trim($_POST['nidn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id = intval($_POST['id'] ?? 0);
    // Gunakan prepared statement untuk mencegah SQL injection.

    if ($action === 'add') {
        $stmt = $pdo->prepare('INSERT INTO dosen (nidn, nama, kontak, email) VALUES (:nidn, :nama, :kontak, :email)');
        $stmt->execute(['nidn' => $nidn, 'nama' => $nama, 'kontak' => $kontak, 'email' => $email]);
    }

    if ($action === 'edit' && $id > 0) {
        $stmt = $pdo->prepare('UPDATE dosen SET nidn = :nidn, nama = :nama, kontak = :kontak, email = :email WHERE id = :id');
        $stmt->execute(['nidn' => $nidn, 'nama' => $nama, 'kontak' => $kontak, 'email' => $email, 'id' => $id]);
    }

    header('Location: dosen.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare('DELETE FROM dosen WHERE id = :id');
    $stmt->execute(['id' => $id]);
    header('Location: dosen.php');
    exit;
}

header('Location: dosen.php');
exit;
