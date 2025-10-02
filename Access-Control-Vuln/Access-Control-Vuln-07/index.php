<?php
session_start();
include 'config.php';

// Determine if user is trying to access admin panel
$access_admin = basename($_SERVER['PHP_SELF']) === 'admin.php';

// Only allow if X-Original-URL header is correct, but only for admin.php
$allowed = false;
if ($access_admin && isset($_SERVER['HTTP_X_ORIGINAL_URL']) &&
    ($_SERVER['HTTP_X_ORIGINAL_URL'] === '/admin' || $_SERVER['HTTP_X_ORIGINAL_URL'] === '/admin.php')) {
    $allowed = true;
}

// Handle user deletion (only if allowed)
$delete_msg = "";
if ($allowed && isset($_POST['delete_user'])) {
    $user_to_delete = $_POST['delete_user'];
    if ($user_to_delete !== 'admin') {
        $stmt = $conn->prepare("DELETE FROM users WHERE username=?");
        $stmt->bind_param("s", $user_to_delete);
        $stmt->execute();
        $delete_msg = "User '" . htmlspecialchars($user_to_delete) . "' deleted successfully!";
    } else {
        $delete_msg = "Cannot delete the admin account!";
    }
}

// Fetch users (only if allowed)
$users = [];
if ($allowed) {
    $res = $conn->query("SELECT username FROM users");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $users[] = $row['username'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SOCORA LAB</title>
<style>
body { margin: 0; font-family: Arial, sans-serif; background-color: #000; color: #fff; }
.navbar { background-color: #111; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
.navbar .logo { font-weight: bold; font-size: 18px; color: #00aaff; }
.navbar .nav-buttons { display: flex; gap: 15px; }
.navbar .nav-buttons a { background: #222; padding: 8px 15px; border-radius: 5px; text-decoration: none; color: #fff; font-size: 14px; transition: background 0.3s; }
.navbar .nav-buttons a:hover { background: #555; }
.content { padding: 40px; text-align: center; }
.content h1 { font-size: 28px; color: #00aaff; }
.content p, .content td, .content th { color: #ccc; font-size: 16px; }
table { margin: 20px auto; border-collapse: collapse; width: 50%; }
th, td { padding: 10px; border: 1px solid #444; }
button { padding: 5px 10px; background: #222; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #555; }
.flag { font-size: 22px; color: #ff4444; font-weight: bold; margin-top: 20px; }
</style>
</head>
<body>
<div class="navbar">
    <div class="logo">SOCORA LAB</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
        <a href="admin.php">Admin Panel</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="content">
<?php if ($access_admin): ?>
    <?php if ($allowed): ?>
        <h1>Admin Panel</h1>
        <p>Welcome to the admin panel! You can manage users here.</p>

        <?php if (!empty($delete_msg)): ?>
            <p style="color:#00ff00;"><?= $delete_msg ?></p>
        <?php endif; ?>

        <h2>Users</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u) ?></td>
                <td>
                    <?php if ($u !== 'admin'): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_user" value="<?= htmlspecialchars($u) ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="flag">flag{DN2N_k23J_kdan_DW123}</div>
    <?php else: ?>
        <h1>Access Denied</h1>
        <p>You are not authorized.</p>
    <?php endif; ?>
<?php else: ?>
    <h1>Welcome to SOCORA LAB</h1>
    <p>This is your home page</p>
<?php endif; ?>
</div>
</body>
</html>
