<?php
include '../config/database.php';


$newPassword = 'admin123';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

// Ganti email admin sesuai user kamu
$email = 'admin@admin.com';

$sql = "UPDATE users SET password='$hash' WHERE email='$email'";

if (mysqli_query($conn, $sql)) {
    echo "Password admin berhasil direset ke: $newPassword <br>";
    echo "Hash baru: $hash";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
