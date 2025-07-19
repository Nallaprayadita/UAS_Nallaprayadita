<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
// Handle AJAX filter
if(isset($_GET['ajax']) && $_GET['ajax']==1) {
  $where = "WHERE o.status='selesai'";
  if(!empty($_GET['date_from'])) {
    $from = mysqli_real_escape_string($conn, $_GET['date_from']);
    $where .= " AND o.order_date >= '$from'";
  }
  if(!empty($_GET['date_to'])) {
    $to = mysqli_real_escape_string($conn, $_GET['date_to']);
    $where .= " AND o.order_date <= '$to'";
  }
  $summary = mysqli_query($conn, "SELECT COUNT(*) as total_orders, SUM(total) as total_revenue FROM orders o $where");
  $sum = mysqli_fetch_assoc($summary);
  $orders = mysqli_query($conn, "SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id = u.id $where ORDER BY o.order_date DESC");
  ob_start();
  $no=1;
  while($o = mysqli_fetch_assoc($orders)) {
    echo "<tr><td>".$no++."</td><td>".$o['id']."</td><td>".htmlspecialchars($o['name'])."</td><td>".htmlspecialchars($o['email'])."</td><td>".htmlspecialchars($o['order_date'])."</td><td>Rp ".number_format($o['total'],0,',','.')."</td></tr>";
  }
  $table = ob_get_clean();
  echo json_encode([
    'total_orders'=>$sum['total_orders']??0,
    'total_revenue'=>number_format($sum['total_revenue']??0,0,',','.'),
    'table'=>$table
  ]);
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sales Report - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(135deg, #f8fafc 0%, #ffe0f7 100%); font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; }
    .card { border-radius: 18px; box-shadow: 0 4px 24px rgba(214,51,132,0.08); }
    .btn-primary, .btn-outline-danger { border-radius: 20px; font-weight: 500; }
    .table-responsive { border-radius: 18px; overflow: hidden; box-shadow: 0 2px 12px rgba(214,51,132,0.07); }
    th { background: linear-gradient(90deg, #d63384 60%, #ffb6e6 100%) !important; color: #fff !important; }
  </style>
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
        <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> User Management</a></li>
        <li class="nav-item"><a class="nav-link active" href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
      </ul>
      <span class="navbar-text d-none d-lg-inline" style="color:#d63384;font-weight:500;">MS Glow Admin</span>
    </div>
  </div>
</nav>
<div class="container py-4">
  <div class="card p-4 mb-4">
    <h2 class="fw-bold mb-3" style="color:#d63384">Sales Report</h2>
    <form class="row g-3 mb-4" id="filterForm">
      <div class="col-md-4">
        <label for="date_from" class="form-label">From</label>
        <input type="date" class="form-control" id="date_from" name="date_from">
      </div>
      <div class="col-md-4">
        <label for="date_to" class="form-label">To</label>
        <input type="date" class="form-control" id="date_to" name="date_to">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
      </div>
    </form>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="p-3 bg-white rounded shadow-sm mb-2">
          <h5 class="mb-1">Total Completed Orders</h5>
          <div class="fs-3 fw-bold text-success" id="total_orders">0</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-3 bg-white rounded shadow-sm mb-2">
          <h5 class="mb-1">Total Revenue</h5>
          <div class="fs-3 fw-bold text-primary" id="total_revenue">Rp 0</div>
        </div>
      </div>
    </div>
    <div class="mb-3 text-end">
      <a href="#" class="btn btn-outline-danger" id="exportPdfBtn"><i class="fas fa-file-pdf"></i> Export PDF</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th>No</th>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Date</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="orders_table">
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadReport() {
  var data = $('#filterForm').serialize() + '&ajax=1';
  $.get('sales_report.php', data, function(res) {
    var d = JSON.parse(res);
    $('#total_orders').text(d.total_orders);
    $('#total_revenue').text('Rp ' + d.total_revenue);
    $('#orders_table').html(d.table);
  });
}
$('#filterForm').on('submit', function(e) {
  e.preventDefault();
  loadReport();
});
$(document).ready(function(){
  loadReport();
  $('#exportPdfBtn').on('click', function(e){
    e.preventDefault();
    var params = $('#filterForm').serialize();
    window.open('sales_report_pdf.php?' + params, '_blank');
  });
});
</script>
</body>
</html>