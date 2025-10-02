<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === 'true';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg: #0f1226;
      --card: #141833;
      --muted: #aab0d5;
      --text: #e8ecff;
      --accent: #7c5cff;
      --accent-2: #22d3ee;
      --danger: #ef4444;
      --ok: #10b981;
      --ring: rgba(124,92,255,.45);
    }
    *{ box-sizing: border-box; }
    body{
      margin:0; font-family: 'Inter', system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%),
        radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%),
        linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%);
      display:flex; align-items:center; justify-content:center; height:100vh; padding:1rem;
    }
    .card{
      width:100%; max-width:420px;
      background: var(--card); border:1px solid rgba(124,92,255,.25);
      border-radius: 20px; padding: 1.7rem;
      box-shadow: 0 20px 40px rgba(0,0,0,.35);
      text-align:center;
    }
    h1{ margin:0 0 1rem; font-size:1.6rem; font-weight:600; }
    p{ color: var(--muted); margin:.5rem 0; }
    a{
      display:inline-block; margin-top:1.2rem; padding:.7rem 1rem;
      border-radius:14px; font-weight:600; font-size:.95rem; text-decoration:none;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color:#fff; box-shadow:0 8px 20px rgba(124,92,255,.35);
      transition:all .2s ease;
    }
    a:hover{ transform:translateY(-1px); box-shadow:0 12px 28px rgba(124,92,255,.45); }
  </style>
</head>
<body>
  <div class="card">
    <h1>Welcome, <?=htmlspecialchars($_SESSION['user'])?></h1>
    <p>You are logged in successfully.</p>

    <?php if ($isAdmin): ?>
      <a href="/admin.php">Go to Admin Panel</a><br>
    <?php endif; ?>

    <a href="/logout.php" style="margin-top:0.8rem; background:linear-gradient(135deg, var(--danger), #f87171);">Log out</a>
  </div>
</body>
</html>
