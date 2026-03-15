<?php
//error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../setup.php';

    require_once __DIR__.'/../functions/auth.php';
    require_once __DIR__.'/../functions/api.php';

    require_once __DIR__.'/../functions/room_types_sync.php';
    require_once __DIR__.'/../functions/rooms_sync.php';
    require_once __DIR__.'/../functions/pricing_plans.php';
    require_once __DIR__.'/../functions/room_slug.php';
    require_once __DIR__.'/../functions/rate_plan_slug.php';    

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
   
    $summary = [];
    $rooms = syncRoomTypes($conn, $config);

    $roomsResult = syncRooms($conn, $config);

    $pricingPlans = syncPricingPlans($conn, $config);

    $summary = [
        'rooms' => $rooms,
        'rooms_result' => $roomsResult,
        'pricing_plans' => $pricingPlans
    ];

    print_r($summary);
?>