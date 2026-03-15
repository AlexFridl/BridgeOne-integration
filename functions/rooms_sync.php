<?php

function syncRooms(mysqli $conn, array $config): array|false
{
    $propertyId = $config['api_property_id'] ?? null;

    if (empty($propertyId)) {
        logMessage('ERROR', 'Missing api_property_id in config', []);
        return false;
    }

    if (empty($_SESSION['api_pkey'])) {
        logMessage('ERROR', 'Missing api_pkey in session (authenticate first)', []);
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

    $datas = $roomsResponse['data'] ?? null;
    if (!is_array($datas)) {
        logMessage(
            'ERROR', 
            'Rooms response is not an array', 
            [
            'status' => $roomsResponse['status'] ?? null,
            'raw' => $roomsResponse['raw'] ?? null,
            ]
        );
        return false;
    }

    $sql = "INSERT INTO rooms (
                external_room_id,
                external_room_type_id,
                external_property_id,
                name,
                slug,
                status,
                is_available,
                is_deleted,
                external_created_at,
                external_deleted_at,
                raw_json
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                external_room_type_id = VALUES(external_room_type_id),
                external_property_id = VALUES(external_property_id),
                name = VALUES(name),
                slug = VALUES(slug),
                status = VALUES(status),
                is_available = VALUES(is_available),
                is_deleted = VALUES(is_deleted),
                external_created_at = VALUES(external_created_at),
                external_deleted_at = VALUES(external_deleted_at),
                raw_json = VALUES(raw_json)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        logMessage('ERROR', 'Failed to prepare rooms UPSERT statement', [
            'mysqli_error' => $conn->error,
        ]);
        return false;
    }

    $inserted = 0;
    $updated = 0;
    $total = 0;
    $skipped_missing_room_number = 0;
    $skipped_missing_id_rooms = 0;
    $failed_execute = 0;

    foreach ($datas as $roomTypeRow) {
        $externalPropertyId = isset($roomTypeRow['id_properties']) ? (int)$roomTypeRow['id_properties'] : 0;
        $externalRoomTypeId = isset($roomTypeRow['id_room_types']) ? (int)$roomTypeRow['id_room_types'] : 0;

        $roomsList = $roomTypeRow['roomDetails']['roomNumber'] ?? null;
        if (!is_array($roomsList)) {
            $skipped_missing_room_number++;
            continue;
        }

        foreach ($roomsList as $room) {
            if (!isset($room['id_rooms'])) {
                $skipped_missing_id_rooms++;
                continue;
            }

            $externalRoomId = (int)$room['id_rooms'];
            $name = $room['name'] ?? '';
            if ($name === '') {
                $name = (string)$externalRoomId;
            }

            $slug = createRoomSlug($externalRoomId, $name);
            $status = $room['status'] ?? 'unknown';

            $isAvailable = 0;
            $isDeleted = !empty($room['is_deleted']) ? 1 : 0;

            $externalCreatedAt = $room['room_date_created'] ?? null;
            $externalDeletedAt = $room['date_deleted'] ?? null;

            $rawJson = json_encode(
                [
                    'roomType' => [
                        'id_room_types' => $roomTypeRow['id_room_types'] ?? null,
                        'id_properties' => $roomTypeRow['id_properties'] ?? null,
                        'name' => $roomTypeRow['name'] ?? null,
                        'shortname' => $roomTypeRow['shortname'] ?? null,
                        'type' => $roomTypeRow['type'] ?? null,
                    ],
                    'room' => $room,
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
            if ($rawJson === false) {
                $rawJson = null;
            }

            $bindOk = $stmt->bind_param(
                "iiisssiisss",
                $externalRoomId,
                $externalRoomTypeId,
                $externalPropertyId,
                $name,
                $slug,
                $status,
                $isAvailable,
                $isDeleted,
                $externalCreatedAt,
                $externalDeletedAt,
                $rawJson
            );

            if ($bindOk === false) {
                $failed_execute++;
                logMessage(
                    'ERROR', 
                    'Failed to bind params for rooms UPSERT', 
                    [
                        'errno' => $stmt->errno ?? null,
                        'sqlstate' => $stmt->sqlstate ?? null,
                        'mysqli_error' => $stmt->error,
                        'external_room_id' => $externalRoomId,
                    ]
                );
                continue;
            }

            $execOk = $stmt->execute();
            if ($execOk === false) {
                $failed_execute++;
                logMessage(
                    'ERROR', 
                    'Failed to execute rooms UPSERT',
                    [
                        'errno' => $stmt->errno ?? null,
                        'sqlstate' => $stmt->sqlstate ?? null,
                        'mysqli_error' => $stmt->error,
                        'external_room_id' => $externalRoomId,
                        'external_room_type_id' => $externalRoomTypeId,
                        'slug' => $slug,
                    ]
                );
                continue;
            }

            $total++;

            if ($stmt->affected_rows === 1) {
                $inserted++;
            } elseif ($stmt->affected_rows === 2) {
                $updated++;
            }
        }
    }

    $stmt->close();

    return [
        'table' => 'rooms',
        'inserted' => $inserted,
        'updated' => $updated,
        'total' => $total,
        'skipped_missing_room_number' => $skipped_missing_room_number,
        'skipped_missing_id_rooms' => $skipped_missing_id_rooms,
        'failed_execute' => $failed_execute,
    ];
}