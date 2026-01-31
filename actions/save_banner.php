<?php
session_start();
include __DIR__ . '/../config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $banner = $_POST['banner'];
    $user_id = $_SESSION['user_id'];
    
    $sql = "UPDATE users SET banner = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $banner, $user_id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false]);
}
?>