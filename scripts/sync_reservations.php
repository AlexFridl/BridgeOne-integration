<?php
//error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../setup.php';

    require_once __DIR__.'/../functions/auth.php';
    require_once __DIR__.'/../functions/api.php';

     try {
        $token = authenticate($config);
        echo "Login successful\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";

        logMessage(
            'ERROR', 
            'Login failed', 
            ['error' => $e->getMessage()], 
            'sync_catalog'
        );
        exit(1);
    }

    $conn = dbConnect($config);
   
    

    logMessage(
        'INFO', 
        'Sync reservations started', 
        [], 
        'sync_reservations'
    );

    echo "Pokrenula sam rezervacije";
?>
