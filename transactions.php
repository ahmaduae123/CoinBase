<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - CoinBase Clone</title>
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
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .transaction-table th, .transaction-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .transaction-table th {
            background-color: #0052cc;
            color: white;
        }
        @media (max-width: 768px) {
            .transaction-table th, .transaction-table td {
                font-size: 0.9em;
                padding: 10px;
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
    ?>
    <header>
        <h1>Transaction History</h1>
        <nav>
            <a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirectTo('wallet.php')">Wallet</a>
            <a href="#" onclick="redirectTo('buy_sell.php')">Buy/Sell</a>
            <a href="#" onclick="logout()">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Your Transactions</h2>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
                $stmt->execute([$user_id]);
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['currency']) . "</td>";
                    echo "<td>$" . number_format($row['amount'], 2) . "</td>";
                    echo "<td>Completed</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
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
