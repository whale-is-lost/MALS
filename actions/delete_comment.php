<?php
session_start();
include __DIR__ . '/../config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $comment_id, $user_id);

    if($stmt->execute()) {
        header("Location: ../view_post.php?id=$post_id");
    } else {
        header("Location: ../view_post.php?id=$post_id&error=Failed to delete comment");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>