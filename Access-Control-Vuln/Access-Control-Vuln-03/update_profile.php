<?php
session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

header('Content-Type: application/json');

include 'config.php';

$user = $_SESSION['user'];
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['bio'])) {
    $bio = trim($data['bio']);
    if ($bio !== '') {
        $stmt = $pdo->prepare("UPDATE users SET bio = :bio WHERE username = :username");
        $stmt->execute([
            ':bio' => htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'),
            ':username' => $user
        ]);
    }
}

if (isset($data['roleid'])) {
    $roleid = (int)$data['roleid'];
    $stmt = $pdo->prepare("UPDATE users SET roleid = :roleid WHERE username = :username");
    $stmt->execute([
        ':roleid' => $roleid,
        ':username' => $user
    ]);
    $_SESSION['roleid'] = $roleid;
}

$stmt = $pdo->prepare("SELECT username, bio, roleid FROM users WHERE username = :username");
$stmt->execute([':username' => $user]);
$updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($updatedUser);
