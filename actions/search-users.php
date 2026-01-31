<?php
session_start();
include __DIR__ . '/../config.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$search_term = '%' . $query . '%';
$sql = "SELECT id, username, avatar FROM users WHERE username LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id' => $row['id'],
        'username' => htmlspecialchars($row['username']),
        'avatar' => $row['avatar'] ?? 'avatar0.gif'
    ];
}

echo json_encode($users);
?>