<?php
include '../config/database.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = mysqli_query($conn, "INSERT INTO products (nama, harga, stok) VALUES ('$nama', '$harga', '$stok')");
    
    if ($query) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menyimpan data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffe4ec;
        }
        .container {
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #ff69b4;
            border: none;
        }
        .btn-primary:hover {
            background-color: #ff1493;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Tambah Produk</h2>
    <?php if (isset(\$error)) echo "<div class='alert alert-danger'>\$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Produk</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" name="stok" id="stok" class="form-control" required>
        </div>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
