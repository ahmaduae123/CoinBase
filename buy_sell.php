<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy/Sell - CoinBase Clone</title>
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
        .trade-container {
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
        .chart-container {
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .trade-container {
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
        $currency = $_POST['currency'];
        $amount = $_POST['amount'];
        $type = $_POST['type'];
        $order_type = $_POST['order_type'];
        $price = $_POST['price'] ?? null;
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, currency, amount, type, order_type, price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $currency, $amount, $type, $order_type, $price]);
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ? AND currency = ?");
            $adjustment = $type === 'buy' ? -$amount : $amount;
            $stmt->execute([$adjustment, $user_id, $currency]);
            echo "<p class='success'>Order placed successfully!</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }
    ?>
    <header>
        <h1>Buy/Sell Cryptocurrency</h1>
        <nav>
            <a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirectTo('wallet.php')">Wallet</a>
            <a href="#" onclick="redirectTo('transactions.php')">Transactions</a>
            <a href="#" onclick="logout()">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="trade-container">
            <h2>Place an Order</h2>
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
                    <label for="type">Order Type</label>
                    <select id="type" name="type" required>
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="order_type">Order Method</label>
                    <select id="order_type" name="order_type" required>
                        <option value="market">Market Order</option>
                        <option value="limit">Limit Order</option>
                    </select>
                </div>
                <div class="form-group" id="price-field" style="display: none;">
                    <label for="price">Limit Price</label>
                    <input type="number" id="price" name="price" step="0.01">
                </div>
                <button type="submit">Place Order</button>
            </form>
            <div class="chart-container">
                <canvas id="tradeChart"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
        function logout() {
            window.location.href = 'login.php';
        }
        document.getElementById('order_type').addEventListener('change', function() {
            document.getElementById('price-field').style.display = this.value === 'limit' ? 'block' : 'none';
        });
        const ctx = document.getElementById('tradeChart').getContext('2d');
        const tradeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Bitcoin Price',
                        data: [45000, 47000, 49000, 51000, 50000, 52000],
                        borderColor: '#f7931a',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    </script>
</body>
</html>
