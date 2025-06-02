<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CoinBase Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }
        header {
            background-color: #0052cc;
            color: white;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2em;
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #0041a3;
            padding: 10px;
        }
        nav a {
            color: white;
            margin: 0 20px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            margin: 0 0 10px;
            color: #0052cc;
        }
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            header h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'db.php';
    if (!isset($_SESSION['user_id'])) {
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    ?>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <nav>
            <a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirectTo('wallet.php')">Wallet</a>
            <a href="#" onclick="redirectTo('buy_sell.php')">Buy/Sell</a>
            <a href="#" onclick="redirectTo('transactions.php')">Transactions</a>
            <a href="#" onclick="logout()">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Your Dashboard</h2>
        <div class="dashboard-grid">
            <div class="card">
                <h3>Wallet Balance</h3>
                <p>
                    <?php
                    $stmt = $pdo->prepare("SELECT SUM(balance) as total FROM wallets WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $balance = $stmt->fetch()['total'] ?? 0;
                    echo "$" . number_format($balance, 2);
                    ?>
                </p>
            </div>
            <div class="card">
                <h3>Portfolio Value</h3>
                <p>
                    <?php
                    // Dummy portfolio value calculation
                    echo "$" . number_format($balance * 1.05, 2);
                    ?>
                </p>
            </div>
            <div class="card">
                <h3>Recent Transactions</h3>
                <p>
                    <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM transactions WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    echo $stmt->fetch()['count'] . " transactions";
                    ?>
                </p>
            </div>
        </div>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
        function logout() {
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>
