<?php 
    $conn = mysqli_connect('localhost', 'root', '');

    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $database = __DIR__.'/../migrations/000_create_database.sql';
    mysqli_query($conn, file_get_contents($database));

    mysqli_select_db($conn, 'bridgeone');
    if (!mysqli_select_db($conn, 'bridgeone')) {
    echo "Database not selected yet (probably not created). Will create it first." . PHP_EOL;
}

    $files = glob(__DIR__.'/../migrations/*.sql');

    foreach ($files as $file) {
        $filename = basename($file);
        $sql = file_get_contents($file);

        $checkSql = "SELECT 1 FROM migrations WHERE migration = '" . mysqli_real_escape_string($conn, $filename) . "' LIMIT 1";
        $checkRes = mysqli_query($conn, $checkSql);

        if ($checkRes && mysqli_num_rows($checkRes) > 0) {
            echo "Skipped: $filename" . PHP_EOL;
            continue;
        }

        if(mysqli_query($conn, $sql)){
            echo "Executed: ".basename($file) .PHP_EOL;

            $insSql = "INSERT INTO migrations (migration) VALUES ('" . mysqli_real_escape_string($conn, $filename) . "')";
            mysqli_query($conn, $insSql);
        }
        else {
            echo "Failed to execute: ".basename($file) .PHP_EOL;
            echo "MySQL Error: " . mysqli_error($conn) . PHP_EOL;
        }
    }

?>