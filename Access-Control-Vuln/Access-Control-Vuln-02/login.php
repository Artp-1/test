<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    
    $stmt = $conn->prepare("SELECT username, password, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
       
        if ($row['password'] === $password) {
           
            if ($row['is_admin'] == 1) {
                setcookie("Admin", "true", time() + 3600, "/");
            } else {
                setcookie("Admin", "false", time() + 3600, "/");
            }

            $_SESSION['user'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }

    $error = "Invalid credentials!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <!-- Google Font -->
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
      width:100%; max-width:400px;
      background: var(--card); border:1px solid rgba(124,92,255,.25);
      border-radius: 20px; padding: 1.5rem 1.7rem;
      box-shadow: 0 20px 40px rgba(0,0,0,.35);
    }
    h2{ margin:0 0 1rem; font-size:1.5rem; font-weight:600; }
    form{ display:flex; flex-direction:column; gap:.9rem; }
    label{ font-weight:500; font-size:.95rem; color: var(--muted); }
    input{
      padding:.65rem .8rem; border-radius:12px; border:1px solid rgba(124,92,255,.35);
      background: rgba(20,24,51,.5); color: var(--text);
    }
    button{
      margin-top:.5rem; padding:.7rem 1rem; border:none; border-radius:14px; cursor:pointer;
      font-weight:600; font-size:.95rem; color:#fff;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      box-shadow: 0 8px 20px rgba(124,92,255,.35);
      transition: all .2s ease;
    }
    button:hover{ transform:translateY(-1px); box-shadow:0 12px 28px rgba(124,92,255,.45); }
    .error{ background: rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.35);
      color: var(--danger); padding:.8rem; border-radius:12px; margin-bottom:1rem; font-weight:500; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
      <div>
        <label for="username">Username</label>
        <input id="username" name="username" required>
      </div>
      <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
      </div>
      <button>Login</button>
    </form>
  </div>
</body>
</html>