<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/database.php';

// Check if database connection exists
if (!isset($conn) || !$conn) {
    die('Database connection failed. Please check your database configuration.');
}

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

// Aksi ubah status
if (isset($_GET['aksi'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $aksi = $_GET['aksi'];

    $allowed = [
        'terima' => 'paid',
        'tolak' => 'canceled',
        'selesai' => 'selesai'
    ];

    if (array_key_exists($aksi, $allowed)) {
        $status = $allowed[$aksi];
        $update_query = "UPDATE orders SET status='$status' WHERE id=$id";
        $update_result = mysqli_query($conn, $update_query);
        
        if ($update_result) {
            $_SESSION['success'] = "Status pesanan #$id berhasil diubah menjadi '$status'";
        } else {
            $_SESSION['error'] = "Gagal mengubah status pesanan #$id: " . mysqli_error($conn);
        }
        
        header("Location: orders.php");
        exit;
    }
}

// Filter status dan pencarian
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Statistik
$stats_query = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_orders,
    SUM(CASE WHEN status = 'canceled' THEN 1 ELSE 0 END) as canceled_orders,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN status = 'paid' OR status = 'selesai' THEN total ELSE 0 END) as total_revenue
    FROM orders";
$stats_result = mysqli_query($conn, $stats_query);

if (!$stats_result) {
    die("Error in statistics query: " . mysqli_error($conn));
}

$stats = mysqli_fetch_assoc($stats_result);

// Extract variables from stats array
$total_orders = $stats['total_orders'] ?? 0;
$pending_orders = $stats['pending_orders'] ?? 0;
$paid_orders = $stats['paid_orders'] ?? 0;
$canceled_orders = $stats['canceled_orders'] ?? 0;
$completed_orders = $stats['completed_orders'] ?? 0;
$total_revenue = $stats['total_revenue'] ?? 0;

// Ambil data pesanan dengan join user dan product info
$query = "SELECT orders.*, users.name, users.email,
          GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ') as product_names,
          GROUP_CONCAT(DISTINCT p.image SEPARATOR ', ') as product_images
          FROM orders 
          JOIN users ON orders.user_id = users.id
          LEFT JOIN order_items oi ON orders.id = oi.order_id
          LEFT JOIN products p ON oi.product_id = p.id
          WHERE 1=1";

if ($filter_status && in_array($filter_status, ['pending', 'paid', 'canceled', 'selesai'])) {
    $query .= " AND orders.status = '$filter_status'";
}

if ($search) {
    $query .= " AND (users.name LIKE '%$search%' OR users.email LIKE '%$search%')";
}

$query .= " GROUP BY orders.id ORDER BY orders.order_date DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error in main query: " . mysqli_error($conn));
}

// Total untuk pagination
$count_query = "SELECT COUNT(DISTINCT orders.id) as total FROM orders JOIN users ON orders.user_id = users.id WHERE 1=1";
if ($filter_status && in_array($filter_status, ['pending', 'paid', 'canceled', 'selesai'])) {
    $count_query .= " AND orders.status = '$filter_status'";
}
if ($search) {
    $count_query .= " AND (users.name LIKE '%$search%' OR users.email LIKE '%$search%')";
}
$count_result = mysqli_query($conn, $count_query);

if (!$count_result) {
    die("Error in count query: " . mysqli_error($conn));
}

