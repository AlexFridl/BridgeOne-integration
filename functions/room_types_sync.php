<?php   
    function syncRoomTypes(mysqli $conn, array $config){
//error reporting
// echo "syncRoomTypes started\n";   
        $propertyId = $config['api_property_id'] ?? null;
        
        if (empty($propertyId)) {
            logMessage('ERROR',
                        'Missing api_property_id in config', 
                        []
                    );
            return false;
        }

        if (empty($_SESSION['api_pkey'])) {
            logMessage('ERROR', 
                        'Missing api_pkey in session (authenticate first)', 
                        []
                    );
            return false;
        }
        
        $roomsResponse = apiRequest(
            '/room/data/rooms',
            'POST',
            $config,
            [
                'token' => $config['api_token'],
                'id_properties' => (string)$propertyId,
                //hardcoded key because it didn't work on pkey. 
                //Key taken from request in API docuemntation
                'key' => '574eb98879eb28d03b21e8a5c1a21259a9a5c85f',
                // 'key' => $_SESSION['api_pkey'],
                'type' => 1,
                'details' => '1'
            ]
        );

        $roomsType = $roomsResponse['data'] ?? null;
        if (!is_array($roomsType)) {
            logMessage('ERROR', 
                        'Room types response is not an array',
                        [
                            'status' => $roomsResponse['status'] ?? null,
                            'raw' => $roomsResponse['raw'] ?? null,
                        ]
            );
            return false;
        }
 
        $sql = "INSERT INTO room_types (
                    external_room_type_id,
                    external_property_id,
                    name,
                    type,
                    shortname,
                    price,
                    occupancy,
                    bathrooms,
                    description,
                    is_deleted,
                    external_created_at,
                    external_updated_at,
                    raw_json
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    external_property_id = VALUES(external_property_id),
                    name = VALUES(name),
                    type = VALUES(type),
                    shortname = VALUES(shortname),
                    price = VALUES(price),
                    occupancy = VALUES(occupancy),
                    bathrooms = VALUES(bathrooms),
                    description = VALUES(description),
                    is_deleted = VALUES(is_deleted),
                    external_created_at = VALUES(external_created_at),
                    external_updated_at = VALUES(external_updated_at),
                    raw_json = VALUES(raw_json)";
    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            logMessage('ERROR', 'Failed to prepare room_types UPSERT statement', [
                'mysqli_error' => $conn->error,
            ]);
            return false;
        }
    
        $inserted = 0;
        $updated = 0;
    
        foreach ($roomsType as $roomType) {
            $externalRoomTypeId = isset($roomType['id_room_types']) ? (int)$roomType['id_room_types'] : 0;
            $externalPropertyId = isset($roomType['id_properties']) ? (int)$roomType['id_properties'] : 0;
    
            $name = $roomType['name'] ?? null;
            $typeVal = $roomType['type'] ?? null;
            $shortname = $roomType['shortname'] ?? null;
    
            $price = isset($roomType['price']) ? (float)$roomType['price'] : null;
            $occupancy = isset($roomType['occupancy']) ? (int)$roomType['occupancy'] : null;
            $bathrooms = isset($roomType['bathrooms']) ? (int)$roomType['bathrooms'] : null;
    
            $description = $roomType['description'] ?? null;
            $isDeleted = !empty($roomType['is_deleted']) ? 1 : 0;
    
            $externalCreatedAt = $roomType['date_created'] ?? null;
            $externalUpdatedAt = $roomType['date_modified'] ?? null;
    
            $rawJson = json_encode(
                $roomType,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
            if ($rawJson === false) {
                $rawJson = null;
            }
    
            $bindOk = $stmt->bind_param(
                "iisssdiississ",
                $externalRoomTypeId,
                $externalPropertyId,
                $name,
                $typeVal,
                $shortname,
                $price,
                $occupancy,
                $bathrooms,
                $description,
                $isDeleted,
                $externalCreatedAt,
                $externalUpdatedAt,
                $rawJson
            );
    
            if ($bindOk === false) {
                logMessage('ERROR', 'Failed to bind params for room_types UPSERT', [
                    'mysqli_error' => $stmt->error,
                    'external_room_type_id' => $externalRoomTypeId,
                ]);
                continue;
            }
    
            $execOk = $stmt->execute();
            if ($execOk === false) {
                logMessage('ERROR', 
                            'Failed to execute room_types UPSERT', 
                            [
                                'mysqli_error' => $stmt->error,
                                'external_room_type_id' => $externalRoomTypeId,
                            ]
                );
                continue;
            }
            logMessage(
                'SUCCESS', 
                'ExecuteD room_types UPSERT', 
                []
            );
    
            if ($stmt->affected_rows === 1) {
                $inserted++;
            } elseif ($stmt->affected_rows === 2) {
                $updated++;
            }
        }

        $stmt->close();
    
        return [
            'table' => 'room_types',
            'inserted' => $inserted,
            'updated' => $updated,
            'total' => count($roomsType),
        ];
    }
?>