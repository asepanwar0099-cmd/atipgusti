<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query('SELECT * FROM dosen ORDER BY nama ASC');
$dosenList = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Data Dosen</h2>
        <p class="text-muted">Kelola informasi dosen dan QR code absensi.</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#dosenModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-2"></i>Tambah Dosen
    </button>
</div>
<div class="card card-glass border-0">
    <div class="card-body p-0 overflow-auto">
        <table class="table table-dark table-striped align-middle mb-0">
            <thead class="table-secondary text-dark">
                <tr>
                    <th>#</th>
                    <th>NIDN / ID</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dosenList)): ?>
                    <tr><td colspan="6" class="text-center py-4">Belum ada data dosen.</td></tr>
                <?php else: ?>
                    <?php foreach ($dosenList as $index => $dosen): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($dosen['nidn']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['nama']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['kontak']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['email']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-light me-1" onclick="editDosen(<?php echo $dosen['id']; ?>, '<?php echo htmlspecialchars(addslashes($dosen['nidn'])); ?>', '<?php echo htmlspecialchars(addslashes($dosen['nama'])); ?>', '<?php echo htmlspecialchars(addslashes($dosen['kontak'])); ?>', '<?php echo htmlspecialchars(addslashes($dosen['email'])); ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="dosen_action.php?delete=<?php echo $dosen['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dosen ini?');">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="dosenModal" tabindex="-1" aria-labelledby="dosenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="dosenModalLabel">Tambah Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="dosen_action.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="dosenId">
                    <input type="hidden" name="action" id="dosenAction" value="add">
                    <div class="mb-3">
                        <label class="form-label">NIDN / ID</label>
                        <input type="text" name="nidn" id="nidn" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="kontak" id="kontak" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script>
function resetForm() {
    document.getElementById('dosenModalLabel').textContent = 'Tambah Dosen';
    document.getElementById('dosenAction').value = 'add';
    document.getElementById('dosenId').value = '';
    document.getElementById('nidn').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('kontak').value = '';
    document.getElementById('email').value = '';
}

function editDosen(id, nidn, nama, kontak, email) {
    document.getElementById('dosenModalLabel').textContent = 'Edit Dosen';
    document.getElementById('dosenAction').value = 'edit';
    document.getElementById('dosenId').value = id;
    document.getElementById('nidn').value = nidn;
    document.getElementById('nama').value = nama;
    document.getElementById('kontak').value = kontak;
    document.getElementById('email').value = email;
    var modal = new bootstrap.Modal(document.getElementById('dosenModal'));
    modal.show();
}
</script>
