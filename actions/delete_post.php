<?php
    session_start();
    include __DIR__ . '/../config.php';


    if(!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }


    if(!isset($_GET['id'])) {
        header("Location: ../index.php");
        exit();
    }

    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];


    $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);

    if($stmt->execute() && $stmt->affected_rows > 0) {
        header("Location: ../index.php?deleted=1");
    } else {
        header("Location: ../index.php?error=Could not delete post");
    }

    $stmt->close();
    $conn->close();
    exit();
?>