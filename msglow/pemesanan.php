<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ambil ID produk dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID produk tidak valid.'); window.location.href='dashboard.php';</script>";
    exit;
}

$product_id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('ID produk tidak valid.'); window.location.href='dashboard.php';</script>";
    exit;
}

$product = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pemesanan Produk - MS Glow Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f5;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 600px;
      margin-top: 50px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h3 {
      color: #d63384;
      text-align: center;
      margin-bottom: 30px;
    }
    .btn-pink {
      background-color: #ff69b4;
      color: white;
      border: none;
    }
    .btn-pink:hover {
      background-color: #ff1493;
    }
  </style>
</head>
<body>

<div class="container">
  <h3>Form Pemesanan</h3>
  <form action="proses_pemesanan.php" method="POST">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    
    <div class="mb-3">
      <label class="form-label">Nama Produk</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Harga</label>
      <input type="text" class="form-control" value="Rp <?= number_format($product['price'], 0, ',', '.') ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Jumlah</label>
      <input type="number" name="quantity" class="form-control" value="1" min="1" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Alamat Pengiriman</label>
      <textarea name="alamat" class="form-control" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-pink w-100">Pesan Sekarang</button>
  </form>
</div>

</body>
</html>
