<?php
require_once '../config/database.php';
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Order ID tidak ditemukan.</div>';
    exit;
}
$order_id = intval($_GET['id']);
$sql = "SELECT o.*, u.name, u.email, GROUP_CONCAT(p.name) as product_names, GROUP_CONCAT(p.image) as product_images, GROUP_CONCAT(oi.quantity) as product_qty, GROUP_CONCAT(oi.price) as product_price
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? GROUP BY o.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($order = $result->fetch_assoc()) {
    $productNames = explode(',', $order['product_names']);
    $productImages = explode(',', $order['product_images']);
    $productQty = explode(',', $order['product_qty']);
    $productPrice = explode(',', $order['product_price']);
    echo '<div class="mb-3"><strong>Customer:</strong> '.htmlspecialchars($order['name']).' ('.htmlspecialchars($order['email']).')</div>';
    echo '<div class="mb-3"><strong>Date:</strong> '.htmlspecialchars($order['order_date']).'</div>';
    echo '<div class="mb-3"><strong>Address:</strong> '.htmlspecialchars($order['shipping_address']).'</div>';
    echo '<div class="mb-3"><strong>Status:</strong> '.htmlspecialchars($order['status']).'</div>';
    echo '<div class="mb-3"><strong>Products:</strong>';
    echo '<table class="table table-sm table-bordered align-middle"><thead><tr><th>Image</th><th>Name</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>';
    $total = 0;
    foreach($productNames as $i => $pname) {
        $img = trim($productImages[$i] ?? '');
        $qty = intval($productQty[$i] ?? 0);
        $price = intval($productPrice[$i] ?? 0);
        $subtotal = $qty * $price;
        $total += $subtotal;
        echo '<tr>';
        echo '<td><img src="../uploads/'.htmlspecialchars($img).'" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"></td>';
        echo '<td>'.htmlspecialchars($pname).'</td>';
        echo '<td>'.$qty.'</td>';
        echo '<td>Rp '.number_format($price,0,',','.').'</td>';
        echo '<td>Rp '.number_format($subtotal,0,',','.').'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    echo '<div class="mb-2 text-end"><strong>Total: Rp '.number_format($total,0,',','.').'</strong></div>';
} else {
    echo '<div class="alert alert-warning">Order tidak ditemukan.</div>';
}
$stmt->close();
$conn->close();