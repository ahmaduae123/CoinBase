<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - CoinBase Clone</title>
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
        .wallet-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 12px;
            background-color: #0052cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0041a3;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 10px;
        }
        .error { color: red; }
        .success { color: green; }
        @media (max-width: 768px) {
            .wallet-container {
                padding: 15px;
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = $_POST['amount'];
        $currency = $_POST['currency'];
        $type = $_POST['type'];
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, currency, type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $amount, $currency, $type]);
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ? AND currency = ?");
            $adjustment = $type === 'deposit' ? $amount : -$amount;
            $stmt->execute([$adjustment, $user_id, $currency]);
            echo "<p class='success'>Transaction successful!</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }
    ?>
    <header>
        <h1>Your Wallet</h1>
        <nav>
            <a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirectTo('buy_sell.php')">Buy/Sell</a>
            <a href="#" onclick="redirectTo('transactions.php')">Transactions</a>
            <a href="#" onclick="logout()">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="wallet-container">
            <h2>Deposit/Withdraw</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency" required>
                        <option value="BTC">Bitcoin (BTC)</option>
                        <option value="ETH">Ethereum (ETH)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="type">Transaction Type</label>
                    <select id="type" name="type" required>
                        <option value="deposit">Deposit</option>
                        <option value="withdraw">Withdraw</option>
                    </select>
                </div>
                <button type="submit">Submit</button>
            </form>
            <h3>Wallet Balances</h3>
            <?php
            $stmt = $pdo->prepare("SELECT currency, balance FROM wallets WHERE user_id = ?");
            $stmt->execute([$user_id]);
            while ($row = $stmt->fetch()) {
                echo "<p>" . htmlspecialchars($row['currency']) . ": $" . number_format($row['balance'], 2) . "</p>";
            }
            ?>
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
