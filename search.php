<?php

if (isset($_GET["query"])) {
    $url = "https://api.coingecko.com/api/v3/search?query=" . $_GET["query"];
    $result = file_get_contents($url);
    $data = file_put_contents("data/search-results.log", $result . "\n", FILE_APPEND);
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
    <h1>Coingecko Search</h1>

    <form action="search.php" method="GET">
        <label for="search">Query</label>
        <input type="search" name="query">
        <input type="submit" value="Search">
    </form>

    <?php if (isset($result)) : ?>
        <p><code><?= htmlspecialchars($result); ?></code></p>
    <?php endif; ?>

</body>

</html>