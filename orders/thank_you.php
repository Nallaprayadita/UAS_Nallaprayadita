<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Terima Kasih - MS Glow Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f5;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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

    .thank-you-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .thank-you-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      padding: 60px 40px;
      text-align: center;
      max-width: 600px;
      width: 100%;
    }

    .success-icon {
      font-size: 80px;
      color: #28a745;
      margin-bottom: 30px;
    }

    .thank-you-title {
      font-size: 32px;
      font-weight: bold;
      color: #d63384;
      margin-bottom: 20px;
    }

    .thank-you-message {
      font-size: 18px;
      color: #6c757d;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .order-info {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
    }

    .order-info h5 {
      color: #d63384;
      margin-bottom: 15px;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding: 5px 0;
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .btn-primary-custom {
      background: #d63384;
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-block;
      margin: 0 10px;
    }

    .btn-primary-custom:hover {
      background: #ff1493;
      color: white;
      text-decoration: none;
    }

    .btn-outline-custom {
      border: 2px solid #d63384;
      color: #d63384;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-block;
      margin: 0 10px;
    }

    .btn-outline-custom:hover {
      background: #d63384;
      color: white;
      text-decoration: none;
    }

    .features-list {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }

    .features-list li {
      padding: 5px 0;
      color: #6c757d;
    }

    .features-list li:before {
      content: "✓";
      color: #28a745;
      font-weight: bold;
      margin-right: 10px;
    }

    .animation-bounce {
      animation: bounce 2s infinite;
    }

    @keyframes bounce {
      0%, 20%, 60%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-10px);
      }
      80% {
        transform: translateY(-5px);
      }
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

      .thank-you-card {
        padding: 40px 20px;
        margin: 20px;
      }

      .thank-you-title {
        font-size: 24px;
      }

      .thank-you-message {
        font-size: 16px;
      }

      .success-icon {
        font-size: 60px;
      }

      .btn-primary-custom,
      .btn-outline-custom {
        display: block;
        width: 100%;
        margin: 10px 0;
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
        <li class="nav-item"><a class="nav-link active" href="index.php">Pesanan</a></li>
      </ul>
    </div>
  </nav>

  <a href="../logout.php" class="btn btn-outline-danger logout-btn">Logout</a>

  <div class="thank-you-container">
    <div class="thank-you-card">
      <div class="success-icon animation-bounce">🎉</div>
      
      <h1 class="thank-you-title">Terima Kasih Telah Berbelanja di MS Glow Store!</h1>
      
      <p class="thank-you-message">
        Pesanan Anda telah berhasil dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk berkualitas tinggi MS Glow ke alamat yang Anda berikan.
      </p>

      <div class="order-info">
        <h5>Informasi Pesanan</h5>
        <div class="info-item">
          <span><strong>Status:</strong></span>
          <span><span class="badge bg-success">Dikonfirmasi</span></span>
        </div>
        <div class="info-item">
          <span><strong>Estimasi Pengiriman:</strong></span>
          <span>1-5 hari kerja</span>
        </div>
        <div class="info-item">
          <span><strong>Notifikasi:</strong></span>
          <span>Email & SMS</span>
        </div>
      </div>

      <div class="mb-4">
        <h6 class="text-muted mb-3">Apa yang akan terjadi selanjutnya?</h6>
        <ul class="features-list">
          <li>Tim kami akan memproses pesanan Anda dalam 1-2 jam</li>
          <li>Anda akan menerima email konfirmasi dengan detail pengiriman</li>
          <li>Produk akan dikemas dengan hati-hati dan dikirim</li>
          <li>Tracking number akan dikirimkan via email/SMS</li>
        </ul>
      </div>

      <div class="d-flex justify-content-center flex-wrap">
        <a href="index.php" class="btn-primary-custom">Lihat Pesanan Saya</a>
        <a href="../products/index.php" class="btn-outline-custom">Belanja Lagi</a>
      </div>

      <div class="mt-4">
        <small class="text-muted">
          Ada pertanyaan? Hubungi customer service kami di <strong>0800-1234-5678</strong> atau email <strong>support@msglow.com</strong>
        </small>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto redirect ke halaman orders setelah 10 detik
    setTimeout(function() {
      if (confirm('Apakah Anda ingin melihat daftar pesanan?')) {
        window.location.href = 'index.php';
      }
    }, 10000);
  </script>
</body>
</html>
