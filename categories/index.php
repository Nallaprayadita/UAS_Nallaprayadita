<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$userRole = $user['role'];

// Ambil data kategori
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kategori - MS Glow Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffe4f0;
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

    .category-card {
      background-color: white;
      border-radius: 16px;
      padding: 30px 20px;
      box-shadow: 0 8px 15px rgba(0,0,0,0.08);
      transition: 0.3s;
    }

    .category-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.12);
    }

    .category-card img {
      width: 60px;
      margin-bottom: 15px;
    }

    .btn-pink {
      background-color: #ff69b4;
      color: white;
      border: none;
    }

    .btn-pink:hover {
      background-color: #ff1493;
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
      .form-control {
        font-size: 14px;
      }

      .category-card {
        padding: 20px 15px;
      }

      .category-card img {
        width: 50px;
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
      <li class="nav-item"><a class="nav-link active" href="#">Kategori</a></li>
      <li class="nav-item"><a class="nav-link" href="../orders/index.php">Pesanan</a></li>
    </ul>
  </div>
</nav>

<a href="../logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

<div class="container mt-5">
  <h3 class="text-center mb-4" style="color: #d63384; font-family: 'Comic Sans MS', 'cursive'; font-size: 24px; text-shadow: 1px 1px 2px #f8c8dc;">
     Temukan berbagai kategori produk perawatan terbaik dari MS Glow 
  </h3>

<div class="row justify-content-center text-center">
  <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
      <div class="category-card w-100">
        <?php
          // Gambar ditentukan secara manual berdasarkan ID kategori
          switch ($row['id']) {
              case 1:
                  $image = 'produk_wajah.png';
                  break;
              case 2:
                  $image = 'produk_kulit.jpg';
                  break;
              case 3:
                  $image = 'produk_tubuh.jpg';
                  break;
              default:
                  $image = 'default.jpg';
                  break;
          }
        ?>
        <img src="../uploads/<?= urlencode($image) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <h5><?= htmlspecialchars($row['name']) ?></h5>
        <p class="text-muted">Klik untuk melihat produk dari kategori ini.</p>
        <a href="../products/index.php?category_id=<?= $row['id'] ?>" class="btn btn-outline-pink btn-sm" style="border-color: #ff69b4; color: #ff69b4;">Lihat Produk</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
