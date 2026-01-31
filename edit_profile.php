<?php
    session_start();
    include 'config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];


    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Edit Profile - AM</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <style>
            body {
                background: #efebe2;
                padding: 20px;
            }
            .profile-edit-container {
                max-width: 600px;
                margin: 50px auto;
                padding: 40px;
                background: #f4f5deff;
                border: 4px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }
            
            .profile-edit-container h1 {
                font-family: 'Press Start 2P', cursive;
                font-size: 24px;
                margin-bottom: 30px;
                color: #242424;
                text-align: center;
            }
            .profile-avatar {
                width: 100px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 30px;
                position: relative;
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 
                    inset 4px 4px 0 0 rgb(255, 252, 252),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
            }
            
            .form-group {
                margin-bottom: 25px;
            }
            
            .form-group label {
                display: block;
                font-family: 'Press Start 2P', cursive;
                font-size: 13px;
                margin-bottom: 10px;
                color: #242424;
            }
            
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="password"],
            .form-group textarea {
                width: 100%;
                padding: 15px;
                border: 3px solid #ce9998ff;
                border-radius: 5px;
                font-family: sans-serif;
                font-size: 16px;
                background: white;
                box-sizing: border-box;
            }
            .form-group textarea {
                min-height: 100px;
                resize: vertical;
            }
            .form-note {
                font-family: 'Press Start 2P', cursive;
                font-size: 10px;
                color: #999;
                margin-top: 5px;
            }
            
            .button-group {
                display: flex;
                gap: 15px;
                margin-top: 30px;
            }
            
            .submit-btn, .cancel-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 6px 12px;
                text-decoration: none;
                font-size: 10px;
                transition: background 0.3s;
                margin-left: auto;
                top: 10px;
                right: 10px;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                flex: 1;
            }
            .submit-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05)
            }
            
            .cancel-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05)
            }
            
            .error {
                background: #ffcccc;
                color: #cc0000;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                font-family: 'Press Start 2P', cursive;
                font-size: 11px;
            }
            
            .success {
                background: #ccffcc;
                color: #006600;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                font-family: 'Press Start 2P', cursive;
                font-size: 11px;
            }

            .section-divider {
                border-top: 3px solid #ce9998ff;
                margin: 30px 0;
            }

            .section-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 16px;
                margin-bottom: 20px;
                color: #242424;
            }
        </style>
    </head>
    <body>
        <div class="profile-edit-container">
            <h1>Edit Profile</h1>
            
            <!-- Avatar-->
            <div class="profile-avatar">
                <?php 
                    if(isset($_SESSION['user_id'])) {
                        $avatar_sql = "SELECT avatar FROM users WHERE id = ?";
                        $avatar_stmt = $conn->prepare($avatar_sql);
                        $avatar_stmt->bind_param("i", $_SESSION['user_id']);
                        $avatar_stmt->execute();
                        $avatar_result = $avatar_stmt->get_result();
                        if($avatar_result->num_rows > 0) {
                            $avatar_data = $avatar_result->fetch_assoc();
                            $display_avatar = $avatar_data['avatar'] ?? 'avatar0.gif';
                        } else {
                            $display_avatar = 'avatar0.gif';
                        }
                    } else {
                        $display_avatar = 'avatar0.gif';
                    }
                    if(file_exists('images/avatars/' . $display_avatar)): ?>
                        <img src="images/avatars/<?php echo $display_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <?php echo $firstLetter; ?>
                    <?php endif; ?>
            </div>
            
            <?php
            if(isset($_GET['success'])) {
                echo '<div class="success">Profile updated successfully!</div>';
            }
            if(isset($_GET['error'])) {
                echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            
            <form action="actions/update_profile.php" method="POST">
                <div class="section-title">Profile</div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required 
                        value="<?php echo htmlspecialchars($user['name']); ?>">
                    <div class="form-note"></div>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required 
                        value="<?php echo htmlspecialchars($user['username']); ?>">
                    <div class="form-note"></div>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="bio">Bio(Optional)</label>
                    <input type="text" id="bio" name="bio" required value="<?php echo htmlspecialchars($user['bio']); ?>">
                </div>
                <div class="section-divider"></div>

                <!-- Change Password Section -->
                <div class="section-title">Change Password (Optional)</div>
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password">
                    <div class="form-note">Leave blank to keep current password</div>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <div class="button-group">
                    <button type="submit" class="submit-btn">Save Changes</button>
                    <a href="index.php" class="cancel-btn">Done</a>
                </div>
            </form>
        </div>
    </body>
</html>