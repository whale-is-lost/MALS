<?php
    session_start();
    include __DIR__ . '/../config.php';
    
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {   
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user_id'];

        $check_sql = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('ii', $post_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if($check_result->num_rows > 0) {
            $delete_sql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param('ii', $post_id, $user_id);
            $delete_stmt->execute();
            $liked = false;
        } else {
            $insert_sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('ii', $post_id, $user_id);
            $insert_stmt->execute();
            $liked = true;
        }
        
        $count_sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("i", $post_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $like_count = $count_result->fetch_assoc()['count'];
        
        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'count' => $like_count
        ]);
        
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
?>