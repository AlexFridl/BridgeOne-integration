<?php 
    function createRoomSlug($roomId, $roomName) {
    
    $slug = strtolower($roomName);

    $slug = preg_replace('/\s+/', '-', $slug);

    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

    $slug = preg_replace('/-+/', '-', $slug);

    return "HS-" . $roomId . "-" . $slug;
}

?>