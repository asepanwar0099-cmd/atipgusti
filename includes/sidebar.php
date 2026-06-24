<?php
$current = basename($_SERVER['PHP_SELF']);
function navItem($page, $label, $icon) {
    global $current;
    $active = $current === $page ? 'active' : '';
    echo "<a class=\"nav-link text-white mb-2 py-2 rounded $active\" href=\"$page\"><i class=\"fa-solid $icon me-2\"></i>$label</a>";
}
?>
<aside class="sidebar p-3 shadow-sm">
    <div class="sidebar-brand text-center mb-4">
        <div class="avatar bg-white text-primary rounded-circle mx-auto mb-2">
            <i class="fa-solid fa-user-tie fa-2x"></i>
        </div>
        <h6 class="mb-0 text-white">Admin Panel</h6>
    </div>
    <nav class="nav flex-column">
        <?php
        navItem('dashboard.php', 'Beranda', 'fa-house');
        navItem('dosen.php', 'Data Dosen', 'fa-users');
        navItem('qr_generator.php', 'QR Code Generator', 'fa-qrcode');
        navItem('scan_absensi.php', 'Scan Absensi', 'fa-camera');
        navItem('laporan.php', 'Laporan Absensi', 'fa-file-lines');
        ?>
    </nav>
</aside>
<main class="content flex-grow-1 p-4">
