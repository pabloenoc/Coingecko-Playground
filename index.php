<?php
error_reporting(E_ALL & ~E_WARNING);


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

<?php require __DIR__ . "/views/_head.php" ?>

<body class="container">

    <?php require __DIR__ . "/views/_nav.php" ?>

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