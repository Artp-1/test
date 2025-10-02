<?php
session_start();
include 'config.php';

$viewUserId = $_GET['id'] ?? $_SESSION['user']['id'] ?? null;
if (!$viewUserId) die("No user specified");

if (!preg_match('/^[a-f0-9\-]{36}$/i', $viewUserId)) {
    die("Invalid ID");
}

$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id=? LIMIT 1");
$stmt->execute([$viewUserId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("User not found");

if ($user['username'] === 'administrator') {
    header("Location: admin-panel.php?id=" . $user['id']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Account of <?= htmlspecialchars($user['username']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{ margin:0; font-family:'Inter',sans-serif; background:#0f1226; color:#e8ecff; display:flex; justify-content:center; align-items:center; height:100vh; }
.card{ background:#141833; padding:2rem; border-radius:20px; text-align:center; box-shadow:0 20px 40px rgba(0,0,0,.35); width:100%; max-width:400px; }
h1{ margin-bottom:1rem; font-size:1.5rem; }
button{ margin:0.5rem; padding:.7rem 1rem; border:none; border-radius:14px; cursor:pointer; font-weight:600; color:#fff; background: linear-gradient(135deg,#7c5cff,#22d3ee); }
button:hover{ opacity:.9; }
a{ text-decoration:none; color:inherit; }
</style>
</head>
<body>
<div class="card">
  <h1>Account of <?= htmlspecialchars($user['username']) ?></h1>
  <p>Email: <?= htmlspecialchars($user['email']) ?></p>

  <p><a href="logout.php"><button>Logout</button></a></p>
  <p><a href="home.php"><button>Home</button></a></p>
</div>
</body>
</html>
