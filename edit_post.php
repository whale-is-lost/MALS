<?php
    session_start();
    include 'config.php';


    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];


    $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        header("Location: index.php");
        exit();
    }

    $post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Edit Post - AM</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
        <style>
            body {
                background: #efebe2;
                padding: 20px;
            }
            
            .edit-container {
                max-width: 68vw;
                padding: 40px;
                background: #f4f5deff;
                border: 3px solid #ce9998ff;
                border-radius: 10px;
                box-shadow: 5px 5px 0 0 #6145465a;
            }
            
            .edit-container h1 {
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
                box-sizing: border-box;
            }
            
            .form-group textarea {
                min-height: 200px;
                resize: vertical;
            }
            
            .button-group {
                display: flex;
                gap: 15px;
            }
            
            .submit-btn, .cancel-btn, .delete-btn {
                font-family: 'Press Start 2P', cursive;
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
            
            .cancel-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05)
            }
            
            .delete-btn:hover {
                background: #d7dab9ff;
                color: #ff6d6dff;
                transform: scale(1.05);
                transition-delay: 0.1s ease;
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
                color: #036f03;
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
            .edit-top-container { 
                max-width: 68vw;
                min-width: 500px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="edit-top-container">
            <a onclick="history.back(-2)" class="back-btn" style="padding: 12px; padding-bottom: 14px;"><</a>
        <div class="edit-container">
            <h1>Edit Post</h1>
            <div class="button-group">
                    <button type="button" class="delete-btn" onclick="if(confirm('Are you sure you want to delete this post?')) window.location.href='actions/delete_post.php?id=<?php echo $post['id']; ?>' ">
                        Delete Post
                    </button>
            </div>
            <?php
            if(isset($_GET['success'])) {
                echo '<div class="success">Post updated successfully!</div>';
            }
            if(isset($_GET['error'])) {
                echo '<div class="error">Error: ' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            
            <form action="actions/update_post.php" method="POST">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                
                <div class="form-group">
                    <label for="title">Post Title:</label>
                    <input type="text" id="title" name="title" required maxlength="255" 
                        value="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
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
                    <textarea name="content" id="content" style="display:none;" required><?php echo $post['content']; ?></textarea>    
                </div>
                <div class="button-group">
                    <a class="cancel-btn" onclick="window.location.href='index.php'">Done</a>
                    <button type="submit" class="submit-btn">Update Post</button>
                </div>
            </form>
        </div>
</div>
        <script>
            const quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: '#toolbar-container'
                }
            });

            (function populateEditor() {
                const contentTextarea = document.getElementById('content');
                if (contentTextarea && contentTextarea.value.trim().length) {
                    
                    if (quill.clipboard && typeof quill.clipboard.dangerouslyPasteHTML === 'function') {
                        quill.clipboard.dangerouslyPasteHTML(contentTextarea.value);
                    } else {
                        
                        quill.root.innerHTML = contentTextarea.value;
                    }
                }
            })();

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const content = document.querySelector('textarea[name=content]');
                content.value = quill.root.innerHTML;
            });
        </script>
    </body>
</html>