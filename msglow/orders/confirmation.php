<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['checkout_data'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$checkout_data = $_SESSION['checkout_data'];

// Map shipping_method and payment_method to their respective IDs
$shipping_methods_map = [
    'reguler' => 1,
    'express' => 2,
    'instant' => 3,
    'cargo' => 4
];
$payment_methods_map = [
    'cod' => 1,
    'transfer' => 2,
    'ewallet' => 3,
    'credit_card' => 4,
    'virtual_account' => 5
];

$checkout_data['shipping_method_id'] = $shipping_methods_map[$checkout_data['shipping_method']] ?? null;
$checkout_data['payment_method_id'] = $payment_methods_map[$checkout_data['payment_method']] ?? null;
$order_id = $checkout_data['order_id'];

// Ambil data pesanan
$query = "SELECT * FROM orders WHERE id = $order_id AND user_id = {$user['id']}";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: index.php");
    exit;
}

// Ambil data user
$user_query = "SELECT * FROM users WHERE id = {$order['user_id']}";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Gabungkan data
$order['user_name'] = $user_data['name'] ?? '';
$order['email'] = $user_data['email'] ?? '';
$order['phone'] = $user_data['phone'] ?? '';
$order['address'] = $user_data['address'] ?? '';

// Ambil detail produk dalam pesanan
$items_result = null;
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'order_items'");
if (mysqli_num_rows($check_table) > 0) {
    $query_items = "SELECT oi.*, p.name, p.price, 
                           COALESCE(p.image, 'default.jpg') as image
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = $order_id";
    $items_result = mysqli_query($conn, $query_items);
}

// Hitung total dengan ongkos kirim
$shipping_cost = 0;
$shipping_text = 'Gratis';
if ($checkout_data['shipping_method'] == 'express') {
    $shipping_cost = 15000;
    $shipping_text = 'Rp 15.000';
} elseif ($checkout_data['shipping_method'] == 'instant') {
    $shipping_cost = 25000;
    $shipping_text = 'Rp 25.000';
}
$total_amount = $order['total'] + $shipping_cost;

// Proses konfirmasi pesanan
if (isset($_POST['confirm'])) {
    // Update pesanan dengan data checkout
    $update_query = "UPDATE orders SET 
                     shipping_address = '{$checkout_data['shipping_address']}',
                     shipping_method_id = '{$checkout_data['shipping_method_id']}',
                     payment_method_id = '{$checkout_data['payment_method_id']}',
                     notes = '{$checkout_data['notes']}',
                     total = $total_amount,
                     status = 'confirmed'
                     WHERE id = $order_id";
    
    if (mysqli_query($conn, $update_query)) {
        unset($_SESSION['checkout_data']);
        header("Location: thank_you.php");
        exit;
    }
}

