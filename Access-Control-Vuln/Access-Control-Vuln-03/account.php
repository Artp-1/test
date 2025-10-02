<?php
session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$users = json_decode(file_get_contents("users.json"), true);
$user = $_SESSION['user'];

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['bio'])) {
    $bio = trim($data['bio']);
    if ($bio !== '') {
        $users[$user]['bio'] = htmlspecialchars($bio);
    }
}

if (isset($data['roleid'])) {
    $users[$user]['roleid'] = (int)$data['roleid'];
    $_SESSION['roleid'] = (int)$data['roleid'];
}

file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

header('Content-Type: application/json');
echo json_encode($users[$user]);
