<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$userRole = $user['role'];

// Ambil kategori
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Filter & Search
$filter_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "SELECT * FROM products WHERE 1";
if ($filter_category > 0) {
    $query .= " AND category_id = $filter_category";
}
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk - MS Glow Store</title>
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

    .search-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }

    .product-card {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 8px 15px rgba(0,0,0,0.08);
      padding: 20px;
      text-align: center;
      transition: 0.3s;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }

    .product-card img {
      height: 250px;
      width: 100%;
      object-fit: contain;
      border-radius: 10px;
      margin-bottom: 10px;
      background-color: #f8f9fa;
      padding: 10px;
    }

    .btn-pink {
      background-color: #ff69b4 !important;
      color: white !important;
      border: none !important;
    }

    .btn-pink:hover {
      background-color: #ff1493 !important;
      color: white !important;
    }

    @media (max-width: 768px) {
      .navbar-logo {
        font-size: 22px;
        padding: 15px 0 5px;
      }

      .logout-btn {
        font-size: 14px;
        top: 10px;
        right: 15px;
        padding: 5px 10px;
      }

      h3 {
        font-size: 20px;
        margin-top: 15px;
      }

      .btn,
      .form-control,
      .form-select {
        font-size: 14px;
      }

      .product-card img {
        height: 180px;
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
      <li class="nav-item"><a class="nav-link active" href="#">Produk</a></li>
      <li class="nav-item"><a class="nav-link" href="../categories/index.php">Kategori</a></li>
      <li class="nav-item"><a class="nav-link" href="../orders/index.php">Pesanan</a></li>
    </ul>
  </div>
</nav>

<a href="../logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

<div class="container mt-4">
  <h3 class="text-center mb-4">Daftar Produk</h3>

  <!-- Filter & Search -->
  <div class="row mb-4">
    <div class="col-md-6 mb-2 mb-md-0">
      <form method="GET" class="d-flex">
        <select name="category_id" class="form-select me-2" onchange="this.form.submit()">
          <option value="0">-- Semua Kategori --</option>
          <?php mysqli_data_seek($categories, 0); while ($cat = mysqli_fetch_assoc($categories)) : ?>
            <option value="<?= $cat['id'] ?>" <?= ($filter_category == $cat['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <noscript><button type="submit" class="btn btn-pink">Filter</button></noscript>
      </form>
    </div>
    <div class="col-md-6">
      <form method="GET" class="d-flex search-bar">
        <input type="hidden" name="category_id" value="<?= $filter_category ?>">
        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-pink ms-2">Cari</button>
      </form>
    </div>
  </div>

  <!-- Daftar Produk -->
  <div class="row justify-content-center">

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="col-sm-6 col-md-4 mb-4">
        <div class="product-card">
          <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <h5><?= htmlspecialchars($row['name']) ?></h5>
          <p class="text-muted">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
          <a href="../pemesanan.php?id=<?= $row['id'] ?>" class="btn btn-pink btn-sm">Pesan Sekarang</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
