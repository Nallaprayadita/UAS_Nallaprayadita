<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$order_id = $_GET['order_id'] ?? 0;

// Ambil data pesanan terlebih dahulu
$query = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: index.php");
    exit;
}

// Ambil data user secara terpisah
$user_query = "SELECT * FROM users WHERE id = {$order['user_id']}";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("User query error: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($user_result);

// Gabungkan data untuk kompatibilitas
$order['user_name'] = $user_data['name'] ?? '';
$order['email'] = $user_data['email'] ?? '';
$order['phone'] = $user_data['phone'] ?? '';
$order['address'] = $user_data['address'] ?? '';

// Ambil detail produk dalam pesanan (cek apakah tabel order_items ada)
$items_result = null;
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'order_items'");
if (mysqli_num_rows($check_table) > 0) {
    $query_items = "SELECT oi.*, p.name, p.price, 
                           COALESCE(p.image, 'default.jpg') as image
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = $order_id";
    $items_result = mysqli_query($conn, $query_items);
    
    if (!$items_result) {
        die("Query items error: " . mysqli_error($conn));
    }
}

// Proses form checkout
if ($_POST) {
    $phone = $_POST['phone'];
    $shipping_address = $_POST['shipping_address'];
    $shipping_method = $_POST['shipping_method'];
    $payment_method = $_POST['payment_method'];
    $notes = $_POST['notes'];
    
    // Simpan ke session untuk halaman konfirmasi
    $_SESSION['checkout_data'] = [
        'order_id' => $order_id,
        'phone' => $phone,
        'shipping_address' => $shipping_address,
        'shipping_method' => $shipping_method,
        'payment_method' => $payment_method,
        'notes' => $notes
    ];
    
    header("Location: confirmation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout - MS Glow Store</title>
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

    .checkout-card {
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

    .total-section {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-top: 20px;
    }

    .checkout-btn {
      background: #d63384;
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
      width: 100%;
    }

    .checkout-btn:hover {
      background: #ff1493;
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
    <h3>Checkout Pesanan</h3>

    <form method="POST">
      <div class="row">
        <div class="col-md-8">
          <!-- Alamat Pengiriman -->
          <div class="checkout-card p-4">
            <h5 class="mb-3">Alamat Pengiriman</h5>
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($order['user_name']) ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="<?= htmlspecialchars($order['email']) ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">No. Telepon</label>
              <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($order['phone']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat Pengiriman</label>
              <textarea name="shipping_address" class="form-control" rows="3" required><?= htmlspecialchars($order['address']) ?></textarea>
            </div>
          </div>

          <!-- Detail Produk -->
          <div class="checkout-card p-4">
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

          <!-- Opsi Pengiriman -->
          <div class="checkout-card p-4">
            <h5 class="mb-3">Opsi Pengiriman</h5>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="shipping_method" value="reguler" id="reguler" checked>
              <label class="form-check-label" for="reguler">
                <strong>Reguler (3-5 hari)</strong> - Gratis
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="shipping_method" value="express" id="express">
              <label class="form-check-label" for="express">
                <strong>Express (1-2 hari)</strong> - Rp 15.000
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="shipping_method" value="instant" id="instant">
              <label class="form-check-label" for="instant">
                <strong>Instant (Hari ini)</strong> - Rp 25.000
              </label>
            </div>
          </div>

          <!-- Metode Pembayaran -->
          <div class="checkout-card p-4">
            <h5 class="mb-3">Metode Pembayaran</h5>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
              <label class="form-check-label" for="cod">
                <strong>Cash on Delivery (COD)</strong>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" value="transfer" id="transfer">
              <label class="form-check-label" for="transfer">
                <strong>Transfer Bank</strong>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" value="ewallet" id="ewallet">
              <label class="form-check-label" for="ewallet">
                <strong>E-Wallet</strong>
              </label>
            </div>
          </div>

          <!-- Catatan -->
          <div class="checkout-card p-4">
            <h5 class="mb-3">Catatan (Opsional)</h5>
            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan untuk pesanan Anda..."></textarea>
          </div>
        </div>

        <div class="col-md-4">
          <!-- Ringkasan Pesanan -->
          <div class="checkout-card p-4">
            <h5 class="mb-3">Ringkasan Pesanan</h5>
            <div class="total-section">
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span>Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Ongkos Kirim:</span>
                <span id="shipping-cost">Gratis</span>
              </div>
              <hr>
              <div class="d-flex justify-content-between">
                <strong>Total:</strong>
                <strong id="total-amount">Rp <?= number_format($order['total'], 0, ',', '.') ?></strong>
              </div>
            </div>
            <button type="submit" class="checkout-btn mt-3">Lanjutkan ke Konfirmasi</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Update shipping cost and total
    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
      radio.addEventListener('change', function() {
        const subtotal = <?= $order['total'] ?>;
        let shippingCost = 0;
        let shippingText = 'Gratis';
        
        if (this.value === 'express') {
          shippingCost = 15000;
          shippingText = 'Rp 15.000';
        } else if (this.value === 'instant') {
          shippingCost = 25000;
          shippingText = 'Rp 25.000';
        }
        
        const total = subtotal + shippingCost;
        
        document.getElementById('shipping-cost').textContent = shippingText;
        document.getElementById('total-amount').textContent = 'Rp ' + total.toLocaleString('id-ID');
      });
    });
  </script>
</body>
</html>
