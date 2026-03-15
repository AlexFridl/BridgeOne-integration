<?php
    function dbConnect($config){
        $dbHost = $config['db']['host'];
        $dbName = $config['db']['name'];
        $dbUser = $config['db']['user'];
        $dbPass = $config['db']['pass'];

        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

        if(!$conn){
            die("Database connection failed!".mysqli_connect_error());
        }
        return $conn;
    }

    //napravi za diskonektovanje sa baze
?>