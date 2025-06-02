<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoinBase Clone - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
        }
        header {
            background-color: #0052cc;
            color: white;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
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
        .crypto-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .crypto-table th, .crypto-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .crypto-table th {
            background-color: #0052cc;
            color: white;
        }
        .crypto-table tr:hover {
            background-color: #f1f1f1;
        }
        .chart-container {
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .crypto-table th, .crypto-table td {
                font-size: 0.9em;
                padding: 10px;
            }
            header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>CoinBase Clone</h1>
        <nav>
            <a href="#" onclick="redirectTo('index.php')">Home</a>
            <a href="#" onclick="redirectTo('signup.php')">Sign Up</a>
            <a href="#" onclick="redirectTo('login.php')">Login</a>
        </nav>
    </header>
    <div class="container">
        <h2>Real-Time Cryptocurrency Prices</h2>
        <table class="crypto-table">
            <thead>
                <tr>
                    <th>Cryptocurrency</th>
                    <th>Price (USD)</th>
                    <th>24h Change</th>
                    <th>Market Cap</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bitcoin (BTC)</td>
                    <td id="btc-price">$0</td>
                    <td id="btc-change">0%</td>
                    <td id="btc-marketcap">$0</td>
                </tr>
                <tr>
                    <td>Ethereum (ETH)</td>
                    <td id="eth-price">$0</td>
                    <td id="eth-change">0%</td>
                    <td id="eth-marketcap">$0</td>
                </tr>
            </tbody>
        </table>
        <div class="chart-container">
            <canvas id="priceChart"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }

        // Dummy API simulation for real-time prices
        function fetchCryptoPrices() {
            const prices = {
                btc: { price: 50000 + Math.random() * 1000, change: (Math.random() * 2 - 1).toFixed(2), marketcap: '1.2T' },
                eth: { price: 3000 + Math.random() * 100, change: (Math.random() * 2 - 1).toFixed(2), marketcap: '400B' }
            };
            document.getElementById('btc-price').innerText = `$${prices.btc.price.toFixed(2)}`;
            document.getElementById('btc-change').innerText = `${prices.btc.change}%`;
            document.getElementById('btc-marketcap').innerText = `$${prices.btc.marketcap}`;
            document.getElementById('eth-price').innerText = `$${prices.eth.price.toFixed(2)}`;
            document.getElementById('eth-change').innerText = `${prices.eth.change}%`;
            document.getElementById('eth-marketcap').innerText = `$${prices.eth.marketcap}`;
        }

        // Chart.js for price trends
        const ctx = document.getElementById('priceChart').getContext('2d');
        const priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Bitcoin Price',
                        data: [45000, 47000, 49000, 51000, 50000, 52000],
                        borderColor: '#f7931a',
                        fill: false
                    },
                    {
                        label: 'Ethereum Price',
                        data: [2800, 2900, 3000, 3100, 3050, 3200],
                        borderColor: '#627eea',
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

        // Fetch prices every 5 seconds
        fetchCryptoPrices();
        setInterval(fetchCryptoPrices, 5000);
    </script>
</body>
</html>
