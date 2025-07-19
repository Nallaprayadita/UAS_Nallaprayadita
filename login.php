<?php
session_start();
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $email = mysqli_real_escape_string($conn, $email);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        if (password_verify($password, $user['password'])) {

            if ($user['role'] === 'admin') {
                $_SESSION['admin'] = $user;
                header("Location: admin/orders.php");
                exit;
            } else {
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit;
            }

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
    if ($user) {
    echo "Input password: " . $password . "<br>";
    echo "DB hash: " . $user['password'] . "<br>";

    if (password_verify($password, $user['password'])) {
        echo "Password cocok!"; exit;
    } else {
        echo "Password TIDAK cocok!"; exit;
    }
}

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - MS Glow Store</title>
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
    .btn-login {
      background-color: #ff69b4;
      color: white;
      border: none;
    }
    .btn-login:hover {
      background-color: #ff1493;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h3 class="text-center mb-4">Login</h3>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-login w-100">Login</button>
    </form>

    <div class="text-center mt-3">
      Belum punya akun? <a href="register.php">Daftar sekarang</a>
    </div>
  </div>
</body>
</html>
