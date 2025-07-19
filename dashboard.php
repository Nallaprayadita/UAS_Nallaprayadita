<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - MS Glow Store</title>
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

    .carousel-inner {
      max-height: 500px;
      overflow: hidden;
      border-radius: 10px;
    }

    .carousel img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    .mini-showcase {
      display: flex;
      gap: 30px;
      justify-content: center;
      margin-top: 40px;
      flex-wrap: wrap;
    }

    .mini-showcase-item {
      width: 220px;
      text-align: center;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 15px;
    }

    .mini-showcase-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .promo-section {
      margin: 50px auto;
      max-width: 1000px;
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      text-align: center;
    }

    .promo-section img {
      width: 60%;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .testimonial-box {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      font-style: italic;
      color: #555;
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

      .mini-showcase-item {
        width: 100%;
        max-width: 300px;
        margin: auto;
      }

      .mini-showcase {
        gap: 20px;
        flex-direction: column;
        align-items: center;
      }

      .promo-section img {
        width: 100%;
      }

      .carousel-inner {
        max-height: 300px;
      }

      h2, h4 {
        font-size: 20px;
      }

      .testimonial-box {
        font-size: 14px;
      }

      .navbar-nav {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <div class="navbar-logo">MS Glow Store</div>

  <nav class="navbar navbar-expand-lg">
    <div class="container justify-content-center">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="products/index.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="categories/index.php">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="orders/index.php">Pesanan</a></li>
      </ul>
    </div>
  </nav>

  <a href="logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

  <div class="container">
    <h2 class="mt-4 text-center ">Selamat Datang, <?= htmlspecialchars($user['name']) ?>!</h2>

    <?php if ($user['role'] === 'admin'): ?>
      <p class="text-center mt-3">Anda login sebagai <strong>Admin</strong>. Silakan kelola data melalui menu di atas.</p>
    <?php else: ?>

      <!-- Slideshow Banner -->
      <div id="bannerCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="uploads/banner.jpg" class="d-block w-100" alt="Banner 1">
          </div>
          <div class="carousel-item">
            <img src="uploads/banner2.jpg" class="d-block w-100" alt="Banner 2">
          </div>
          <div class="carousel-item">
            <img src="uploads/banner3.jpg" class="d-block w-100" alt="Banner 3">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>

      <!-- Produk Pilihan -->
      <h4 class="mt-5 text-center">Glow naturally with our best-selling skincare</h4>
      <div class="mini-showcase">
        <a href="pemesanan.php?id=1" class="text-decoration-none text-dark">
          <div class="mini-showcase-item">
            <img src="uploads/produk1.jpg" alt="Produk 1">
            <p>MS Glow Day Cream</p>
          </div>
        </a>
        <a href="pemesanan.php?id=2" class="text-decoration-none text-dark">
          <div class="mini-showcase-item">
            <img src="uploads/produk2.jpg" alt="Produk 2">
            <p>MS Glow Facial Wash</p>
          </div>
        </a>
        <a href="pemesanan.php?id=3" class="text-decoration-none text-dark">
          <div class="mini-showcase-item">
            <img src="uploads/produk3.jpg" alt="Produk 3">
            <p>MS Glow Night Cream</p>
          </div>
        </a>
      </div>

      <!-- Promo Section -->
      <div class="promo-section mt-5">
        <img src="uploads/promo1.jpg" alt="Promo MS Glow">
        <p class="mt-3 text-muted">
          <strong>MS Glow</strong> adalah brand skincare ternama di Indonesia yang dikenal dengan rangkaian produk unggulannya. Diformulasikan dengan bahan-bahan berkualitas tinggi, MS Glow cocok untuk semua jenis kulit—mulai dari kulit kering, berminyak, sensitif hingga berjerawat. Dengan komitmen untuk memberikan hasil nyata, MS Glow membantu merawat, mencerahkan, dan menjaga kesehatan kulit secara optimal. Tak heran jika MS Glow menjadi pilihan favorit jutaan pengguna di seluruh Indonesia.
        </p>
      </div>

      <!-- Kolase Brand Ambassador -->
      <div class="container mt-5 mb-5">
        <h4 class="text-center mb-4" style="color: #d63384;">Dipercaya oleh Selebriti & Influencer</h4>
        <div class="row justify-content-center g-4">
          <div class="col-6 col-sm-4 col-md-3">
            <img src="uploads/ambassador1.jpg" class="img-fluid rounded shadow-sm" alt="selebgram">
            <p class="text-center mt-2 mb-0 fw-semibold">selebgram</p>
            <p class="text-center text-muted small">Presenter & Public Figure</p>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <img src="uploads/ambassador2.jpg" class="img-fluid rounded shadow-sm" alt="Nagita Slavina">
            <p class="text-center mt-2 mb-0 fw-semibold">Nagita Slavina</p>
            <p class="text-center text-muted small">Model & Influencer</p>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <img src="uploads/ambassador3.jpg" class="img-fluid rounded shadow-sm" alt="Sarwendah">
            <p class="text-center mt-2 mb-0 fw-semibold">Sarwendah</p>
            <p class="text-center text-muted small">Penyanyi muda</p>
          </div>
          <div class="col-6 col-sm-4 col-md-3">
            <img src="uploads/ambassador4.jpg" class="img-fluid rounded shadow-sm" alt="Lesti Kejora">
            <p class="text-center mt-2 mb-0 fw-semibold">Lesti Kejora</p>
            <p class="text-center text-muted small">Artis & Ibu Muda</p>
          </div>
        </div>
      </div>

      <!-- Testimoni Pelanggan -->
<div class="container mb-5">
  <h4 class="text-center mb-4" style="color: #d63384;">Apa Kata Mereka?</h4>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="testimonial-box">
        “Kulitku jadi glowing dan halus banget setelah 2 minggu pakai MS Glow. Produk lokal tapi kualitas internasional!” – <strong>Adinda Nazwa, Mahasiswi</strong>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-box">
        “Cocok banget buat kulit sensitifku. Sekarang nggak gampang jerawatan lagi. Terima kasih MS Glow!” – <strong>Lutfi Mulia, Ibu Rumah Tangga</strong>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-box">
        “Paketnya datang cepat, produk original, dan hasilnya nyata. Wajib repeat order!” – <strong>Nur Hikmah, Karyawan Swasta</strong>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-box">
        “Semenjak pakai MS Glow, jadi sering muji wajahku makin cerah, Terimakasih MS Glow Love it!” – <strong>Raihana, Guru SD</strong>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-box">
        “Akhirnya nemu produk yang beneran ngaruh buat flek hitam di pipi. Terima kasih MS Glow.” – <strong>Sardilla, Dosen</strong>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial-box">
        “Udah coba banyak skincare, tapi baru MS Glow yang cocok. Nggak akan pindah lagi.” – <strong>Puspita, Mahasiswi</strong>
      </div>
    </div>
  </div>
</div>


    <?php endif; ?>
  </div> <!-- ✅ Penutup container utama -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
