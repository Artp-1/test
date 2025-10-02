<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$_SESSION['roleid'] = $user['roleid'] ?? 0;


$isAdmin = $_SESSION['roleid'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>My Account</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg: #0f1226;
  --card: #141833;
  --muted: #aab0d5;
  --text: #e8ecff;
  --accent: #7c5cff;
  --accent-2: #22d3ee;
}
*{ box-sizing: border-box; }
body{
  margin:0; font-family: 'Inter', system-ui, sans-serif;
  color: var(--text);
  background: radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%),
              radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%),
              linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%);
  display:flex; align-items:center; justify-content:center; height:100vh; padding:1rem;
}
.card{
  width:100%; max-width:400px;
  background: var(--card); border:1px solid rgba(124,92,255,.25);
  border-radius: 20px; padding: 1.5rem 1.7rem;
  box-shadow: 0 20px 40px rgba(0,0,0,.35);
}
h2{ margin:0 0 1rem; font-size:1.5rem; font-weight:600; }
p{ margin:0.5rem 0; }
a{
  display:inline-block; margin-top:1rem; text-decoration:none;
  color: var(--accent); font-weight:600;
}
</style>
</head>
<body>
  <div class="card">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Roleid:</strong> <?= htmlspecialchars($user['roleid']) ?></p>

    <a href="update_user.php">Update Profile</a><br>
    <a href="logout.php">Logout</a><br>

    <?php if($isAdmin): ?>
      <a href="admin_panel.php">Admin Panel</a>
    <?php endif; ?>
  </div>
</body>
</html>
