# Absensi Dosen Berbasis QR Code

Aplikasi absensi dosen berbasis QR Code dengan PHP Native, MySQL, Bootstrap 5, dan html5-qrcode.

## Instalasi
1. Impor `db.sql` ke MySQL.
2. Sesuaikan kredensial di `includes/config.php`.
3. Tempatkan file di folder server web, lalu akses `login.php`.
4. Default admin: `admin` / `Admin123!`.

## Struktur File
- `login.php`, `logout.php`
- `dashboard.php`, `dosen.php`, `qr_generator.php`, `scan_absensi.php`, `laporan.php`
- `includes/` untuk koneksi dan layout bersama
- `lib/phpqrcode.php` untuk generate QR Code
- `qrcodes/` sebagai output QR code
