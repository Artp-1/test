<?php
session_start();
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    $roleid = isset($_POST['roleid']) ? intval($_POST['roleid']) : $user['roleid'];

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        
        $stmt = $pdo->prepare("UPDATE users SET email = ?, roleid = ? WHERE username = ?");
        $stmt->execute([$email, $roleid, $_SESSION['user']]);

        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['user']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['roleid'] = $user['roleid'] ?? 0;
        $_SESSION['email'] = $user['email'];

        
        header("Location: account.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Update Email</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{ --bg:#0f1226; --card:#141833; --text:#e8ecff; --accent:#7c5cff; --accent-2:#22d3ee; }
body{ margin:0; font-family:'Inter', sans-serif; color: var(--text);
  background: radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%),
              radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%),
              linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%);
  display:flex; align-items:center; justify-content:center; height:100vh; padding:1rem;}
.card{ width:100%; max-width:400px; background: var(--card); border-radius:20px; padding:1.5rem 1.7rem; }
h2{ margin:0 0 1rem; font-size:1.5rem; font-weight:600; }
form{ display:flex; flex-direction:column; gap:.9rem; }
input{ padding:.65rem .8rem; border-radius:12px; border:1px solid rgba(124,92,255,.35);
      background: rgba(20,24,51,.5); color: var(--text); }
button{ margin-top:.5rem; padding:.7rem 1rem; border:none; border-radius:14px;
        cursor:pointer; font-weight:600; color:#fff;
        background: linear-gradient(135deg, var(--accent), var(--accent-2)); }
button:hover{ transform:translateY(-1px); }
.error{ background: rgba(239,68,68,.15); padding:.8rem; border-radius:12px; margin-bottom:1rem; color:#ef4444; }
</style>
</head>
<body>
  <div class="card">
    <h2>Update Email</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <form method="post" action="">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      <button type="submit">Update Email</button>
    </form>
    <p style="margin-top:1rem; font-size:0.85rem; color:#aab0d5;">
    </p>
  </div>
</body>
</html>
