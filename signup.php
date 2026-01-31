<?php
    session_start();
    include 'config.php';

    $error='';
    $success='';
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"]);
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already registered";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Account created successfully. You can log in.";
            } else {
                $error = "Something went wrong. Try again.";
            }
        }

        mysqli_stmt_close($stmt);
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AM-Sign up</title>
    <script src="js/login.js"></script>
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
            margin-left: 100px;
        }
        .btn:hover {
            background-color: #515351ff;
            transform: scale(1.05);
        }
        .login {
            font-size: 12px;
            color: #555;
            margin-top: auto;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red; background-color: #ffe6e6; padding: 10px; border-radius: 5px; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="username" name="username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>
            <div class="login"><p>Already have an account? <a href="login.php">log in</a></p></div>

        <?php if (!empty($success)): ?>
            <p style="color: #6a7b86ff; text-align: center;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>