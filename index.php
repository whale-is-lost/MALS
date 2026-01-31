<?php
    session_start();
    include 'config.php';

    $username = null;
    
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $username = $user['username'];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AM-Homepage</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/popup.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Micro+5">

        <style>
            nav {
                background: #efebe2;
                position: relative;
                top: 0;
                z-index: 100;
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
                border: 5px solid #bedf78ee;
                background: #dff3beaa;
                font-family: 'Press Start 2P', cursive;
                box-shadow: 1px 3px 0 0 #74903c7c;
                transition: all 0.1s;
            }
            .popup {
                display: none; 
                position: absolute;
                top: 0;
                left: 0;
                justify-content: center;
                align-items: center;
                z-index: 99999;
            }

            .popup.active {
                display: flex;
            }

            .popup-content {
                background: #f9f6f2;
                padding: 0;
                border-radius: 8px;
                max-width: 450px;
                width: 90%;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border: 3px solid #73c2cc;
                box-shadow: 
                    inset 2px 2px 0 0 #d4c4a8,
                    inset -2px -2px 0 0 #6b5539,
                    6px 6px 0 0 rgba(0, 0, 0, 0.2);
                font-family: 'Press Start 2P', cursive;
            }

            .popup-header {
                background:  #73c2cc ;
                color: white;
                padding: 12px 15px;
                margin: 0;
                font-size: 11px;
                border-bottom: 5px solid #55acb9;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-radius: 4px 4px 0 0;
                text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.3);
            }

            .popup-header-title {
                font-size: 10px;
                letter-spacing: 1px;
            }

            .close-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 10px 6px;
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
                    4px 4px 0 0 #466a8de2;
            }

            .close-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }

            .popup-body {
                padding: 30px;
                background: #f9f6f2;
                border-radius: 0 0 4px 4px;
            }

            .avatar-selector {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15px;
                margin-bottom: 20px;
            }

            .avatar-container {
                width: 120px;
                height: 120px;
                position: relative;
                overflow: hidden;
                background-color: rgba(199, 194, 194, 0.74);
                border-radius: 20px;
                box-shadow: 
                    inset 2px 2px 0 0 rgba(255, 255, 255, 0.68),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
            }

            .avatar-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                position: absolute;
                opacity: 0;
                transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            }
            .avatar-image.active {
                opacity: 1;
                transform: scale(1);
            }
            .avatar-image.fade-out-left {
                animation: fadeOutLeft 0.3s forwards;
            }
            .avatar-image.fade-out-right {
                animation: fadeOutRight 0.3s forwards;
            }

            .avatar-image.fade-in-left {
                animation: fadeInLeft 0.3s forwards;
            }

            .avatar-image.fade-in-right {
                animation: fadeInRight 0.3s forwards;
            }
            @keyframes fadeOutLeft {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(-30px);
                }
            }

            @keyframes fadeOutRight {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(30px);
                }
            }
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .avatar-nav-btn {
                background: linear-gradient(to bottom, #ffd89b 0%, #ffb76b 100%);
                border: 3px solid #c9a67b;
                border-radius: 5px;
                width: 40px;
                height: 40px;
                cursor: pointer;
                font-size: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(202, 175, 129, 0.53);
                transition: all 0.1s;
            }

            .avatar-nav-btn:hover {
                background: #d7dab9ff;
                transform: translateY(-1px);
            }

            .avatar-nav-btn:active {
                transform: translateY(1px);
                box-shadow: 
                    inset -1px -1px 0 0 #ffe4c4,
                    inset 1px 1px 0 0 #8b7355;
            }

            .avatar-save-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 10px 6px;
                text-decoration: none;
                font-size: 10px;
                transition: background 0.3s;
                top: 10px;
                right: 10px;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #966566;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(202, 175, 129, 0.53);
                width: 40%;
                margin-bottom: 6px;
                margin-left: 115px;
            }

            .avatar-save-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.02);
            }

            .pop-info {
                background: white;
                border: 3px solid #c9a67b;
                border-radius: 4px;
                padding: 15px;
                margin: 15px 0;
                box-shadow: 
                    inset 1px 1px 0 0 #e8d4c0,
                    inset -1px -1px 0 0 #a88d6f;
            }

            .pop-info p {
                font-size: 12px;
                color: #4a3f2e;
                margin: 0;
                text-align: center;
            }

            .popup-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 20px;
            }

            .signout-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 10px 6px;
                text-decoration: none;
                font-size: 10px;
                transition: background 0.3s;
                top: 10px;
                right: 10px;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #966566;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(202, 175, 129, 0.53);
            }

            .signout-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.02);
            }

            .popup-pattern {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-image: 
                    radial-gradient(circle at 10px 10px, rgba(232, 157, 168, 0.12) 2px, transparent 2px);
                background-size: 20px 20px;
                pointer-events: none;
                border-radius: 4px;
            }


            .home {
                max-width: 60vw; 
                min-width: 500px;
                margin: 0 auto;
                padding: 40px 20px;
            }

            .posts-heading {
                font-family: 'Press Start 2P', cursive;
                font-size: 24px;
                margin-bottom: 35px;
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

            .post-image {
                width: 100%;
                height: auto;
                max-height: 400px;
                object-fit: cover;
                border-radius: 5px;
                margin-bottom: 16px;
            }

            .no-posts {
                font-family: 'Press Start 2P', cursive;
                text-align: center;
                font-size: 13px;
                color: #666;
                padding: 50px 20px;
            }

            .login-prompt {
                max-width: 40vw;
                min-width: 500px;
                margin: 80px auto;
                padding: 50px 40px;
                background: #f4f5deff;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
                text-align: center;
            }

            .login-prompt h2 {
                font-family: 'Press Start 2P', cursive;
                font-size: 22px;
                margin-bottom: 25px;
                color: #242424;
            }

            .login-prompt p {
                font-family: 'Press Start 2P', cursive;
                font-size: 13px;
                margin-bottom: 35px;
                color: #666;
                line-height: 1.7;
            }

            .edit-btn {
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
            }

            .edit-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }

            .post-actions {
                display: flex;
                gap: 15px;
                align-items: center;
            }

            .like-btn {
                font-family: 'Press Start 2P', cursive;
                background: transparent; 
                color: #242424;
                padding: 5px;
                margin-top: 10px;
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

            .comment-indicator {
                font-family: 'Press Start 2P', cursive;
                font-size: 11px;
                color: #666;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .comment-btn {
                font-family: 'Press Start 2P', cursive;
                background: transparent; 
                color: #242424;
                padding: 2px;
                margin-top: 10px;
                border: none;
                border-radius: 5px;
                font-size: 11px;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
            }
            .comment-icon {
                width: 30px; 
                height: 40px;
                vertical-align: middle;
            }
            .comment-btn:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: scale(1.07);
            }
            .main-container {
                display: flex;
                gap: 30px;
                max-width: 90vw;
                margin: 0 auto;
                padding: 40px 20px;
            }

            .sidebar {
                width: 280px;
                flex-shrink: 0;
                position: sticky;
                top: 20px;
                height: fit-content;
            }

            .sidebar-section {
                background: #f4f5deff;
                padding: 20px;
                margin-bottom: 20px;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }

            .sidebar-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 14px;
                color: #242424;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #ce9998ff;
            }

            .filter-option {
                display: flex;
                align-items: center;
                padding: 10px;
                margin-bottom: 8px;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.2s;
                font-family: 'Press Start 2P', cursive;
                font-size: 10px;
                color: #333;
            }

            .filter-option:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: translateX(5px);
            }

            .filter-option.active {
                background: #ce9998ff;
                color: white;
            }

            .filter-option input[type="radio"] {
                margin-right: 10px;
                cursor: pointer;
            }

            .category-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px;
                margin-bottom: 8px;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.2s;
                font-family: 'Press Start 2P', cursive;
                font-size: 10px;
                color: #333;
            }

            .category-item:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: translateX(5px);
            }

            .category-item.active {
                background: #ce9998ff;
                color: white;
            }

            .category-count {
                background: rgba(0, 0, 0, 0.1);
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 9px;
            }

            .home {
                flex: 1;
                max-width: 80vw;
                min-width: 500px;
                padding: 0;
            }

            .clear-filters-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 9px;
                padding: 8px 12px;
                cursor: pointer;
                border-radius: 5px;
                width: 100%;
                margin-top: 10px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
            }

            .clear-filters-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.02);
            }
            .pop-btn {
                font-family: 'Press Start 2P', cursive;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 13px;
                padding: 8px 16px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(119, 148, 81, 0.49);
                text-decoration: none;
                display: inline-block;
                transition: all 0.2s;
            }
            
            .pop-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            
            .user-search-container {
                position: relative;
                width: 100%;
                max-width: 280px;
                margin-left: 70px;
                margin-bottom: 30px;
            }

            #userSearch {
                width: 100%;
                padding: 12px 16px;
                font-family: 'Press Start 2P', cursive;
                font-size: 10px;
                border: 2px solid #ce9998ff;
                box-shadow: 5px 5px 0 0 #6145465a;
                border-radius: 5px;
                background: #f4f5deff;
            }

            #userSearch:focus {
                background: rgb(251, 253, 236);
                transition: all 0.2s;
                outline: none;
                transform: scale(1.02);
            }

            .search-results {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #f1fdd7f8;
                border: 2px solid #bddd76ee;
                border-radius: 7px;
                margin-top: 4px;
                max-height: 300px;
                overflow-y: auto;
                display: none;
                box-shadow: 4px 4px 0 0 rgba(0, 0, 0, 0.2);
                z-index: 1000;
                transition: all 0.2s;
            }

            .search-results.show {
                display: block;
            }

            .search-result-item {
                padding: 12px 16px;
                cursor: pointer;
                border-bottom: 1px solid #ddd;
                font-family: 'Press Start 2P', cursive;
                font-size: 9px;
                transition: background 0.1s;
            }

            .search-result-item:hover {
                background: #e5f8bcf8;
            }

            .search-result-item:last-child {
                border-bottom: none;
            }

            .user-avatar {
                width: 24px;
                height: 24px;
                border-radius: 5px;
                box-shadow: 
                    inset 4px 4px 0 0 rgb(255, 252, 252),
                    inset -2px -2px 0 0 rgba(0, 0, 0, 0.2),
                    3px 3px 0 0 rgba(0, 0, 0, 0.1);
                display: inline-block;
                margin-right: 10px;
                vertical-align: middle;
            }

            .no-results {
                padding: 16px;
                text-align: center;
                color: #666;
                font-family: 'Press Start 2P', cursive;
                font-size: 8px;
            }

        </style>
    </head> 

    <body>
        <nav>
            <div class="nav-container">
                <div class="logo" style="align-items: center; color: #34330a; font-family: 'Micro 5', sans-serif; font-size: 50px;"><img src="images/birbspin.gif" alt="birb" style="width: 50px; height: 50px; display: block;">MALS</div>
                <div class="nav-links">
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile_page.php">My Page</a>
                        <a href="write.php">Write</a>
                        <div class="icon" onclick="openPopup('profile-popup')">
                            <button class="pop-btn">Profile</button>
                        </div>
                    <?php else: ?>
                        <a onclick="openPopup('about-popup')" style="cursor: pointer;">About</a>
                        <a class="sign-in-btn" onclick="window.location.href='login.php'">Sign in</a>
                        <button class="pop-btn" onclick="window.location.href='signup.php'">Get started</button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>


        <!--POPUP PROFILE-->
        <?php
            $current_avatar = 'avatar0.gif';
            if(isset($_SESSION['user_id'])) {
                $avatar_sql = "SELECT avatar FROM users WHERE id = ?";
                $avatar_stmt = $conn->prepare($avatar_sql);
                $avatar_stmt->bind_param("i", $_SESSION['user_id']);
                $avatar_stmt->execute();
                $avatar_result = $avatar_stmt->get_result();
                if($avatar_result->num_rows > 0) {
                    $avatar_data = $avatar_result->fetch_assoc();
                    $current_avatar = $avatar_data['avatar'] ?? 'avatar0.gif';
                }
            }
            $total_avatars = 10; 
        ?>

        <div class="popup" id="profile-popup">
            <div class="popup-content" id="draggable-window">
                <div class="popup-header">
                    <span class="popup-header-title">PROFILE</span>
                    <button class="close-btn" onclick="closePopup('profile-popup')">[x]</button>
                </div>
                
                <div class="popup-body">
                    <div class="avatar-selector">
                        <button class="avatar-nav-btn" onclick="previousAvatar()">◀</button>
                        <div class="avatar-container" id="avatar-container">
                            <?php for($i = 0; $i <= $total_avatars; $i++): ?>
                                <img src="images/avatars/avatar<?php echo $i; ?>.gif" 
                                    class="avatar-image <?php echo ($current_avatar == 'avatar'.$i.'.gif') ? 'active' : ''; ?>" 
                                    data-avatar="avatar<?php echo $i; ?>.gif"
                                    alt="Avatar <?php echo $i; ?>">
                            <?php endfor; ?>
                        </div>
                        
                        <button class="avatar-nav-btn" onclick="nextAvatar()">▶</button>
                    </div>
                    
                    <button class="avatar-save-btn" onclick="saveAvatar()">Save Avatar</button>
                    
                    <div class="pop-info">
                        <p><?php echo htmlspecialchars($username); ?></p>
                    </div>
                    
                    <div>
                        <button class="signout-btn" onclick="window.location.href='edit_profile.php'">
                            Edit Profile
                        </button>
                        <button class="signout-btn" onclick="window.location.href='signout.php'">
                            Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ABOUT POPUP -->
        <div class="popup" id="about-popup">
            <div class="popup-content">
                <div class="popup-header">
                    <span class="popup-header-title">ABOUT</span>
                    <button class="close-btn" onclick="closePopup('about-popup')">[x]</button>
                </div>
                <div class="popup-body">
                    <p style="font-family: 'Courier New', Courier, monospace;">This project involves the development of a simple and interactive Blogging Platform with core php along with html, CSS and JavaScript. This platform is designed to allow users to create manage and share blog posts in a user-friendly environment. <br>The visual design for this project has been heavily inspired by aesthetic of games like Harvest Town and other arcade games from the 2010s that were built with pixels. </p>
                </div>
            </div>
        </div>


        <div class="Hero">
        <?php if(isset($_SESSION['user_id'])): ?>
            <h1 style="font-family: 'Press Start 2P', sans-serif; margin: 40px 0; text-align: center;">Posts</h1>
            <div class="user-search-container">
                <input type="text" id="userSearch" placeholder="Search users..." autocomplete="off" data-currentUserId="<?php echo $_SESSION['user_id']; ?>">
                <div id="searchResults" class="search-results"></div>
            </div>
            <div class="main-container">

                <!--SIDEBAR-->
                <aside class="sidebar">
                    <div class="sidebar-section">
                        <div class="sidebar-title">Sort By</div>
                        <div class="filter-option <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'active' : ''; ?>" 
                            onclick="applyFilter('sort', 'newest')">
                            <input type="radio" name="sort" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'checked' : ''; ?>>
                            Newest First
                        </div>
                        <div class="filter-option <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'active' : ''; ?>" 
                            onclick="applyFilter('sort', 'oldest')">
                            <input type="radio" name="sort" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'checked' : ''; ?>>
                            Oldest First
                        </div>
                        <div class="filter-option <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'popular') ? 'active' : ''; ?>" 
                            onclick="applyFilter('sort', 'popular')">
                            <input type="radio" name="sort" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'popular') ? 'checked' : ''; ?>>
                            Most Liked
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-title">Categories</div>
                        <?php
                        $categories_sql = "SELECT categories.*, COUNT(posts.id) as post_count 
                                            FROM categories 
                                            LEFT JOIN posts ON categories.id = posts.category_id 
                                            GROUP BY categories.id 
                                            ORDER BY categories.name";
                        $categories_result = $conn->query($categories_sql);
                        
                        while($category = $categories_result->fetch_assoc()):
                        ?>
                            <div class="category-item <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>" 
                                onclick="applyFilter('category', <?php echo $category['id']; ?>)">
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                                <span class="category-count"><?php echo $category['post_count']; ?></span>
                            </div>
                        <?php endwhile; ?>
                        
                        <button class="clear-filters-btn" onclick="clearFilters()">Clear Filters</button>
                    </div>
                </aside>

                <!--HOME CONTENT-->
                <div class="home">
                    
                    <?php
                    $where_clause = "WHERE 1=1";
                    
                    if(isset($_GET['category']) && is_numeric($_GET['category'])) {
                        $category_filter = intval($_GET['category']);
                        $where_clause .= " AND posts.category_id = $category_filter";
                    }
                    
                    $order_clause = "ORDER BY posts.created_at DESC";
                    if(isset($_GET['sort'])) {
                        if($_GET['sort'] == 'oldest') {
                            $order_clause = "ORDER BY posts.created_at ASC";
                        } elseif($_GET['sort'] == 'popular') {
                            $order_clause = "ORDER BY like_count DESC, posts.created_at DESC";
                        }
                    }
                    
                    $sql = "SELECT posts.*, users.username, users.avatar, 
                            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
                            FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            $where_clause 
                            $order_clause";
                    $result = $conn->query($sql);
                    
                    if($result->num_rows > 0) {
                        while($post = $result->fetch_assoc()) {
                            $firstLetter = strtoupper(substr($post['username'], 0, 1));
                            
                            $like_count = $post['like_count'];
                            
                            $user_liked = false;
                            if(isset($_SESSION['user_id'])) {
                                $check_like_sql = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
                                $check_stmt = $conn->prepare($check_like_sql);
                                $check_stmt->bind_param("ii", $post['id'], $_SESSION['user_id']);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();
                                $user_liked = $check_result->num_rows > 0;
                            }

                            $comment_count_sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = ?";
                            $comment_stmt = $conn->prepare($comment_count_sql);
                            $comment_stmt->bind_param("i", $post['id']);
                            $comment_stmt->execute();
                            $comment_count_result = $comment_stmt->get_result();
                            $comment_count = $comment_count_result->fetch_assoc()['count'];
                    ?>
                            <div class="post-card" onclick="window.location.href='view_post.php?id=<?php echo $post['id']; ?>'">
                                <div class="post-author">

                                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                                        <div class="post-avatar" onclick="window.location.href='profile_page.php?id=<?php echo $post['user_id']; ?>'; event.stopPropagation();" style="cursor: pointer;">
                                            <?php 
                                            $post_avatar = $post['avatar'] ?? 'avatar0.gif';
                                            if(file_exists('images/avatars/' . $post_avatar)): ?>
                                                <img src="images/avatars/<?php echo $post_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <?php echo $firstLetter; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="post-author-info">
                                            <div class="post-author-name" onclick="window.location.href='profile_page.php?id=<?php echo $post['user_id']; ?>'; event.stopPropagation();" style="cursor: pointer;">
                                                <?php echo htmlspecialchars($post['username']); ?>
                                            </div>
                                            <div class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="post-avatar" onclick="window.location.href='user_profile.php?id=<?php echo $post['user_id']; ?>'; event.stopPropagation();" style="cursor: pointer;">
                                            <?php 
                                            $post_avatar = $post['avatar'] ?? 'avatar0.gif';
                                            if(file_exists('images/avatars/' . $post_avatar)): ?>
                                                <img src="images/avatars/<?php echo $post_avatar; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <?php echo $firstLetter; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="post-author-info">
                                            <div class="post-author-name" onclick="window.location.href='user_profile.php?id=<?php echo $post['user_id']; ?>'; event.stopPropagation();" style="cursor: pointer;">
                                                <?php echo htmlspecialchars($post['username']); ?>
                                            </div>
                                            <div class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-btn" onclick="event.stopPropagation()">Edit</a>
                                    <?php endif; ?>
                            </div>

                                <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                
                                <?php if(!empty($post['image']) && file_exists('images/posts/' . $post['image'])): ?>
                                    <img src="images/posts/<?php echo htmlspecialchars($post['image']); ?>" alt="Post image" class="post-image">
                                <?php endif; ?>
                                
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
                                    <button class="like-btn <?php echo $user_liked ? 'liked' : ''; ?>" 
                                        onclick="toggleLike(<?php echo $post['id']; ?>, this); event.stopPropagation();">
                                        <img src="<?php echo $user_liked ? 'images/piggy_front.gif' : 'images/piggy_back.gif'; ?>" 
                                            alt="like" class="like-icon">
                                        <span class="like-count"><?php echo $like_count; ?></span>
                                    </button>
                                    
                                    <div class="comment-indicator">
                                        <button class="comment-btn" onclick="window.location.href='view_post.php?id=<?php echo $post['id']; ?>#comments'; event.stopPropagation();">
                                        <img src="images/comment_icon.png" class="comment-icon"> <?php echo $comment_count; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p class="no-posts">No posts found with these filters.</p>';
                    }
                    ?>
                </div>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <h2>Welcome!</h2>
                <p>Please sign in to read all posts and join our community.</p>
                <button class="pop-btn" onclick="window.location.href='login.php'">Sign In</button>
                <button class="pop-btn" onclick="window.location.href='signup.php'">Get Started</button>
            </div>
        <?php endif; ?>
        <script src="js/profile.js"></script>
        <script src="js/popup.js"></script>
        <script src="js/toggleLike.js"></script>
        <script src="js/avatar.js"></script>
        <script src="js/filter.js"></script>
        <script src="js/sound.js"></script>
        <script src="js/user_search.js"></script>
        <!--<script src="js/grass.js"></script>-->
    </body>
</html>



