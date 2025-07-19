<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
// Handle delete user
if (isset($_GET['delete'])) {
  $uid = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM users WHERE id=$uid");
  header("Location: users.php");
  exit;
}
// Handle edit user (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
  $edit_id = intval($_POST['edit_id']);
  $name = mysqli_real_escape_string($conn, $_POST['edit_name']);
  $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
  $role = mysqli_real_escape_string($conn, $_POST['edit_role']);
  $update_query = "UPDATE users SET name='$name', email='$email', role='$role'";
  if (!empty($_POST['edit_password'])) {
    $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);
    $update_query .= ", password='$password'";
  }
  $update_query .= " WHERE id=$edit_id";
  mysqli_query($conn, $update_query);
  echo "<script>window.location='users.php';</script>";
  exit;
}
// Fetch users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>User Management - Admin</title>
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
        <li class="nav-item"><a class="nav-link active" href="users.php"><i class="fas fa-users"></i> User Management</a></li>
        <li class="nav-item"><a class="nav-link" href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
      </ul>
      <span class="navbar-text d-none d-lg-inline" style="color:#d63384;font-weight:500;">MS Glow Admin</span>
    </div>
  </div>
</nav>
<div class="container py-4">
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="fw-bold mb-0" style="color:#d63384">User Management</h2>
      <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus"></i> Add User</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; while($u = mysqli_fetch_assoc($users)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role'] ?? '-') ?></td>
            <td>
              <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?= $u['id'] ?>" data-name="<?= htmlspecialchars($u['name']) ?>" data-email="<?= htmlspecialchars($u['email']) ?>" data-role="<?= htmlspecialchars($u['role']) ?>"><i class="fas fa-edit"></i> Edit</a>
              <a href="users.php?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i> Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="users.php">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select class="form-select" id="role" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="users.php">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_name" class="form-label">Name</label>
              <input type="text" class="form-control" id="edit_name" name="edit_name" required>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_email" name="edit_email" required>
            </div>
            <div class="mb-3">
              <label for="edit_password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
              <input type="password" class="form-control" id="edit_password" name="edit_password">
            </div>
            <div class="mb-3">
              <label for="edit_role" class="form-label">Role</label>
              <select class="form-select" id="edit_role" name="edit_role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Pass user data to edit modal
var editUserModal = document.getElementById('editUserModal');
editUserModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var id = button.getAttribute('data-id');
  var name = button.getAttribute('data-name');
  var email = button.getAttribute('data-email');
  var role = button.getAttribute('data-role');
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_name').value = name;
  document.getElementById('edit_email').value = email;
  document.getElementById('edit_role').value = role;
  document.getElementById('edit_password').value = '';
});
</script>
</body>
</html>
<?php
// Handle add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']) && !isset($_POST['edit_id'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = mysqli_real_escape_string($conn, $_POST['role']);
  mysqli_query($conn, "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
  echo "<script>window.location='users.php';</script>";
  exit;
}
?>