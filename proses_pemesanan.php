<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Ambil data produk
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($query);

    if (!$product) {
        echo "<script>alert('Produk tidak ditemukan.'); window.location.href='dashboard.php';</script>";
        exit;
    }

    $total_price = $product['price'] * $quantity;
    $status = 'pending';
    $tanggal = date('Y-m-d H:i:s');

    // Simpan ke tabel orders
    $insertOrder = mysqli_query($conn, "INSERT INTO orders (user_id, order_date, total, status, alamat) 
    VALUES ($user_id, '$tanggal', $total_price, '$status', '$alamat')");

    if ($insertOrder) {
        $order_id = mysqli_insert_id($conn);

        // Simpan ke tabel order_items
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
        VALUES ($order_id, $product_id, $quantity, {$product['price']})");

        // Tampilkan alert dan redirect setelah klik OK
        echo "<script>
            alert('Pemesanan berhasil!');
            window.location.href='orders/index.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Terjadi kesalahan saat memproses pesanan.'); window.location.href='dashboard.php';</script>";
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
