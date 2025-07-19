<?php
require_once '../config/database.php';

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

// Set headers for PDF download
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - MS Glow</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .print-only { display: block; }
        }
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #d63384;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #d63384;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h3 {
            margin: 0;
            color: #d63384;
            font-size: 24px;
        }
        .summary-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #d63384;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .print-btn {
            background: #d63384;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .print-btn:hover {
            background: #b02a5b;
        }
        .date-range {
            text-align: center;
            margin: 10px 0;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
    
    <div class="header">
        <h1>Sales Report</h1>
        <p>MS Glow Store</p>
        <p>Generated on: <?php echo date('d F Y, H:i:s'); ?></p>
        <?php if(!empty($_GET['date_from']) || !empty($_GET['date_to'])): ?>
        <div class="date-range">
            Period: 
            <?php echo !empty($_GET['date_from']) ? date('d F Y', strtotime($_GET['date_from'])) : 'Beginning'; ?>
            - 
            <?php echo !empty($_GET['date_to']) ? date('d F Y', strtotime($_GET['date_to'])) : 'Today'; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3><?php echo $sum['total_orders'] ?? 0; ?></h3>
            <p>Total Completed Orders</p>
        </div>
        <div class="summary-item">
            <h3>Rp <?php echo number_format($sum['total_revenue'] ?? 0, 0, ',', '.'); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Order ID</th>
                <th style="width: 25%;">Customer</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($orders) > 0):
                while($o = mysqli_fetch_assoc($orders)): 
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($o['id']); ?></td>
                <td><?php echo htmlspecialchars($o['name']); ?></td>
                <td><?php echo htmlspecialchars($o['email']); ?></td>
                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($o['order_date']))); ?></td>
                <td>Rp <?php echo number_format($o['total'], 0, ',', '.'); ?></td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #666; font-style: italic;">
                    No completed orders found for the selected period.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Close window after printing (optional)
        window.onafterprint = function() {
            // window.close();
        }
    </script>
</body>
</html>