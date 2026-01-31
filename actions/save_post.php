<?php
session_start();
include __DIR__ . '/../config.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $user_id = $_SESSION['user_id'];

    
    if(empty($title) || empty($content) || !$category_id) {
        header("Location: ../write.php?error=All fields are required");
        exit();
    }
    $sql = "INSERT INTO posts(user_id,title,content,category_id) VALUES(?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $user_id, $title, $content, $category_id);
    
    if($stmt->execute()) {
        header("Location: ../write.php?success=1");
        exit();
    } else {
        header("Location: ../write.php?error=Failed to save post");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../write.php");
    exit();
}
?>