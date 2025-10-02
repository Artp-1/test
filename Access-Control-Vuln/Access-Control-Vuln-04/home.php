<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['user'];
$username = $currentUser['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
        $stmt->execute([$currentUser['id'], $comment]);
    }
}

$stmt = $pdo->query("SELECT COUNT(*) FROM comments");
if ($stmt->fetchColumn() == 0) {
    $defaults = [
        ['administrator', 'Welcome to the system!'],
        ['bob', 'Hey, this looks cool.'],
        ['maria', 'Happy to join the discussion!'],
        ['harvey', 'Nice project everyone 👋']
    ];
    foreach ($defaults as $d) {
        $uStmt = $pdo->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
        $uStmt->execute([$d[0]]);
        $u = $uStmt->fetch(PDO::FETCH_ASSOC);
        if ($u) {
            $cStmt = $pdo->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
            $cStmt->execute([$u['id'], $d[1]]);
        }
    }
}

$stmt = $pdo->query("
    SELECT comments.id, comments.comment, comments.created_at, users.username, users.id AS user_uuid
    FROM comments
    JOIN users ON comments.user_id = users.id
    ORDER BY comments.created_at DESC
");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home</title>
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
form textarea {
  width:100%; height:80px;
  border-radius:12px; border:1px solid rgba(124,92,255,.35);
  background: rgba(20,24,51,.5); color: var(--text);
  padding:.7rem; font-family:inherit;
}
button {
  margin-top:.5rem; padding:.7rem 1rem; border:none; border-radius:14px; cursor:pointer;
  font-weight:600; font-size:.95rem; color:#fff;
  background: linear-gradient(135deg, var(--accent), var(--accent-2));
  box-shadow: 0 8px 20px rgba(124,92,255,.35);
  transition: all .2s ease;
}
button:hover { transform:translateY(-1px); box-shadow:0 12px 28px rgba(124,92,255,.45); }

.comment {
  background: rgba(20,24,51,.7);
  border:1px solid rgba(124,92,255,.25);
  border-radius:14px;
  padding:1rem; margin:.7rem 0;
  min-height: 60px; display:flex; align-items:center;
  transition: background .2s ease;
  cursor: pointer;
  text-decoration: none; color: inherit;
}
.comment:hover { background: rgba(124,92,255,.15); }

.meta { font-size:.85rem; color: var(--muted); margin-bottom:.5rem; }

.button-group { margin-bottom: 1rem; display:flex; gap:.5rem; }
</style>
</head>
<body>
<div class="container">
  <h1>Welcome</h1>

  <div class="button-group">
    <form action="my-account.php" method="GET" style="display:inline;">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($currentUser['id']); ?>">
      <button type="submit">My Account</button>
    </form>
    <form action="home.php" method="GET" style="display:inline;">
      <button type="submit">Home</button>
    </form>
    <form action="logout.php" method="POST" style="display:inline;">
      <button type="submit">Logout</button>
    </form>
  </div>

  <form method="POST">
    <textarea name="comment" placeholder="Write a comment..."></textarea>
    <button type="submit">Post</button>
  </form>

  <?php foreach ($comments as $c): ?>
    <div class="comment" onclick="redirectProfile('<?php echo $c['user_uuid']; ?>')">
      <div>
        <div class="meta"><?php echo htmlspecialchars($c['username']); ?> • <?php echo $c['created_at']; ?></div>
        <div><?php echo htmlspecialchars($c['comment']); ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script>
function redirectProfile(uuid) {
    window.location.href = 'profile.php?id=' + uuid;
}
</script>
</body>
</html>
