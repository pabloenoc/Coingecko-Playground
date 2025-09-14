<?php

if (isset($_GET['crypto']) && isset($_GET['fiat']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {

    $coingecko_api_url = "https://api.coingecko.com/api/v3/coins/"
        . $_GET['crypto']
        . "/market_chart/range?vs_currency="
        . $_GET['fiat']
        . "&from="
        . $_GET['start_date']
        . "&to="
        . $_GET['end_date'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Coingecko Playground</title>
</head>

<body class="container">

    <nav style="justify-content: flex-end; gap: 1rem; padding: 1rem;">

        <a href="/">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
            </svg>

            Home
        </a>

        <a href="search.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
            Search API
        </a>

        <a href="price_change.php">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 19l16 0" />
                <path d="M4 15l4 -6l4 2l4 -5l4 4" />
            </svg>
            Price Change
        </a>
    </nav>

    <h1><a href="/" style="color:inherit; text-decoration: none;">Coingecko Price Change</a></h1>

    <form action="price_change.php" method="GET">
        <label for="crypto">Select crypto:</label>

        <select name="crypto" id="">
            <option value="">Please select an option</option>
            <option value="bitcoin">Bitcoin</option>
            <option value="ethereum">Ethereum</option>
            <option value="litecoin">Litecoin</option>
            <option value="bitcoin-cash">Bitcoin Cash</option>
        </select>

        <label for="fiat">Select fiat:</label>

        <select name="fiat" id="">
            <option value="">Please select an option</option>
            <option value="usd">US Dollar</option>
            <option value="eur">Euro</option>
            <option value="gbp">British Pound</option>
            <option value="jpy">Japanese Yen</option>
            <option value="aud">Australian Dollar</option>
        </select>

        <label for="start">Start time:</label>

        <input type="date" name="start_date" value="">

        <label for="start">End time:</label>

        <input type="date" name="end_date" value="<?= date("Y-m-d") ?>">

        <input type="submit" value="OK">

    </form>

    <?php if (isset($coingecko_api_url)): ?>
        Querying: <code><?= $coingecko_api_url ?></code>

        <div>
           <canvas id="myChart"></canvas> 
        </div>

        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['08/31 00:00', '08/31 01:00', '08/31 02:00'],
                    datasets: [{
                        label: 'Price by Date (Hourly)',
                        data: [108781.95, 109321.61, 109453.20],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        </script>


    <?php endif; ?>

</body>

</html>