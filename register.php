<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $nama === '' || $confirm === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM admin WHERE username = :username');
        $stmt->execute(['username' => $username]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username sudah digunakan. Pilih username lain.';
        } else {
            $hashed = hash('sha256', $password);
            $stmt = $pdo->prepare('INSERT INTO admin (username, password, nama) VALUES (:username, :password, :nama)');
            $stmt->execute(['username' => $username, 'password' => $hashed, 'nama' => $nama]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Admin - Absensi Dosen QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<div class="card auth-card text-white shadow-lg">
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="brand-badge mb-3">
                <i class="fa-solid fa-user-plus fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Buat Akun Baru</h3>
            <p class="text-muted mb-3">Daftarkan admin baru untuk mengelola sistem absensi.</p>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2">&#x26A0; <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control form-control-lg" value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <div class="input-group input-group-lg">
                    <input type="password" id="passwordInput" name="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-light password-toggle" onclick="togglePassword('passwordInput', 'passwordIcon')">
                        <i class="fa-solid fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group input-group-lg">
                    <input type="password" id="confirmInput" name="confirm_password" class="form-control" required>
                    <button type="button" class="btn btn-outline-light password-toggle" onclick="togglePassword('confirmInput', 'confirmIcon')">
                        <i class="fa-solid fa-eye" id="confirmIcon"></i>
                    </button>
                </div>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Daftar Sekarang</button>
            </div>
        </form>
        <div class="divider"></div>
        <div class="text-center">
            <span class="text-muted">Sudah punya akun?</span>
            <a href="login.php" class="auth-link fw-semibold">Masuk di sini</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    }
</script>
</body>
</html>
