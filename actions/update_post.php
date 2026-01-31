<?php
session_start();
include __DIR__ . '/../config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['user_id']);

    if($stmt->execute()) {
        header("Location: ../view_post.php?id=$post_id");
    } else {
        header("Location: ../edit_post.php?id=$post_id&error=Failed to update post");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>