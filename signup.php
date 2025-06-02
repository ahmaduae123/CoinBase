<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CoinBase Clone</title>
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
        .signup-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .signup-container h2 {
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
        .two-fa-code {
            text-align: center;
            font-weight: bold;
            background-color: #e6f3ff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .signup-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $two_fa_code = sprintf("%06d", mt_rand(100000, 999999));
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, two_fa_code) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password, $two_fa_code]);
                echo "<p class='success'>Signup successful! Please note your 2FA code below.</p>";
                echo "<p class='two-fa-code'>Your 2FA Code: $two_fa_code</p>";
                echo "<p class='success'>Redirecting to login in 5 seconds...</p>";
                echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 5000);</script>";
            } catch (PDOException $e) {
                echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
            }
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <p style="text-align: center; margin-top: 10px;">
            Already have an account? <a href="#" onclick="redirectTo('login.php')">Login</a>
        </p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
