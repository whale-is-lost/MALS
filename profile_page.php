<?php
    session_start();
    include 'config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

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

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $username = $user['username'];
    $display_avatar = $user['avatar'] ?? 'avatar0.gif';
    $banner = $user['banner'] ?? null;
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
                margin-bottom: 0;
                gap: 30px;
                border-radius: 20px;
                border: 5px solid #bedf78ee;
                background: #dff3beaa;
                font-family: 'Press Start 2P', cursive;
                box-shadow: 1px 3px 0 0 #74903c7c;
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

            .banner-upload-btn {
                position: absolute;
                top: 20px;
                right: 20px;
                font-family: 'Press Start 2P', cursive;
                background: rgba(236, 238, 214, 0.9);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 10px 15px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.38);
                transition: all 0.2s;
            }

            .banner-upload-btn:hover {
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

            .edit-profile-btn {
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
                margin-left: auto;
            }

            .edit-profile-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
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
                    4px 4px 0 0 rgba(179, 181, 141, 0.36);
                text-decoration: none;
                display: inline-block;
                margin-top: 20px;
                margin-left: 10px;
                transition: all 0.2s;
            }
            
            .back-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
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

            .edit-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 6px 12px;
                text-decoration: none;
                font-size: 10px;
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

            .like-btn:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: scale(1.07);
            }

            .like-icon {
                width: 32px; 
                height: 32px;
            }
            

            .comment-btn {
                background: transparent; 
                padding: 2px;
                border: none;
                font-size: 11px;
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
                transition: all 0.3s;
                border-radius: 10px;
            }

            .comment-btn:hover {
                background: rgba(206, 153, 152, 0.2);
                transform: scale(1.07);
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

            .banner-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                z-index: 10000;
                justify-content: center;
                align-items: center;
            }

            .banner-modal.active {
                display: flex;
            }

            .banner-modal-content {
                background: #f9f6f2;
                padding: 40px;
                border-radius: 10px;
                border: 4px solid #ce9998ff;
                max-width: 600px;
                width: 90%;
            }

            .banner-modal-title {
                font-family: 'Press Start 2P', cursive;
                font-size: 18px;
                margin-bottom: 30px;
                text-align: center;
            }

            .banner-options {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }

            .banner-option {
                aspect-ratio: 16/9;
                border-radius: 8px;
                cursor: pointer;
                border: 3px solid transparent;
                transition: all 0.2s;
                overflow: hidden;
            }

            .banner-option:hover {
                border-color: #ce9998ff;
                transform: scale(1.05);
            }

            .banner-option.selected {
                border-color: #ce9998ff;
                box-shadow: 0 0 0 3px rgba(206, 153, 152, 0.3);
            }

            .banner-option img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .modal-buttons {
                display: flex;
                gap: 15px;
                justify-content: center;
            }

            .modal-btn {
                font-family: 'Press Start 2P', cursive;
                padding: 12px 24px;
                border-radius: 5px;
                font-size: 11px;
                cursor: pointer;
                border: 3px solid #986768;
                transition: all 0.2s;
            }

            .save-banner-btn {
                background: #90ee90;
                color: #2d4a2d;
            }

            .save-banner-btn:hover {
                background: #a8f5a8;
            }

            .cancel-btn {
                background: rgb(236, 238, 214);
                color: #c24242c2;
            }

            .cancel-btn:hover {
                background: #d7dab9ff;
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
                    4px 4px 0 0 rgba(179, 181, 141, 0.36);
                text-decoration: none;
                display: inline-block;
                margin-top: 20px;
                margin-left: 10px;
                transition: all 0.2s;
            }
            
            .back-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            .home-btn {
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
                    4px 4px 0 0 rgba(179, 181, 141, 0.36);
                text-decoration: none;
                display: inline-block;
                margin-top: 20px;
                transition: all 0.2s;
            }
            
            .home-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            .pop-btn {
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
                    4px 4px 0 0 rgba(179, 181, 141, 0.36);
                text-decoration: none;
                display: inline-block;
                margin-top: 20px;
                margin-left: 20px;
                transition: all 0.2s;
            }
            
            .pop-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
            }
            .icon {
                position: absolute;
                top: 2px;
                right: max;
                font-family: 'Press Start 2P', cursive;
                background: none;
                color: none;
                border: none;
                font-size: 10px;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: none;
                transition: all 0.2s;
            }
        </style>
    </head>
    <body>
        <div class="profile-header">
            <div class="banner-container">
                <?php if($banner): ?>
                    <img src="images/banners/<?php echo htmlspecialchars($banner); ?>" class="banner-image" alt="Profile Banner">
                <?php endif; ?>
                <div class="icon" >
                    <a onclick="history.back(-1)" class="back-btn" style="padding: 12px; padding-bottom: 14px;"><</a>
                    <a href="index.php" class="home-btn"><img src="images/home.png" alt="Home" style="width: 20px; height: 20px;vertical-align: middle;"></a>
                    <button class="pop-btn" onclick="openPopup('profile-popup')" style="padding: 12px;">Profile</button>
                </div>
                <button class="banner-upload-btn" onclick="openBannerModal()">Change Banner</button>
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
                                $post_count_stmt->bind_param("i", $user_id);
                                $post_count_stmt->execute();
                                $post_count_result = $post_count_stmt->get_result();
                                echo $post_count_result->fetch_assoc()['count'];
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
                                $like_count_stmt->bind_param("i", $user_id);
                                $like_count_stmt->execute();
                                $like_count_result = $like_count_stmt->get_result();
                                echo $like_count_result->fetch_assoc()['count'];
                                ?>
                            </span>
                            <span class="stat-label">Likes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Profile popup-->
        <?php
            $current_avatar = $display_avatar ?? 'avatar0.gif';
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

        <div class="posts-container">
            <div class="write-btn">
                <p style="font-family: 'Press Start 2P', sans serif; font-style: bold; color: #9790908a; ">
                    <a href="write.php" class="back-btn" style="padding: 12px; margin-bottom:20px;">+</a>
                    New Post
                </p>
            </div>
            <?php
            $sql = "SELECT posts.*, users.username, users.avatar FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    WHERE posts.user_id = ? 
                    ORDER BY posts.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
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
                    $check_stmt->bind_param("ii", $post['id'], $user_id);
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
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-btn" onclick="event.stopPropagation()">Edit</a>
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
                <p class="no-posts">No posts yet. Write your first post!</p>
            <?php endif; ?>
        </div>

        <div class="banner-modal" id="banner-modal">
            <div class="banner-modal-content">
                <h2 class="banner-modal-title">Choose a Banner</h2>
                
                <div class="banner-options">
                    <?php for($i = 1; $i <= 9; $i++): ?>
                        <div class="banner-option" onclick="selectBanner('banner<?php echo $i; ?>.gif')" data-banner="banner<?php echo $i; ?>.gif">
                            <img src="images/banners/banner<?php echo $i; ?>.gif" alt="Banner <?php echo $i; ?>">
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="modal-buttons">
                    <button class="modal-btn save-banner-btn" onclick="saveBanner()">Save</button>
                    <button class="modal-btn cancel-btn" onclick="closeBannerModal()">Cancel</button>
                </div>
            </div>
        </div>
        <script src="js/profile.js"></script>
        <script src="js/toggleLike.js"></script>
        <script src="js/avatar.js"></script>
        <script src="js/filter.js"></script>
        <script>
            let selectedBanner = null;

            function openBannerModal() {
                document.getElementById('banner-modal').classList.add('active');
            }

            function closeBannerModal() {
                document.getElementById('banner-modal').classList.remove('active');
                selectedBanner = null;
                document.querySelectorAll('.banner-option').forEach(opt => opt.classList.remove('selected'));
            }

            function selectBanner(banner) {
                selectedBanner = banner;
                document.querySelectorAll('.banner-option').forEach(opt => opt.classList.remove('selected'));
                document.querySelector(`[data-banner="${banner}"]`).classList.add('selected');
            }

            function saveBanner() {
                if(!selectedBanner) {
                    alert('Please select a banner first!');
                    return;
                }

                fetch('actions/save_banner.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'banner=' + selectedBanner
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Failed to save banner');
                    }
                });
            }
        </script>
    </body>
</html>