<?php
session_start();
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if ($check && mysqli_num_rows($check) > 0) {
        $error = "Email sudah terdaftar. Silakan gunakan email lain.";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'customer')");
        if ($query) {
            $_SESSION['success'] = "Pendaftaran berhasil. Silakan login.";
            header("Location: login.php");
            exit;
        } else {
            $error = "Pendaftaran gagal. Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - MS Glow Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fff0f5; font-family: 'Segoe UI', sans-serif; }
    .form-container {
      max-width: 500px;
      margin: 60px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .btn-daftar {
      background-color: #ff69b4;
      color: white;
      border: none;
    }
    .btn-daftar:hover { background-color: #ff1493; }
  </style>
</head>
<body>
  <div class="form-container">
    <h3 class="text-center mb-4">Daftar Akun Baru</h3>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-daftar w-100">Daftar</button>
    </form>

    <div class="text-center mt-3">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
  </div>
</body>
</html>