// Proses cancel - kembali ke checkout
if (isset($_POST['cancel'])) {
    header("Location: checkout.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pesanan - MS Glow Store</title>
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

    .confirmation-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .product-item {
      border-bottom: 1px solid #eee;
      padding: 15px 0;
    }

    .product-item:last-child {
      border-bottom: none;
    }

    .product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding: 5px 0;
      border-bottom: 1px solid #f8f9fa;
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .total-section {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      margin-top: 20px;
    }

    .btn-confirm {
      background: #d63384;
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-confirm:hover {
      background: #ff1493;
      color: white;
    }

    .btn-cancel {
      background: #6c757d;
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-cancel:hover {
      background: #5a6268;
      color: white;
    }

    .alert-info {
      background: #d1ecf1;
      border: 1px solid #bee5eb;
      color: #0c5460;
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

  <div class="container">
    <h3>Konfirmasi Pesanan</h3>

    <div class="alert alert-info">
      <strong>Periksa kembali detail pesanan Anda sebelum melanjutkan!</strong>
    </div>

    <div class="row">
      <div class="col-md-8">
        <!-- Informasi Pengiriman -->
        <div class="confirmation-card p-4">
          <h5 class="mb-3">Informasi Pengiriman</h5>
          <div class="info-row">
            <span><strong>Nama Lengkap:</strong></span>
            <span><?= htmlspecialchars($order['user_name']) ?></span>
          </div>
          <div class="info-row">
            <span><strong>Email:</strong></span>
            <span><?= htmlspecialchars($order['email']) ?></span>
          </div>
          <div class="info-row">
            <span><strong>No. Telepon:</strong></span>
            <span><?= htmlspecialchars($checkout_data['phone'] ?? $order['phone']) ?></span>
          </div>
          <div class="info-row">
            <span><strong>Alamat:</strong></span>
            <span><?= htmlspecialchars($checkout_data['shipping_address']) ?></span>
          </div>
        </div>

        <!-- Detail Produk -->
        <div class="confirmation-card p-4">
          <h5 class="mb-3">Detail Produk</h5>
          <?php if ($items_result && mysqli_num_rows($items_result) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
              <div class="product-item">
                <div class="row align-items-center">
                  <div class="col-2">
                    <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-image">
                  </div>
                  <div class="col-6">
                    <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                    <small class="text-muted">Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                  </div>
                  <div class="col-2 text-center">
                    <span class="badge bg-secondary"><?= $item['quantity'] ?>x</span>
                  </div>
                  <div class="col-2 text-end">
                    <strong>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></strong>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="product-item">
              <div class="row align-items-center">
                <div class="col-2">
                  <img src="../uploads/default.jpg" alt="Produk" class="product-image">
                </div>
                <div class="col-6">
                  <h6 class="mb-1">Produk Pesanan</h6>
                  <small class="text-muted">Detail produk tidak tersedia</small>
                </div>
                <div class="col-2 text-center">
                  <span class="badge bg-secondary">1x</span>
                </div>
                <div class="col-2 text-end">
                  <strong>Rp <?= number_format($order['total'], 0, ',', '.') ?></strong>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Opsi Pengiriman & Pembayaran -->
        <div class="confirmation-card p-4">
          <h5 class="mb-3">Opsi Pengiriman & Pembayaran</h5>
          <div class="info-row">
            <span><strong>Metode Pengiriman:</strong></span>
            <span>
              <?php
                $shipping_methods = [
                  'reguler' => 'Reguler (3-5 hari)',
                  'express' => 'Express (1-2 hari)',
                  'instant' => 'Instant (Hari ini)'
                ];
                echo $shipping_methods[$checkout_data['shipping_method']];
              ?>
            </span>
          </div>
          <div class="info-row">
            <span><strong>Metode Pembayaran:</strong></span>
            <span>
              <?php
                $payment_methods = [
                  'cod' => 'Cash on Delivery (COD)',
                  'transfer' => 'Transfer Bank',
                  'ewallet' => 'E-Wallet'
                ];
                echo $payment_methods[$checkout_data['payment_method']];
              ?>
            </span>
          </div>
          <?php if (!empty($checkout_data['notes'])): ?>
            <div class="info-row">
              <span><strong>Catatan:</strong></span>
              <span><?= htmlspecialchars($checkout_data['notes']) ?></span>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-md-4">
        <!-- Total Pesanan -->
        <div class="confirmation-card p-4">
          <h5 class="mb-3">Total Pesanan</h5>
          <div class="total-section">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <span>Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Ongkos Kirim:</span>
              <span><?= $shipping_text ?></span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Total:</strong>
              <strong>Rp <?= number_format($total_amount, 0, ',', '.') ?></strong>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="confirmation-card p-4">
          <form method="POST" class="d-grid gap-2">
            <button type="submit" name="confirm" class="btn btn-confirm">
              Konfirmasi Pesanan
            </button>
            <button type="submit" name="cancel" class="btn btn-cancel">
              Kembali ke Checkout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
