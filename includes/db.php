<?php
require_once __DIR__ . '/config.php';

// Buat koneksi PDO ke MySQL dengan charset UTF-8 dan exception mode.
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // Jika gagal, hentikan eksekusi dengan pesan yang jelas.
    die('Koneksi database gagal: ' . $e->getMessage());
}
