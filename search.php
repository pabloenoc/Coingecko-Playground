<?php

if (isset($_GET["query"])) {
    $url = "https://api.coingecko.com/api/v3/search?query=" . $_GET["query"];
    $result = file_get_contents($url);
    $data = file_put_contents("data/search-results.log", $result . "\n", FILE_APPEND);
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require __DIR__ . "/views/_head.php" ?>

<body class="container">

    <?php require __DIR__ . "/views/_nav.php" ?>

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