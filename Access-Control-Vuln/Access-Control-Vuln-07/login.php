<?php
session_start();
include 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['user'] = $username;
        header("Location: admin.php"); 
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SOCORA LAB - Login</title>
<style>
body { margin: 0; font-family: Arial, sans-serif; background-color: #000; color: #fff; }
.container { max-width: 400px; margin: 100px auto; padding: 30px; background-color: #111; border: 1px solid #333; border-radius: 8px; text-align: center; }
.logo { font-size: 28px; font-weight: bold; color: #00aaff; margin-bottom: 20px; }
input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 6px; border: none; background: #222; color: #fff; font-size: 14px; }
button.signin-btn { width: 100%; padding: 12px; border: none; border-radius: 6px; background-color: #222; color: #fff; font-weight: bold; cursor: pointer; transition: background 0.3s; }
button.signin-btn:hover { background-color: #555; }
.error { color: #ff4444; margin-bottom: 15px; font-size: 14px; }
.password-input { display: flex; align-items: center; }
.password-input input { flex: 1; }
.password-input button { background: none; border: none; color: #fff; cursor: pointer; margin-left: -30px; font-size: 16px; }
.links { margin-top: 15px; font-size: 14px; }
.links a { color: #00aaff; text-decoration: none; }
.footer { margin-top: 20px; font-size: 12px; color: #777; }
</style>
<script>
function togglePassword() {
    const pwd = document.querySelector('input[name="password"]');
    pwd.type = (pwd.type === "password") ? "text" : "password";
}
</script>
</head>
<body>
<div class="container">
    <div class="logo">SOCORA LAB</div>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter your username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="password-input">
          <input type="password" name="password" placeholder="Enter your password" required>
          <button type="button" onclick="togglePassword()">👁</button>
        </div>
      </div>
      <button class="signin-btn" type="submit">Sign In</button>
    </form>

    <div class="links">
      <a href="#">Forgot Password?</a>
    </div>
    <div class="footer">© 2025 SOCORA LAB</div>
</div>
</body>
</html>
