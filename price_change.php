<?php

$errors = [];
$timestamps = [];
$prices = [];

if (isset($_GET['crypto']) && isset($_GET['fiat']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {

    // Check submitted values 

    if ($_GET['crypto'] == "") {
        $errors[] = "You must select a value for crypto";
    }

    if ($_GET['fiat'] == "") {
        $errors[] = "You must select a value for fiat";
    }

    if (empty($errors)) {
        $coingecko_api_url = "https://api.coingecko.com/api/v3/coins/"
            . $_GET['crypto']
            . "/market_chart/range?vs_currency="
            . $_GET['fiat']
            . "&from="
            . $_GET['start_date']
            . "&to="
            . $_GET['end_date'];
    } else {
        header('HTTP/1.1 422 Unprocessable Content');
    }
}

if (isset($coingecko_api_url)) {
    $price_data = json_decode(file_get_contents($coingecko_api_url));

    foreach ($price_data->{'prices'} as $item) {
        $timestamp_with_microseconds = $item[0];
        $price = $item[1];
        $timestamp = intval($timestamp_with_microseconds / 1000);
        $timestamps[] = $timestamp;
        $prices[] = $price;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require __DIR__ . "/views/_head.php" ?>

<body class="container">

    <?php require __DIR__ . "/views/_nav.php" ?>

    <h1><a href="/" style="color:inherit; text-decoration: none;">Coingecko Price Change</a></h1>

    <form action="price_change.php" method="GET">
        <?php if (!empty($errors)): ?>
            <div>
                <p style="color: red">Please fix the following errors before submitting:</p>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li style="color: red"><?= $e ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <label for="crypto">Select crypto:</label>

        <?php if (!empty($errors)): ?>
        <select name="crypto" id="crypto" required aria-invalid="true">
        <?php else: ?>
        <select name="crypto" id="crypto" required>
        <?php endif ?>
        
            <option value="bitcoin" selected>Bitcoin</option>
            <option value="ethereum">Ethereum</option>
            <option value="litecoin">Litecoin</option>
            <option value="bitcoin-cash">Bitcoin Cash</option>
        </select>

        <label for="fiat">Select fiat:</label>

        <?php if (!empty($errors)): ?>
        <select name="fiat" id="fiat" required aria-invalid="true">
        <?php else: ?>
        <select name="fiat" id="fiat" required>
        <?php endif ?>

            <option value="usd" selected>US Dollar</option>
            <option value="eur">Euro</option>
            <option value="gbp">British Pound</option>
            <option value="jpy">Japanese Yen</option>
            <option value="aud">Australian Dollar</option>
        </select>

        <label for="start_date">Start time:</label>

        <input type="date" name="start_date" id="start_date" value="" required>

        <label for="end_date">End time:</label>

        <input type="date" name="end_date" id="end_date" value="<?= date("Y-m-d") ?>">

        <input type="submit" value="OK">

    </form>

    <button onclick="localStorage.clear();">Delete localStorage</button> <br>

    <?php if (isset($coingecko_api_url)): ?>
        Querying: <code><?= $coingecko_api_url ?></code>

        <div>
            <canvas id="myChart"></canvas>
        </div>

        <script>
            
            const ctx = document.getElementById('myChart');

            const timestamps = [<?php foreach ($timestamps as $ts) echo date("'Y-m-d H:i'", $ts) . ","; ?>];
            const prices = [<?php foreach ($prices as $p) echo $p . ","; ?>];
            
            // the label below looks like "Bitcoin (USD) etc"

            const newDataset = {
                label: '<?= ucfirst($_GET['crypto']) ?> (<?= strtoupper($_GET['fiat']) ?>)',
                data: prices
            };

            let config;

            if (!localStorage.getItem("chart_config")) {
                config = {
                    type: 'bar',
                    data: {
                        labels: timestamps,
                        datasets: [newDataset]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: false }
                        }
                    }
                };
            } else {
                config = JSON.parse(localStorage.getItem("chart_config"));

                config.data.labels = timestamps;

                const existing = config.data.datasets.find(ds => ds.label === newDataset.label);
                if (!existing) {
                    config.data.datasets.push(newDataset);
                }
            }

            localStorage.setItem("chart_config", JSON.stringify(config));

            new Chart(ctx, config);
        </script>


    <?php endif; ?>
    
    <?php require "views/_footer.php" ?>

</body>

</html>