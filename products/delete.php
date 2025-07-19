<?php
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
}

header("Location: index.php");
