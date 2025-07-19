<?php
session_start();
session_unset(); // Hapus semua data dari session
session_destroy(); // Hancurkan session

// Arahkan kembali ke halaman login
header("Location: login.php");
exit;
?>
