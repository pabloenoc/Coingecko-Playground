<?php

date_default_timezone_set('America/Los_Angeles');

// ex: localhost:8000/?crypto=Bitcoin&fiat=USD
if (isset($_GET["crypto"]) && isset($_GET["fiat"])) {
    $url = "https://api.coingecko.com/api/v3/simple/price?ids=" . $_GET["crypto"]  . "&vs_currencies=" . $_GET["fiat"];
    $result = file_get_contents($url);

    $crypto_data = json_decode($result);
    $crypto_data->timestamp = date("Y-m-d H:i:s");

    $data = file_put_contents("data/price-results.log", json_encode($crypto_data) . "\n", FILE_APPEND);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
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

    <h1><a href="/" style="color:inherit; text-decoration: none;">Coingecko Playground</a></h1>

    <form action="index.php" method="GET">
        <fieldset>
            <legend>Price Comparison</legend>


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
                <option value="usd" selected>US Dollar</option>
                <option value="eur">Euro</option>
                <option value="gbp">British Pound</option>
                <option value="jpy">Japanese Yen</option>
                <option value="aud">Australian Dollar</option>
            </select>

            <input type="submit" value="Get Prices">
        </fieldset>
    </form>

    <?php if (isset($result)) : ?>
        <p><code><?= $result; ?></code></p>
    <?php endif ?>

    <?php
    // 1) Read our price-results.log by line

    $searches = [];

    $searches_file_contents = file_get_contents("data/price-results.log");
    $searches_arr = explode("\n", $searches_file_contents);

    foreach ($searches_arr as $search) {
        array_push($searches, json_decode($search));
    }

    foreach ($searches as $search) {
        foreach ($search as $crypto => $values) {
            foreach ($values as $fiat => $value) {
                $table_data[] = [
                    'crypto' => $crypto,
                    'fiat' => $fiat,
                    'value' => $value,
                    'timestamp' => $search->timestamp
                ];
            }
        }
    }

    ?>

    <table>
        <tr>
            <th>Crypto</th>
            <th>Fiat</th>
            <th>Value</th>
            <th>% Change</th>
            <th>Timestamp</th>
        </tr>
        <tr>
            <?php foreach ($table_data as $data): ?>
                <?php if (!isset($data)) break; ?>
        <tr>
            <td><?= htmlspecialchars($data['crypto']) ?></td>
            <td><?= htmlspecialchars($data['fiat']) ?></td>
            <td><?= htmlspecialchars($data['value']) ?></td>
            <td>+/- 4%</td>
            <td><?= htmlspecialchars($data['timestamp']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tr>
    </table>

</body>

</html>