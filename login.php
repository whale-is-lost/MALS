<?php
    session_start();
    include 'config.php';

    $error='';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        } else {
            $error ="Invalid email or password.";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AM-Log in</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: sans-serif;
            background: #efebe2;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: #F3EBEB;
            border-radius: 10px;
            box-shadow: 0 10px 40px #999898;
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        h2{
            font-family: 'Press Start 2P', monospace;
            text-align: center;
            color: #333;
            margin-bottom: 50px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #f6f2f2;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .btn {
            background-color: #353735ff;
            color: white;
            padding: 7px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-left: 130px;
        }
        .btn:hover {
            background-color: #515351ff;
            transform: scale(1.05);
        }
        .signup {
            text-align: center;
            margin-top: 10px;
        }
        
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; background-color: #ffe6e6; padding: 10px; border-radius: 5px; text-align:center;">
            <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
        </form>
        <div class="signup"><p>No account? <a href="signup.php">create one</a></p></div>
    </div>
</body>
</html>