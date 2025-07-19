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

echo "<h3>Debug Information</h3>";
echo "<p>User ID: $user_id</p>";
echo "<p>Order ID: $order_id</p>";

// Test query sederhana dulu
$simple_query = "SELECT * FROM orders WHERE id = $order_id";
echo "<p>Query: $simple_query</p>";

$result = mysqli_query($conn, $simple_query);
if (!$result) {
    echo "<p style='color: red;'>Query Error: " . mysqli_error($conn) . "</p>";
    exit;
}

$order = mysqli_fetch_assoc($result);
if (!$order) {
    echo "<p style='color: red;'>Order not found</p>";
    exit;
}

echo "<h4>Order Data:</h4>";
echo "<pre>" . print_r($order, true) . "</pre>";

// Test query users
$user_query = "SELECT * FROM users WHERE id = " . $order['user_id'];
echo "<p>User Query: $user_query</p>";

$user_result = mysqli_query($conn, $user_query);
if (!$user_result) {
    echo "<p style='color: red;'>User Query Error: " . mysqli_error($conn) . "</p>";
    exit;
}

$user_data = mysqli_fetch_assoc($user_result);
echo "<h4>User Data:</h4>";
echo "<pre>" . print_r($user_data, true) . "</pre>";

// Test join query
$join_query = "SELECT o.*, u.name as user_name, u.email
               FROM orders o 
               JOIN users u ON o.user_id = u.id
               WHERE o.id = $order_id";
echo "<p>Join Query: $join_query</p>";

$join_result = mysqli_query($conn, $join_query);
if (!$join_result) {
    echo "<p style='color: red;'>Join Query Error: " . mysqli_error($conn) . "</p>";
    exit;
}

$join_data = mysqli_fetch_assoc($join_result);
echo "<h4>Join Data:</h4>";
echo "<pre>" . print_r($join_data, true) . "</pre>";

echo "<p style='color: green;'>All queries successful!</p>";
?>

<h3>Test Checkout Form</h3>
<form method="POST" action="checkout.php">
    <input type="hidden" name="order_id" value="<?= $order_id ?>">
    <p>Nama: <?= htmlspecialchars($join_data['user_name'] ?? 'N/A') ?></p>
    <p>Email: <?= htmlspecialchars($join_data['email'] ?? 'N/A') ?></p>
    <p>Total: Rp <?= number_format($join_data['total'] ?? 0, 0, ',', '.') ?></p>
    
    <label>Alamat Pengiriman:</label><br>
    <textarea name="shipping_address" rows="3" style="width: 300px;" required>
<?= htmlspecialchars($user_data['address'] ?? 'Alamat belum diisi') ?>
    </textarea><br><br>
    
    <label>Metode Pengiriman:</label><br>
    <input type="radio" name="shipping_method" value="reguler" checked> Reguler<br>
    <input type="radio" name="shipping_method" value="express"> Express<br><br>
    
    <label>Metode Pembayaran:</label><br>
    <input type="radio" name="payment_method" value="cod" checked> COD<br>
    <input type="radio" name="payment_method" value="transfer"> Transfer<br><br>
    
    <label>Catatan:</label><br>
    <textarea name="notes" rows="2" style="width: 300px;"></textarea><br><br>
    
    <button type="submit">Test Checkout</button>
</form>
