<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$userRole = $user['role'];

// Query untuk mendapatkan data pesanan dengan detail produk
$query = "SELECT o.*, 
                 GROUP_CONCAT(p.name SEPARATOR ', ') as product_names,
                 COUNT(oi.id) as total_items
          FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id
          LEFT JOIN products p ON oi.product_id = p.id
          WHERE o.user_id = $user_id 
          GROUP BY o.id
          ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pesanan - MS Glow Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f5;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar-logo {
      text-align: center;
      padding: 20px 0 10px;
      font-size: 28px;
      font-weight: bold;
      color: #d63384;
    }

    .navbar {
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      background-color: white !important;
    }

    .navbar-nav {
      margin: 0 auto;
    }

    .navbar-nav .nav-link {
      color: #d63384;
      font-weight: 500;
      padding: 10px 15px;
    }

    .navbar-nav .nav-link:hover {
      color: #ff1493;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 30px;
    }

    h3 {
      text-align: center;
      margin: 30px 0;
      color: #d63384;
    }

    @media (max-width: 768px) {
      .navbar-logo {
        font-size: 22px;
        padding: 15px 0 5px;
      }

      .logout-btn {
        top: 10px;
        right: 15px;
        font-size: 14px;
        padding: 5px 10px;
      }

      h3 {
        font-size: 20px;
      }

      .table th, .table td {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="navbar-logo">MS Glow Store</div>

  <nav class="navbar navbar-expand-lg">
    <div class="container justify-content-center">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="../dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="../products/index.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="../categories/index.php">Kategori</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Pesanan</a></li>
      </ul>
    </div>
  </nav>

  <a href="../logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

  <div class="container">
    <h3>Riwayat Pesanan Anda</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Jenis Pesanan</th>
              <th>Jumlah</th>
              <th>Total Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($order = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                <td><?= htmlspecialchars($order['product_names'] ?: 'Produk tidak tersedia') ?></td>
                <td><?= $order['total_items'] ?> item</td>
                <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                <td>
                  <a href="checkout.php?order_id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">Checkout</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-center text-muted">Belum ada pesanan.</p>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
