<?php
    session_start();
    include 'config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if(!isset($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $profile_user_id = intval($_GET['id']);
    $current_user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $profile_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        header("Location: index.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $username = $user['username'];
    $display_avatar = $user['avatar'] ?? 'avatar0.gif';
    $banner = $user['banner'] ?? null;
    $is_own_profile = ($current_user_id == $profile_user_id);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo htmlspecialchars($username); ?>'s Profile - MALS</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Micro+5">

        <style>
            body {
                background: #efebe2;
                margin: 0;
                padding: 0;
            }

            .profile-header {
                position: relative;
                width: 100%;
                height: 300px;
                overflow: hidden;
                margin-bottom: -100px;
            }

            .banner-container {
                width: 100%;
                height: 100%;
                position: relative;
                background: linear-gradient(135deg, #667eea 0%, #4b71a2 100%);
            }

            .banner-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .back-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgba(236, 238, 214, 0.9);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 16px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.36);
                text-decoration: none;
                display: inline-block;
                margin: 20px 0 0 10px;
                transition: all 0.2s;
                position: absolute;
                top: 0;
                left: 0;
            }
            
            .back-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }

            .profile-info-container {
                max-width: 900px;
                margin: 0 auto;
                padding: 0 40px;
                position: relative;
            }

            .profile-avatar-section {
                display: flex;
                align-items: flex-end;
                gap: 30px;
                margin-bottom: 30px;
            }

            .profile-avatar-large {
                width: 150px;
                height: 150px;
                border-radius: 20px;
                border: 5px solid #efebe2;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                background: #806c4f5e;
                overflow: hidden;
                backdrop-filter: blur(20px);
            }

            .profile-avatar-large img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .profile-details {
                flex: 1;
                padding-bottom: 20px;
            }

            .profile-username {
                font-family: 'Press Start 2P', cursive;
                font-size: 32px;
                color: #242424;
                margin-bottom: 10px;
                text-shadow: 2px 2px 0 rgba(255, 255, 255, 0.8);
            }

            .profile-bio {
                font-family: 'Press Start 2P', cursive;
                font-size: 14px;
                color: #414040;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .profile-stats {
                display: flex;
                gap: 30px;
                font-family: 'Press Start 2P', cursive;
                font-size: 11px;
            }

            .stat-item {
                margin-top: 20px;
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .stat-number {
                font-size: 18px;
                color: #ce9998ff;
            }

            .stat-label {
                color: #999;
            }

            .posts-container {
                max-width: 900px;
                margin: 40px auto;
                padding: 0 40px;
            }

            .posts-heading {
                font-family: 'Press Start 2P', cursive;
                font-size: 24px;
                margin-bottom: 30px;
                color: #242424;
                text-align: center;
            }

            .post-card {
                background: #f4f5deff;
                padding: 26px;
                margin-bottom: 30px;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
                cursor: pointer;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .post-card:hover {
                transform: translateY(-2px);
                box-shadow: 0px 7px 0 0 #6145465a;
            }

            .post-author {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 18px;
                padding-bottom: 14px;
                border-bottom: 2px solid #ce9998ff;
            }

            .post-avatar {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 
                    inset 4px 4px 0 0 rgb(255, 252, 252),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
            }
            .post-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .post-author-info {
                flex: 1;
            }
            .post-author-name {
                font-family: 'Press Start 2P', cursive;
                font-size: 13px;
                color: #242424;
                margin-bottom: 5px;
            }
            .post-date {
                font-family: 'Press Start 2P', cursive;
                font-size: 10px;
                color: #999;
            }
            .post-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 18px;
                margin-bottom: 16px;
                color: #242424;
                line-height: 1.4;
            }
            .post-excerpt {
                font-size: 15px;
                line-height: 1.7;
                color: #333;
                margin-bottom: 16px;
            }
            .post-actions {
                display: flex;
                gap: 15px;
                align-items: center;
            }
            .like-btn, .comment-btn {
                background: transparent; 
                padding: 5px;
                border: none;
                font-size: 11px;
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                transition: all 0.3s;
                border-radius: 10px;
            }
            .like-btn:hover, .comment-btn:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: scale(1.07);
            }
            .like-icon {
                width: 32px; 
                height: 32px;
            }
            .comment-icon {
                width: 30px; 
                height: 40px;
            }
            .no-posts {
                font-family: 'Press Start 2P', cursive;
                text-align: center;
                font-size: 13px;
                color: #666;
                padding: 50px 20px;
            }
            .like-count {
                font-family: 'Press Start 2P', sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="profile-header">
            <div class="banner-container">
                <?php if($banner): ?>
                    <img src="images/banners/<?php echo htmlspecialchars($banner); ?>" class="banner-image" alt="Profile Banner">
                <?php endif; ?>
                <button type="button" onclick="history.back(-1)" class="back-btn"><</button>
                <div>
                </div>
            </div>
        </div>
        <div class="profile-info-container">
            <div class="profile-avatar-section">
                <div class="profile-avatar-large">
                    <?php if(file_exists('images/avatars/' . $display_avatar)): ?>
                        <img src="images/avatars/<?php echo htmlspecialchars($display_avatar); ?>" alt="Avatar">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 60px; color: #242424;">
                            <?php echo strtoupper(substr($username, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-details">
                    <h1 class="profile-username"><?php echo htmlspecialchars($username); ?></h1>
                    <p class="profile-bio"><?php echo htmlspecialchars($user['bio'] ?? 'No bio yet.'); ?></p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-number">
                                <?php
                                $post_count_sql = "SELECT COUNT(*) as count FROM posts WHERE user_id = ?";
                                $post_count_stmt = $conn->prepare($post_count_sql);
                                $post_count_stmt->bind_param("i", $profile_user_id);
                                $post_count_stmt->execute();
                                echo $post_count_stmt->get_result()->fetch_assoc()['count'];
                                ?>
                            </span>
                            <span class="stat-label">Posts</span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-number">
                                <?php
                                $like_count_sql = " SELECT COUNT(*) as count FROM likes 
                                                    JOIN posts ON likes.post_id = posts.id 
                                                    WHERE posts.user_id = ?";
                                $like_count_stmt = $conn->prepare($like_count_sql);
                                $like_count_stmt->bind_param("i", $profile_user_id);
                                $like_count_stmt->execute();
                                echo $like_count_stmt->get_result()->fetch_assoc()['count'];
                                ?>
                            </span>
                            <span class="stat-label">Likes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="posts-container">
            <h2 class="posts-heading"><?php echo $is_own_profile ? 'My' : htmlspecialchars($username) . "'s"; ?> Posts</h2>
            
            <?php
            $sql = "SELECT posts.*, users.username, users.avatar FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    WHERE posts.user_id = ? 
                    ORDER BY posts.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $profile_user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0):
                while($post = $result->fetch_assoc()):
                    $like_count_sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
                    $like_stmt = $conn->prepare($like_count_sql);
                    $like_stmt->bind_param("i", $post['id']);
                    $like_stmt->execute();
                    $like_count = $like_stmt->get_result()->fetch_assoc()['count'];
                    
                    $comment_count_sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = ?";
                    $comment_stmt = $conn->prepare($comment_count_sql);
                    $comment_stmt->bind_param("i", $post['id']);
                    $comment_stmt->execute();
                    $comment_count = $comment_stmt->get_result()->fetch_assoc()['count'];
                    
                    $user_liked = false;
                    $check_like_sql = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
                    $check_stmt = $conn->prepare($check_like_sql);
                    $check_stmt->bind_param("ii", $post['id'], $current_user_id);
                    $check_stmt->execute();
                    $user_liked = $check_stmt->get_result()->num_rows > 0;
            ?>
                    <div class="post-card" onclick="window.location.href='view_post.php?id=<?php echo $post['id']; ?>'">
                        <div class="post-author">
                            <div class="post-avatar">
                                <img src="images/avatars/<?php echo htmlspecialchars($post['avatar'] ?? 'avatar0.gif'); ?>" alt="Avatar">
                            </div>
                            <div class="post-author-info">
                                <div class="post-author-name"><?php echo htmlspecialchars($post['username']); ?></div>
                                <div class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                            </div>
                        </div>
                        <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-excerpt">
                            <?php 
                            $content = $post['content'];
                            if(strlen($content) > 200) {
                                echo substr($content, 0, 200) . '...';
                            } else {
                                echo $content;
                            }
                            ?>
                        </p>
                        <div class="post-actions" onclick="event.stopPropagation()">
                            <button class="like-btn" onclick="toggleLike(<?php echo $post['id']; ?>, this); event.stopPropagation();">
                                <img src="images/<?php echo $user_liked ? 'piggy_front.gif' : 'piggy_back.gif'; ?>" class="like-icon" alt="like">
                                <span class="like-count"><?php echo $like_count; ?></span>
                            </button>
                            
                            <button class="comment-btn" onclick="window.location.href='view_post.php?id=<?php echo $post['id']; ?>#comments'; event.stopPropagation();">
                                <img src="images/comment_icon.png" class="comment-icon" alt="comments">
                                <span class="like-count"><?php echo $comment_count; ?></span>
                            </button>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
            ?>
                <p class="no-posts"><?php echo $is_own_profile ? 'You haven\'t' : htmlspecialchars($username) . ' hasn\'t'; ?> posted anything yet.</p>
            <?php endif; ?>
        </div>
        <script src="js/toggleLike.js"></script>
    </body>
</html>