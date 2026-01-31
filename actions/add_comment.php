<?php
    session_start();
    include __DIR__ . '/../config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user_id'];
        $content = trim($_POST['content']);
        
        if(empty($content)) {
            header("Location: ../view_post.php?id=$post_id&error=Comment cannot be empty");
            exit();
        }
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $post_id, $user_id, $content);
        
        if($stmt->execute()) {
            header("Location: ../view_post.php?id=$post_id#comments");
        } else {
            header("Location: ../view_post.php?id=$post_id&error=Failed to post comment");
        }
        
        $stmt->close();
        $conn->close();
        exit();
    }
?>