$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $limit);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #ffe0f7 100%); font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; }
        .dashboard-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(214,51,132,0.08); padding: 32px 24px; margin-bottom: 24px; transition: box-shadow 0.2s; }
        .dashboard-card:hover { box-shadow: 0 8px 32px rgba(214,51,132,0.15); }
        .dashboard-row { display: flex; gap: 24px; justify-content: center; margin-bottom: 32px; flex-wrap: wrap; }
        .dashboard-stat { flex: 1 1 180px; text-align: center; }
        .dashboard-stat h4 { margin: 0; font-size: 2.2rem; color: #d63384; font-weight: bold; }
        .dashboard-stat span { color: #6c757d; font-size: 1.1rem; }
        .table-responsive { border-radius: 18px; overflow: hidden; box-shadow: 0 2px 12px rgba(214,51,132,0.07); }
        table { background: #fff; }
        th { background: linear-gradient(90deg, #d63384 60%, #ffb6e6 100%) !important; color: #fff !important; font-size: 1.08em; }
        td, th { vertical-align: middle !important; }
        .badge-status { font-size: 1em; padding: 7px 18px; border-radius: 20px; font-weight: 500; letter-spacing: 0.5px; }
        .badge-pending { background: #ffe082; color: #b26a00; }
        .badge-paid { background: #b9f6ca; color: #00695c; }
        .badge-canceled { background: #ff8a80; color: #b71c1c; }
        .badge-selesai { background: #cfd8dc; color: #263238; }
        .product-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 10px; margin-right: 7px; border: 2px solid #f8bbd0; box-shadow: 0 1px 4px rgba(214,51,132,0.07); }
        .action-btn { margin: 0 2px; }
        .modal-lg { max-width: 700px; }
        .btn-primary, .btn-outline-danger { border-radius: 20px; font-weight: 500; }
        .btn-link { color: #d63384; text-decoration: underline; font-weight: 500; }
        .btn-link:hover { color: #a81e5d; }
        .dashboard-card .fa { font-size: 2.2rem; margin-bottom: 8px; color: #d63384; }
        .dashboard-stat .fa { font-size: 2.2rem; margin-bottom: 8px; }
        .dashboard-stat .fa-coins { color: #ffb300; }
        .dashboard-stat .fa-check-circle { color: #43a047; }
        .dashboard-stat .fa-clock { color: #ffb300; }
        .dashboard-stat .fa-box { color: #607d8b; }
        .dashboard-stat .fa-user { color: #d63384; }
        @media (max-width: 900px) { .dashboard-row { flex-direction: column; gap: 12px; } }
        @media (max-width: 600px) { .dashboard-card { padding: 18px 8px; } th, td { font-size: 0.98em; } }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showOrderDetail(orderId) {
            fetch('order_detail.php?id=' + orderId)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('orderDetailModalBody').innerHTML = html;
                    var modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
                    modal.show();
                });
        }
        function confirmAction(action, id) {
            let msg = '';
            if(action==='accept') msg = 'Terima pesanan #' + id + '?';
            else if(action==='reject') msg = 'Tolak pesanan #' + id + '?';
            else if(action==='complete') msg = 'Tandai pesanan #' + id + ' sebagai selesai?';
            else msg = 'Lanjutkan aksi ini?';
            return confirm(msg);
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background: linear-gradient(90deg, #fff 60%, #ffe0f7 100%); border-radius: 18px; margin-top: 18px; margin-bottom: 28px; box-shadow: 0 2px 12px rgba(214,51,132,0.07);">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#" style="color:#d63384;">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> User Management</a></li>
        <li class="nav-item"><a class="nav-link" href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
      </ul>
      <span class="navbar-text d-none d-lg-inline" style="color:#d63384;font-weight:500;">MS Glow Admin</span>
    </div>
  </div>
</nav>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color:#d63384">Kelola Pesanan</h2>
        <a href="../logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="dashboard-row">
        <div class="dashboard-card dashboard-stat">
            <h4><?= $total_orders ?></h4>
            <span>Total Orders</span>
        </div>
        <div class="dashboard-card dashboard-stat">
            <h4><?= $pending_orders ?></h4>
            <span>Pending</span>
        </div>
        <div class="dashboard-card dashboard-stat">
            <h4><?= $paid_orders ?></h4>
            <span>Paid</span>
        </div>
        <div class="dashboard-card dashboard-stat">
            <h4><?= $completed_orders ?></h4>
            <span>Completed</span>
        </div>
        <div class="dashboard-card dashboard-stat">
            <h4>Rp <?= number_format($total_revenue, 0, ',', '.') ?></h4>
            <span>Total Revenue</span>
        </div>
    </div>
    <div class="dashboard-card mb-4">
        <form class="row g-3 align-items-center" method="get">
            <div class="col-auto">
                <input type="text" class="form-control" name="search" placeholder="Cari nama/email..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $filter_status == 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="canceled" <?= $filter_status == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                    <option value="selesai" <?= $filter_status == 'selesai' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while($order = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($order['name']) ?><br><small><?= htmlspecialchars($order['email']) ?></small></td>
                    <td><?= htmlspecialchars(substr($order['order_date'], 0, 10)) ?></td>
                    <td>
                        <?php 
                        $productNames = explode(',', $order['product_names']);
                        $productImages = explode(',', $order['product_images']);
                        foreach($productNames as $i => $pname) {
                            $img = trim($productImages[$i] ?? '');
                            if ($img) {
                                echo "<img src='../uploads/".htmlspecialchars($img)."' class='product-thumb' title='".htmlspecialchars($pname)."'> ";
                            }
                        }
                        ?>
                        <a href="javascript:void(0)" onclick="showOrderDetail(<?= $order['id'] ?>)" class="btn btn-link btn-sm">Detail</a>
                    </td>
                    <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($order['shipping_address'] ?? 'Alamat tidak tersedia') ?></td>
                    <td>
                        <?php
                        $statusMap = [
                            'pending' => '<span class="badge badge-status badge-pending">Pending</span>',
                            'paid' => '<span class="badge badge-status badge-paid">Paid</span>',
                            'canceled' => '<span class="badge badge-status badge-canceled">Canceled</span>',
                            'selesai' => '<span class="badge badge-status badge-selesai">Completed</span>'
                        ];
                        echo $statusMap[$order['status']] ?? htmlspecialchars($order['status']);
                        ?>
                    </td>
                    <td>
                        <?php if($order['status'] == 'pending') { ?>
                            <a href="?aksi=terima&id=<?= $order['id'] ?>" class="btn btn-success btn-sm action-btn" onclick="return confirmAction('accept', <?= $order['id'] ?>)"><i class="fas fa-check"></i></a>
                            <a href="?aksi=tolak&id=<?= $order['id'] ?>" class="btn btn-danger btn-sm action-btn" onclick="return confirmAction('reject', <?= $order['id'] ?>)"><i class="fas fa-times"></i></a>
                        <?php } elseif($order['status'] == 'paid') { ?>
                            <a href="?aksi=selesai&id=<?= $order['id'] ?>" class="btn btn-secondary btn-sm action-btn" onclick="return confirmAction('complete', <?= $order['id'] ?>)"><i class="fas fa-box"></i></a>
                        <?php } else { echo '-'; } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Modal for Order Detail -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderDetailModalLabel">Order Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="orderDetailModalBody">
            <!-- AJAX loaded content -->
          </div>
        </div>
      </div>
    </div>
</div>
</body>
</html>
