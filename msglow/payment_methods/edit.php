<?php
include '../config/database.php';
include '../layouts/header.php';

$id = $_GET['id'] ?? '';
$method_name = '';
$error = '';
$success = '';

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data metode berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM payment_methods WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
$stmt->close();

if (!$payment) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    include '../layouts/footer.php';
    exit;
}

$method_name = $payment['method_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method_name = trim($_POST['method_name']);

    if (empty($method_name)) {
        $error = 'Nama metode pembayaran wajib diisi.';
    } else {
        $stmt = $conn->prepare("UPDATE payment_methods SET method_name = ? WHERE id = ?");
        $stmt->bind_param("si", $method_name, $id);
        if ($stmt->execute()) {
            $success = 'Data berhasil diperbarui.';
        } else {
            $error = 'Gagal memperbarui data.';
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Edit Metode Pembayaran</h2>

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
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
