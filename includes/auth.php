<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang dapat mengakses halaman dashboard.
if (empty(
    isset($_SESSION['admin_logged']) ? $_SESSION['admin_logged'] : false
)) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Administrator';
