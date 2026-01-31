
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

    $post_id = $_GET['id'];
    $current_user_id = $_SESSION['user_id'];

    $sql = "SELECT posts.*, users.username, users.avatar 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        header("Location: index.php");
        exit();
    }

    $post = $result->fetch_assoc();
    $firstLetter = strtoupper(substr($post['username'], 0, 1));

    $like_sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
    $like_stmt = $conn->prepare($like_sql);
    $like_stmt->bind_param("i", $post_id);
    $like_stmt->execute();
    $like_result = $like_stmt->get_result();
    $like_count = $like_result->fetch_assoc()['count'];

    $user_like_sql = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
    $user_like_stmt = $conn->prepare($user_like_sql);
    $user_like_stmt->bind_param("ii", $post_id, $current_user_id);
    $user_like_stmt->execute();
    $user_like_result = $user_like_stmt->get_result();
    $user_liked = $user_like_result->num_rows > 0;

    $comments_sql = "SELECT comments.id, comments.content, comments.user_id, comments.post_id, comments.created_at,
                    users.username, users.avatar 
                    FROM comments 
                    JOIN users ON comments.user_id = users.id 
                    WHERE comments.post_id = ? 
                    ORDER BY comments.created_at DESC";
    $comments_stmt = $conn->prepare($comments_sql);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();

    $back_url = 'index.php';
    if (!empty($_GET['back'])) {
        $back_url = $_GET['back'];
    }
    
    $current_page_url = 'view_post.php?id=' . $post_id;
    if (!empty($_GET['back'])) {
        $current_page_url .= '&back=' . urlencode($_GET['back']);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo htmlspecialchars($post['title']); ?> Post - AM</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <style>
            body {
                background: #efebe2;
                padding: 20px;
                font-family: sans-serif;
            }

            nav {
                background: #efebe2;
                position: relative;
                top: 0;
                z-index: 100;
                margin-bottom: 30px;
            }
            .nav-container {
                padding: 25px 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 30px;
                margin: 0 auto;
                gap: 30px;
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                color: #0f172a;
                border-radius: 20px;
                border: 5px solid #d5df78ee;
                background: #dff3beaa;
                font-family: 'Press Start 2P', cursive;
                box-shadow: 1px 3px 0 0 #75903c7c;
                transition: all 0.1s;
            }

            .view-post-container {
                max-width: 60vw;
                min-width: 500px;
                margin: 0 auto;
            }

            .post-content-card {
                background: #f4f5deff;
                padding: 40px;
                margin-bottom: 30px;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }
            
            .post-author {
                display: flex;
                align-items: center;
                gap: 14px;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 3px solid #ce9998ff;
            }

            .post-avatar {
                width: 38px;
                height: 38px;
                position: relative;
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 
                    inset 4px 4px 0 0 rgb(255, 252, 252),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
            }

            .post-author-info {
                flex: 1;
            }

            .post-author-name {
                font-family: 'Press Start 2P', cursive;
                font-size: 16px;
                color: #242424;
                margin-bottom: 8px;
            }

            .post-date {
                font-family: 'Press Start 2P', cursive;
                font-size: 12px;
                color: #999;
            }

            .edit-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 16px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                text-decoration: none;
                display: inline-block;
            }
            .edit-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            
            .post-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 28px;
                margin-bottom: 30px;
                color: #242424;
                line-height: 1.5;
            }
            .post-content {
                font-size: 18px;
                color: #333;
                margin-bottom: 30px;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            
            .post-image {
                width: 100%;
                height: auto;
                max-height: 600px;
                object-fit: cover;
                border-radius: 5px;
                margin-bottom: 30px;
                border: 2px solid #ce9998ff;
            }
            .post-actions {
                padding-top: 20px;
                border-top: 2px solid #ce9998ff;
                margin-top: 20px;
                display: flex;
                gap: 20px;
                align-items: center;
            }
            .like-btn {
                font-family: 'Press Start 2P', cursive;
                background: transparent; 
                color: #242424;
                padding: 5px;
                border: none;
                border-radius: 5px;
                font-size: 11px;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }
            .like-btn:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: scale(1.07);
            }
            .like-icon {
                width: 32px; 
                height: 32px;
                vertical-align: middle;
            }
            
            .back-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 16px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                text-decoration: none;
                display: inline-block;
                margin-bottom: 30px;
            }
            
            .back-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            .comments-section {
                background: #f4f5deff;
                padding: 40px;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }
            .comments-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 20px;
                margin-bottom: 30px;
                color: #242424;
            }
            .comment-form {
                margin-bottom: 40px;
            }
            .comment-form textarea {
                width: 100%;
                padding: 15px;
                border: 3px solid #ce9998ff;
                border-radius: 5px;
                font-family: sans-serif;
                font-size: 16px;
                background: white;
                box-sizing: border-box;
                min-height: 100px;
                resize: vertical;
            }
            .comment-submit-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                margin-top: 10px;
            }
            .comment-submit-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            .comment {
                background: white;
                padding: 20px;
                border: 2px solid #ce9998ff;
                border-radius: 8px;
                margin-bottom: 15px;
            }
            .comment-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #f4f5deff;
            }
            .comment-avatar {
                width: 35px;
                height: 35px;
                position: relative;
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 
                    inset 4px 4px 0 0 rgb(255, 252, 252),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
            }
            .comment-author {
                font-family: 'Press Start 2P', cursive;
                font-size: 12px;
                color: #242424;
                flex: 1;
            }
            .comment-date {
                font-family: 'Press Start 2P', cursive;
                font-size: 9px;
                color: #999;
            }
            .comment-content {
                font-size: 15px;
                color: #333;
                align-items: center;
                text-align: center;
            }
            .no-comments {
                font-family: 'Press Start 2P', cursive;
                font-size: 13px;
                color: #999;
                padding: 30px;
            }
            .comment-delete-btn {
                font-family: 'Press Start 2P', cursive;
                background: #c24242c2;
                color: white;
                padding: 6px 12px;
                border-radius: 5px;
                border: 2px solid #986768;
                cursor: pointer;
                font-size: 9px;
                transition: background 0.3s;
            }
            .comment-delete-btn:hover {
                background: #ff6d6dff;
            }
            .comment-icon {
                width: 30px; 
                height: 50px;
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
        <div class="view-post-container">
            <button type="button" onclick="history.back(-4)" class="back-btn"><</button>
            <div class="post-content-card">
                <div class="post-author">
                    <?php if($current_user_id == $post['user_id']): ?>
                        <div class="post-avatar" onclick="window.location.href='profile_page.php?id=<?php echo $post['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer;">
                            <?php 
                            $post_avatar = $post['avatar'] ?? 'avatar0.gif';
                            if(file_exists('images/avatars/' . $post_avatar)): ?>
                                <img src="images/avatars/<?php echo $post_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <?php echo $firstLetter; ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="post-avatar" onclick="window.location.href='user_profile.php?id=<?php echo $post['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer;">
                            <?php 
                            $post_avatar = $post['avatar'] ?? 'avatar0.gif';
                            if(file_exists('images/avatars/' . $post_avatar)): ?>
                                <img src="images/avatars/<?php echo $post_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <?php echo $firstLetter; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-author-info">
                        <div class="post-author-name" onclick="window.location.href='user_profile.php?id=<?php echo $post['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer;">
                            <?php echo htmlspecialchars($post['username']); ?>
                        </div>
                        <div class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                    </div>
                    <?php if($current_user_id == $post['user_id']): ?>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-btn">Edit</a>
                    <?php endif; ?>
                </div>
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                
                <?php if(!empty($post['image']) && file_exists('images/posts/' . $post['image'])): ?>
                    <img src="images/posts/<?php echo htmlspecialchars($post['image']); ?>" alt="Post image" class="post-image">
                <?php endif; ?>
                
                <div class="post-content">
                    <?php echo $post['content']; ?>
                </div>
                
                <div class="post-actions">
                    <button class="like-btn <?php echo $user_liked ? 'liked' : ''; ?>" 
                        onclick="toggleLike(<?php echo $post['id']; ?>, this)">
                        <img src="<?php echo $user_liked ? 'images/piggy_front.gif' : 'images/piggy_back.gif'; ?>" 
                            alt="like" class="like-icon">
                        <span class="like-count"><?php echo $like_count; ?></span>
                    </button>
                    <div style="font-family: 'Press Start 2P', cursive; font-size: 11px; color: #666;">
                        <img src="images/comment_icon.png"; class="comment-icon"> <?php echo $comments_result->num_rows; ?> Comment<?php echo $comments_result->num_rows != 1 ? 's' : ''; ?>
                    </div>
                </div>
            </div>
            <div class="comments-section">
                <h2 class="comments-title">Comments</h2>
                
                <form action="actions/add_comment.php" method="POST" class="comment-form">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <textarea name="content" placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="comment-submit-btn">Post Comment</button>
                </form>
                <?php if($comments_result->num_rows > 0): ?>
                    <?php while($comment = $comments_result->fetch_assoc()): 
                        $commentFirstLetter = strtoupper(substr($comment['username'], 0, 1));
                    ?>

                        <div class="comment">
                            <div class="comment-header">

                                <?php if($comment['user_id'] == $current_user_id): ?>
                                    <div class="comment-avatar" onclick="window.location.href='profile_page.php?id=<?php echo $comment['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer; border-radius: 10px; width: 35px; height: 35px; padding: 0; overflow: hidden;">
                                        <?php 
                                        $comment_avatar = $comment['avatar'] ?? 'avatar0.gif';
                                        if(file_exists('images/avatars/' . $comment_avatar)): ?>
                                            <img src="images/avatars/<?php echo $comment_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <?php echo $commentFirstLetter; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-author" onclick="window.location.href='profile_page.php?id=<?php echo $comment['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer;">
                                        <?php echo htmlspecialchars($comment['username']); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="comment-avatar" onclick="window.location.href='user_profile.php?id=<?php echo $comment['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer; border-radius: 10px; width: 35px; height: 35px; padding: 0; overflow: hidden;">
                                        <?php 
                                        $comment_avatar = $comment['avatar'] ?? 'avatar0.gif';
                                        if(file_exists('images/avatars/' . $comment_avatar)): ?>
                                            <img src="images/avatars/<?php echo $comment_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <?php echo $commentFirstLetter; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-author" onclick="window.location.href='user_profile.php?id=<?php echo $comment['user_id']; ?>&back=<?php echo urlencode($current_page_url); ?>'" style="cursor: pointer;">
                                        <?php echo htmlspecialchars($comment['username']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="comment-date"><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></div>
                                
                                <?php if($comment['user_id'] == $current_user_id): ?>
                                    <form action="actions/delete_comment.php" method="POST" style="display: inline;" onsubmit="return confirm('Delete this comment?')">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                        <input type="hidden" name="back_url" value="<?php echo isset($_GET['back']) ? $_GET['back'] : ''; ?>">
                                        <button type="submit" class="comment-delete-btn">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="comment-content">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-comments">No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>
        </div>
        <script>
            function goBack() {
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = 'index.php';
                }
            }
        </script>
        <script src="js/toggleLike.js"></script>
    </body>
</html>