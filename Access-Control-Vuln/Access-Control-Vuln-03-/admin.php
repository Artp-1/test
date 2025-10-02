<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['roleid'] != 2) {
    echo "Access denied. You are not admin.";
    exit;
}

if (isset($_GET['delete'])) {
    $del = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$del]);
    echo "User $del deleted.";
    exit;
}

$stmt = $pdo->query("SELECT username, email, roleid FROM users");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel</title></head>
<body>
<h2>Admin Panel</h2>
<table border="1">
<tr><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>
<?php foreach ($users as $u): ?>
<tr>
  <td><?= htmlspecialchars($u['username']) ?></td>
  <td><?= htmlspecialchars($u['email']) ?></td>
  <td><?= htmlspecialchars($u['roleid']) ?></td>
  <td>
    <?php if ($u['username'] !== 'administrator'): ?>
      <a href="admin.php?delete=<?= urlencode($u['username']) ?>">Delete</a>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="logout.php">Logout</a></p>
</body>
</html>
