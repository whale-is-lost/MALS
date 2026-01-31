<?php
session_start();
include __DIR__ . '/../config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $bio = trim($_POST['bio']);

    $sql = "UPDATE users SET full_name = ?, bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $full_name, $bio, $_SESSION['user_id']);

    if($stmt->execute()) {
        header("Location: ../profile_page.php?id=" . $_SESSION['user_id']);
    } else {
        header("Location: ../edit_profile.php?error=Failed to update profile");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>