<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CoinBase Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            color: #0052cc;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0052cc;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0052cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover {
            background-color: #0041a3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        session_start();
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $two_fa_code = $_POST['two_fa_code'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                if ($two_fa_code === $user['two_fa_code']) {
                    $_SESSION['user_id'] = $user['id'];
                    echo "<p class='success'>Login successful! Redirecting to dashboard...</p>";
                    echo "<script>setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);</script>";
                } else {
                    echo "<p class='error'>Invalid 2FA code</p>";
                }
            } else {
                echo "<p class='error'>Invalid email or password</p>";
            }
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="two_fa_code">2FA Code</label>
                <input type="text" id="two_fa_code" name="two_fa_code" required placeholder="Enter 6-digit code">
            </div>
            <button type="submit">Login</button>
        </form>
        <p style="text-align: center; margin-top: 10px;">
            Don't have an account? <a href="#" onclick="redirectTo('signup.php')">Sign Up</a>
        </p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
