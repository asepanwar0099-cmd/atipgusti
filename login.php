<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$error = '';
// Proses login admin dengan hashing SHA-256 yang sesuai dengan default admin di db.sql.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Masukkan username dan password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admin WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && hash('sha256', $password) === $admin['password']) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_name'] = $admin['nama'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Absensi Dosen QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<div class="card auth-card text-white shadow-lg">
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="brand-badge mb-3">
                <i class="fa-solid fa-qrcode fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Selamat Datang</h3>
            <p class="text-muted mb-3">Masuk untuk mengelola absensi dosen berbasis QR Code.</p>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2">&#x26A0; <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['registered']) && $_GET['registered'] === '1'): ?>
            <div class="alert alert-success py-2">Akun berhasil dibuat. Silakan masuk.</div>
        <?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <div class="input-group input-group-lg">
                    <input type="password" id="passwordInput" name="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-light password-toggle" onclick="togglePassword()">
                        <i class="fa-solid fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
            </div>
        </form>
        <div class="divider"></div>
        <div class="text-center">
            <span class="text-muted">Belum punya akun?</span>
            <a href="register.php" class="auth-link fw-semibold">Daftar sekarang</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('passwordIcon');
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
