<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$result = mysqli_query($conn, "SELECT * FROM payment_methods");
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Metode Pembayaran - MS Glow Store</title>
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

    .btn-outline-pink {
      color: #ff1493;
      border: 1px solid #ff69b4;
    }

    .btn-outline-pink:hover {
      background-color: #ff69b4;
      color: white;
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

      h2 {
        font-size: 22px;
      }

      .btn {
        font-size: 14px;
        padding: 6px 12px;
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
        <li class="nav-item"><a class="nav-link" href="../orders/index.php">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Metode Pembayaran</a></li>
      </ul>
    </div>
  </nav>

  <a href="../logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

  <div class="container mt-5">
    <h2 class="mb-4 text-center">Data Metode Pembayaran</h2>

    <a href="create.php" class="btn btn-primary mb-3">+ Tambah Metode Pembayaran</a>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama Metode</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['method_name']); ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
