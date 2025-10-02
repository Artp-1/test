<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$currentUser = $_SESSION['user'];

$validUsers = [];
$result = $conn->query("SELECT username FROM users");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $validUsers[] = $row['username'];
    }
}

if (isset($_GET['id']) && in_array($_GET['id'], $validUsers, true)) {
    $currentUser = $_GET['id'];
    $_SESSION['user'] = $currentUser;
} else {
    header('Location: index.php');
    exit;
}

$isAdmin = ($currentUser === 'administrator');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%),
                radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%),
                linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%);
    color: #e8ecff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.card{
    width:100%; max-width:420px;
    background: #141833; border:1px solid rgba(124,92,255,.25);
    border-radius: 20px; padding: 1.5rem 1.7rem;
    box-shadow: 0 20px 40px rgba(0,0,0,.35);
    text-align: center;
}
h2{ margin:0 0 1rem; font-size:1.5rem; font-weight:600; }
.info{
    margin-top: 15px; padding: 10px;
    background: #7c5cff; color: white; border-radius: 12px;
    cursor: pointer;
}
#hint-text{
    display:none;
    margin-top:10px;
    padding:10px;
    background:#1d2147;
    border-radius:10px;
    color:#e8ecff;
}
.admin-button{
    display: block;
    margin: 15px auto 0;
    padding: 10px;
    background: #ff6600;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
.admin-button:hover{ background: #ff4500; }
a.logout{
    display:block; margin-top:20px; color:#22d3ee; text-decoration:none;
}
a.logout:hover{ color:#7c5cff; }
</style>
</head>
<body>
<div class="card">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']) ?> </h2>

    <?php if($isAdmin): ?>
        <p>You are logged in as <strong>Administrator</strong>.</p>
        <a href="admin_panel.php" class="admin-button">Go to Admin Panel</a>
    <?php else: ?>
        <p>This is your personal account page.</p>
        <div class="info" onclick="toggleHint()">Hint</div>
        <div id="hint-text">Consider what happens if you modify the id parameter</div>
    <?php endif; ?>

    <a href="logout.php" class="logout">Logout</a>
</div>

<script>
function toggleHint(){
    let hintBox = document.getElementById("hint-text");
    hintBox.style.display = (hintBox.style.display === "block") ? "none" : "block";
}
</script>
</body>
</html>
