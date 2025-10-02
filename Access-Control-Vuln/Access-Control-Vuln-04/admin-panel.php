<?php
session_start();
include 'config.php';

$viewUserId = $_GET['id'] ?? $_SESSION['user']['id'] ?? null;
if (!$viewUserId) {
    die("No user specified");
}

if (!preg_match('/^[a-f0-9\-]{36}$/i', $viewUserId)) {
    die("Invalid user ID");
}

$stmt = $pdo->prepare("SELECT id, username FROM users WHERE id=? LIMIT 1");
$stmt->execute([$viewUserId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

if ($user['username'] !== 'administrator') {
    die("Access Denied<br>You are not logged in as administrator.");
}

$flag = 'flag{thi124k_asfn!@}';
$deleted = '';
$stmt = $pdo->query("SELECT id, username FROM users ORDER BY username ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $delId = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id=? LIMIT 1");
    $stmt->execute([$delId]);
    $delUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($delUser && $delUser['username'] !== 'administrator') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        if ($stmt->execute([$delId])) {
            $deleted = $delUser['username'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Panel</title>
<style>
body{
    font-family:'Inter',sans-serif; 
    background:#0f1226; 
    color:#e8ecff; 
    display:flex; 
    justify-content:center; 
    padding:2rem;
}
.card{ 
    background:#141833; 
    padding:2rem; 
    border-radius:20px; 
    max-width:500px; 
    width:100%; 
    box-shadow:0 20px 40px rgba(0,0,0,.35);
}
h2{ margin-top:0;}
ul{ list-style:none; padding-left:0;}
li{ margin-bottom:.7rem;}
a{ color:#22d3ee; text-decoration:none;}
a:hover{text-decoration:underline;}
.deleted{ margin-top:1rem; color:#10b981; font-weight:600;}
.flag{ margin-top:1.5rem; padding:1rem; background:#222244; border:1px solid #7c5cff; border-radius:12px; color:#22d3ee; text-align:center;}
.buttons{ margin-top:1.5rem; display:flex; justify-content:center; gap:1rem;}
button{ padding:.7rem 1rem; border:none; border-radius:14px; cursor:pointer; font-weight:600; color:#fff; background: linear-gradient(135deg,#7c5cff,#22d3ee);}
button:hover{ opacity:.9;}
</style>
</head>
<body>
<div class="card">
<h2>Admin Panel (<?= htmlspecialchars($user['username']); ?>)</h2>
<ul>
<?php foreach($users as $u): ?>
<li>
<?= htmlspecialchars($u['username']); ?>
<?php if($u['username'] !== 'administrator'): ?>
 - <a href="?id=<?= urlencode($user['id']) ?>&delete=<?= urlencode($u['id']); ?>">Delete</a>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<?php if($deleted): ?>
<div class="deleted">Deleted user: <?= htmlspecialchars($deleted); ?></div>
<?php endif; ?>

<div class="flag">Secret Flag: <?= htmlspecialchars($flag); ?></div>

<div class="buttons">
    <a href="home.php"><button>Home</button></a>
    <a href="logout.php"><button>Logout</button></a>
</div>
</div>
</body>
</html>
