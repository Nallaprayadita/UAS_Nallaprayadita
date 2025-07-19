<?php
include '../config/database.php';
include '../layouts/header.php';

$method_name = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method_name = trim($_POST['method_name']);

    if (empty($method_name)) {
        $error = 'Nama metode pembayaran wajib diisi.';
    } else {
        $stmt = $conn->prepare("INSERT INTO payment_methods (method_name) VALUES (?)");
        $stmt->bind_param("s", $method_name);
        if ($stmt->execute()) {
            $success = 'Metode pembayaran berhasil ditambahkan.';
            $method_name = '';
        } else {
            $error = 'Gagal menambahkan metode pembayaran.';
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Tambah Metode Pembayaran</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="method_name" class="form-label">Nama Metode Pembayaran</label>
            <input type="text" name="method_name" id="method_name" class="form-control" value="<?= htmlspecialchars($method_name) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
