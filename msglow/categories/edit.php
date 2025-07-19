<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/database.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM categories WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $update = "UPDATE categories SET name='$name' WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate kategori.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori - MS Glow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Edit Kategori</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required>
        </div>
        <button name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
