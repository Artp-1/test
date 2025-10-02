<?php
session_start();
include 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$currentSessionUser = $_SESSION['user'];

$notFound = false;

if (isset($_GET['id'])) {
    $viewUsername = $_GET['id'];

    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username=?");
    if (!$stmt) { die("Prepare failed: (" . $conn->errno . ") " . $conn->error); }
    $stmt->bind_param("s", $viewUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    if ($userData) {
        $currentUser = $userData['username'];
        $currentPass = $userData['password'];
    } else {
        $notFound = true;
        $currentUser = $viewUsername;
        $currentPass = '';
    }
} else {
    $currentUser = $currentSessionUser;
    $currentPass = '';
    header('Location: update_profile.php?id=' . urlencode($currentUser));
    exit;
}

// Update password vetëm nëse user ekziston dhe përputhet me session
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$notFound && $currentUser === $currentSessionUser) {
    $newPass = $_POST['new_password'] ?? '';

    if (empty($newPass)) {
        $message = "<div class='error'>Please fill in the password field.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        if (!$stmt) { die("Prepare failed: (" . $conn->errno . ") " . $conn->error); }
        $stmt->bind_param("ss", $newPass, $currentUser);
        if ($stmt->execute()) {
            $message = "<div class='success'>Password updated successfully!</div>";
            $currentPass = $newPass;
        } else {
            $message = "<div class='error'>Error updating password.</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update Password</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; background: radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.12), transparent 60%), radial-gradient(1000px 500px at 100% 0%, rgba(124,92,255,.15), transparent 55%), linear-gradient(180deg, #0c0f23 0%, #0b0e1f 100%); color: #e8ecff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
.card { width:100%; max-width:420px; background: #141833; border:1px solid rgba(124,92,255,.25); border-radius: 20px; padding: 1.5rem 1.7rem; box-shadow: 0 20px 40px rgba(0,0,0,.35); text-align: center; }
h2{ margin:0 0 1rem; font-size:1.5rem; font-weight:600; }
input { width: 100%; padding: 10px; margin: 8px 0; border-radius: 8px; border: none; }
button { margin-top: 10px; padding: 10px; width: 100%; border: none; border-radius: 8px; background: #22d3ee; color: #0b0e1f; font-weight: 600; cursor: pointer; }
button:hover { background: #0ea5e9; }
.success, .error { margin-top: 15px; padding: 10px; border-radius: 10px; }
.success { background: #16a34a; color: white; }
.error { background: #dc2626; color: white; }
a.back { display: block; margin-top: 15px; color: #22d3ee; text-decoration: none; }
a.back:hover { color: #7c5cff; }
</style>
</head>
<body>
<div class="card">
    <?php if ($notFound): ?>
        <h2>User Not Found</h2>
        <a href="user.php" class="back">← Back to Dashboard</a>
    <?php else: ?>
        <h2>Update Password for <?= htmlspecialchars($currentUser) ?></h2>
        <?= $message ?>
        <form method="POST">
            <input type="password" name="new_password" 
                   placeholder="Current / New Password" 
                   value="<?= htmlspecialchars($currentPass) ?>" 
                   <?= $currentUser !== $currentSessionUser ? 'readonly' : '' ?>>
            <button type="submit" <?= $currentUser !== $currentSessionUser ? 'disabled' : '' ?>>Update</button>
        </form>
        <a href="user.php" class="back">← Back to Dashboard</a>
    <?php endif; ?>
</div>
</body>
</html>
