<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


$stmt = $pdo->prepare("SELECT roleid FROM users WHERE username = ?");
$stmt->execute([$_SESSION['user']]);
$currentUser = $stmt->fetch();

if (!$currentUser || $currentUser['roleid'] != 1) {
    echo "<h1>Access Denied</h1>";
    echo "<p>You are not admin.</p>";
    echo '<a href="account.php">Back to your profile</a>';
    exit;
}

$deleted = '';
if (isset($_GET['delete'])) {
    $delUser = $_GET['delete'];
    if ($delUser !== 'administrator') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$delUser]);
        $deleted = $delUser;
    }
}

$stmt = $pdo->query("SELECT username FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flag = "FLAG{!e32da_dawd@3_p@#!da}";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body{ font-family:'Inter',sans-serif; background:#0f1226; color:#e8ecff;
          display:flex; justify-content:center; align-items:flex-start; padding:2rem; }
    .card{ background:#141833; padding:1.5rem 2rem; border-radius:16px;
           max-width:400px; width:100%; box-shadow:0 20px 40px rgba(0,0,0,.35); }
    h2{ margin-top:0; }
    ul{ list-style:none; padding-left:0; }
    li{ margin-bottom:.5rem; }
    a{ color:#22d3ee; text-decoration:none; }
    a:hover{ text-decoration:underline; }
    .deleted{ margin-top:1rem; color:#10b981; font-weight:600; }
    .flag{ margin-top:1.5rem; padding:1rem; background:#222244;
           border:1px solid #7c5cff; border-radius:12px;
           color:#22d3ee; font-weight:600; text-align:center; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Admin Panel</h2>
    <ul>
      <?php foreach($users as $user): ?>
        <li>
          <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
          <?php if($user['username'] !== 'administrator'): ?>
             - <a href="?delete=<?= urlencode($user['username']) ?>">Delete</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if($deleted): ?>
      <div class="deleted">Deleted user: <?= htmlspecialchars($deleted, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="flag"> Secret Flag: <?= htmlspecialchars($flag, ENT_QUOTES, 'UTF-8') ?></div>
  </div>
</body>
</html>
