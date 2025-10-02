<?php
session_start();
if (!isset($_SESSION['roleid']) || $_SESSION['roleid'] != 2) {
    echo "Access denied. You are not admin.";
    exit;
}

$users = json_decode(file_get_contents("users.json"), true);

if (isset($_GET['delete'])) {
    $del = $_GET['delete'];
    if ($del !== 'administrator' && isset($users[$del])) {
        unset($users[$del]);
        file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
        echo "Deleted user: " . htmlspecialchars($del);
    }
}
?>

<h2>Admin Panel</h2>
<ul>
<?php foreach($users as $u => $info): ?>
  <li><?php echo $u; ?> - <a href="admin.php?delete=<?php echo $u; ?>">Delete</a></li>
<?php endforeach; ?>
</ul>
