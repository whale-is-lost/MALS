<?php
    session_start();
    include 'config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Write Post - AM</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <style>
            
            nav {
                background: #efebe2;
                position: sticky;
                top: 0;
                z-index: 100;
            }
            .popup {
                display: none; 
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: none;
                justify-content: center;
                align-items: center;
                z-index: 99999;
            }
            .popup-header {
                background: #ce9998ff;
                color: white;
                padding: 18px;
                margin: -40px -40px 10px -40px;
                font-size: 10px;
                border-bottom: 4px solid #AF797C;
                align-content: center;
            }
            .popup.active {
                display: flex;
            }

            .popup-content {
                background: #f4f5deff;
                padding: 40px;
                border-radius: 5px;
                max-width: 500px;
                width: 90%;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-150%, -300%);
                border: 2px solid #ce9998ff;;
                box-shadow: 5px 5px 0 0 #6145465a;
                position: relative;
                font-family: 'Press Start 2P', cursive;
            }

            .popup-content p {
                font-size: 16px;
                color: #242424;
                margin-top: 20px;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 10px;
                background: #f4f5deff;
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 8px;
                cursor: pointer;
                font-family: 'Press Start 2P', cursive;
                border-radius: 5px;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 #9f736dff;
                z-index: 10;
            }

            .close-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05)
            }

            .signout-btn {
                align-self: auto;
                top: 10px;
                right: 10px;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 8px;
                cursor: pointer;
                font-family: 'Press Start 2P', cursive;
                border-radius: 5px;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                margin-left: auto;
                display: flex;
                gap: 30px;
                align-items: center;
            }
            .signout-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05)
            }

            .write-container {
                padding: 40px;
                background: #f4f5deff;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }
            
            .write-container h1 {
                font-family: 'Press Start 2P', cursive;
                font-size: 24px;
                margin-bottom: 30px;
                color: #242424;
            }
            
            .form-group {
                margin-bottom: 25px;
            }
            
            .form-group label {
                display: block;
                font-family: 'Press Start 2P', cursive;
                font-size: 14px;
                margin-bottom: 10px;
                color: #242424;
            }
            
            .form-group input[type="text"],

            .form-group textarea {
                width: 100%;
                padding: 15px;
                border: 3px solid #ce9998ff;
                border-radius: 5px;
                font-family: sans-serif;
                font-size: 16px;
                background: white;
            }
            
            .form-group textarea {
                min-height: 300px;
                resize: vertical;
            }
            
            .submit-btn {
                align-self: auto;
                top: 10px;
                right: 10px;
                background: rgb(236, 238, 214);
                color: #c24242c2;
                border: 3px solid #986768;
                font-size: 10px;
                padding: 8px 8px;
                cursor: pointer;
                font-family: 'Press Start 2P', cursive;
                border-radius: 5px;
                border-radius: 5px;
                box-shadow: 
                    inset -4px -4px 0 0 #c3c594ff, 
                    inset 4px 4px 0 0 #fefff5ff,   
                    4px 4px 0 0 rgba(179, 181, 141, 0.72);
                margin-left: auto;
                display: flex;
                gap: 30px;
                align-items: center;
            }
            
            .submit-btn:hover {
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
                font-size: 12px;
            }
            
            .success {
                background: #ccffcc;
                color: #006600;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                font-family: 'Press Start 2P', cursive;
                font-size: 12px;
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
            .write-post-container {
                max-width: 68vw;
                min-width: 500px;
                margin: 0 auto;
            }
            
            .form-group input[type="file"] {
                padding: 10px;
                border: 3px solid #ce9998ff;
                border-radius: 5px;
                background: white;
                cursor: pointer;
                font-family: 'Press Start 2P', cursive;
                font-size: 11px;
            }
            
            .image-preview {
                margin-top: 10px;
                max-width: 200px;
                border-radius: 5px;
                border: 2px solid #ce9998ff;
                padding: 5px;
            }
            
            .form-group select {
                width: 100%;
                padding: 15px;
                border: 3px solid #ce9998ff;
                border-radius: 5px;
                font-family: sans-serif;
                font-size: 16px;
                background: white;
            }
        </style>
    </head>
    <body>
    <div class="write-post-container">
        <a onclick="history.back(-2)" class="back-btn" style="padding: 12px; padding-bottom: 14px;"><</a>
        <a href="index.php" class="back-btn"><img src="images/home.png" alt="Home" style="width: 20px; height: 20px;vertical-align: middle;"></a>
        <div class="write-container">
            <h1>Write New Post</h1>
            
            <?php
            if(isset($_GET['success'])) {
                echo '<div class="success">Post published successfully!</div>';
            }
            if(isset($_GET['error'])) {
                echo '<div class="error">Error: ' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            
            <form action="actions/save_post.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Post Title:</label>
                    <input type="text" id="title" name="title" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php
                        $cat_sql = "SELECT * FROM categories ORDER BY name";
                        $cat_result = $conn->query($cat_sql);
                        while($cat = $cat_result->fetch_assoc()):
                        ?>
                            <option value="<?php echo $cat['id']; ?>"
                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>


                <!--QUILL.JS-->
                <div class="form-group">
                    <label for="content">Post Content:</label>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" />
                    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" />
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css" />
                    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

                    <div id="standalone-container">
                        <div id="toolbar-container">
                            <span class="ql-formats">
                                <select class="ql-font"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                                <button class="ql-strike"></button>
                            </span>
                            <span class="ql-formats">
                                <select class="ql-color"></select>
                                <select class="ql-background"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-script" value="sub"></button>
                                <button class="ql-script" value="super"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-header" value="1"></button>
                                <button class="ql-header" value="2"></button>
                                <button class="ql-blockquote"></button>
                                <button class="ql-code-block"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-list" value="ordered"></button>
                                <button class="ql-list" value="bullet"></button>
                                <button class="ql-indent" value="-1"></button>
                                <button class="ql-indent" value="+1"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-direction" value="rtl"></button>
                                <select class="ql-align"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                                <button class="ql-image"></button>
                                <button class="ql-video"></button>
                                <button class="ql-formula"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-clean"></button>
                            </span>
                        </div>
                        <div id="editor" style="height: 220px"></div>
                    </div>
                    <textarea name="content" id="content" style="display:none;" required>Start writing...</textarea>    
                </div>
                
                <button type="submit" class="submit-btn">Publish Post</button>
            </form>
        </div>
    </div>
        <script src="js/profile.js"></script>
        <script>
            const quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: '#toolbar-container'
                }
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const content = document.querySelector('textarea[name=content]');
                content.value = quill.root.innerHTML;
            });
            
            // Image preview
            document.getElementById('image').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('preview');
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    </body>
</html>
