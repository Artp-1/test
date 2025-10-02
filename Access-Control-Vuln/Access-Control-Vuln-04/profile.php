<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$viewUserId = $_GET['id'] ?? null;
if (!$viewUserId) {
    die("No user specified");
}

if (!preg_match('/^[a-f0-9\-]{36}$/i', $viewUserId)) {
    die("Invalid ID");
}

$stmt = $pdo->prepare("SELECT id, username, email, bio FROM users WHERE id=? LIMIT 1");
$stmt->execute([$viewUserId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("User not found");
}

$stmt = $pdo->prepare("
    SELECT comments.comment, comments.created_at, users.username, users.id AS user_uuid
    FROM comments 
    JOIN users ON comments.user_id = users.id
    WHERE comments.user_id=? 
    ORDER BY comments.created_at DESC
");
$stmt->execute([$viewUserId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profile of <?= htmlspecialchars($user['username']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0f1226;
  --card: #141833;
  --muted: #aab0d5;
  --text: #e8ecff;
  --accent: #7c5cff;
  --accent-2: #22d3ee;
}
* { box-sizing: border-box; }
body {
  margin:0; font-family: 'Inter', system-ui, sans-serif;
  color: var(--text);
  background:
    radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%),
    radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%),
    linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%);
  display:flex; justify-content:center; padding:2rem;
}
.container {
  width:100%; max-width:600px;
  background: var(--card);
  border:1px solid rgba(124,92,255,.25);
  border-radius: 20px; padding: 1.5rem 1.7rem;
  box-shadow: 0 20px 40px rgba(0,0,0,.35);
}
h1 { margin-top:0; font-size:1.5rem; font-weight:600; }
p { margin:0.3rem 0; }
.comment {
  background: rgba(20,24,51,.7);
  border:1px solid rgba(124,92,255,.25);
  border-radius:14px;
  padding:1rem; margin:.7rem 0;
  min-height: 60px; display:flex; flex-direction:column;
  cursor:pointer;
  transition: background .2s ease;
}
.comment:hover { background: rgba(124,92,255,.15); }
.meta { font-size:.85rem; color: var(--muted); margin-bottom:.5rem; }
.button-group { margin-bottom:1rem; display:flex; gap:.5rem; }
button {
  margin-top:.5rem; padding:.5rem .7rem; border:none; border-radius:12px; cursor:pointer;
  font-weight:600; font-size:.85rem; color:#fff;
  background: linear-gradient(135deg, var(--accent), var(--accent-2));
}
button:hover { opacity:.9; }
</style>
</head>
<body>
<div class="container">
  <h1>Profile of <?= htmlspecialchars($user['username']) ?></h1>
  <p>Email: <?= htmlspecialchars($user['email']) ?></p>
  <?php if ($user['bio']): ?>
    <p>Bio: <?= htmlspecialchars($user['bio']) ?></p>
  <?php endif; ?>

  <div class="button-group">
    <form action="home.php" method="GET"><button type="submit">Home</button></form>
    <form action="my-account.php" method="GET">
      <input type="hidden" name="id" value="<?= htmlspecialchars($_SESSION['user']['id']); ?>">
      <button type="submit">My Account</button>
    </form>
    <form action="logout.php" method="POST"><button type="submit">Logout</button></form>
  </div>

  <h2>Comments by <?= htmlspecialchars($user['username']) ?></h2>
  <?php foreach($comments as $c): ?>
    <div class="comment" onclick="window.location='profile.php?id=<?= htmlspecialchars($c['user_uuid']); ?>'">
      <div class="meta"><?= htmlspecialchars($c['username']); ?> • <?= $c['created_at']; ?></div>
      <div><?= htmlspecialchars($c['comment']); ?></div>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
