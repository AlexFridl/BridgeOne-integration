<?php

function syncPricingPlans(mysqli $conn, array $config): array|false
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

    $pricingPlansResponse = apiRequest(
        '/pricingPlan/data/pricing_plans',
        'POST',
        $config,
        [
            'token' => 'a5666bee05b0fa91afc5c2f56a6cdcfd57a58c89',
            //hardcoded key because it didn't work on pkey. 
            //Key taken from request in API docuemntation
            'key' => '574eb98879eb28d03b21e8a5c1a21259a9a5c85f',
            // 'key' => $_SESSION['api_pkey'],
            'id_properties' => (string)$propertyId,
        ]
    );

    $datas = $pricingPlansResponse['data'] ?? null;
    if (!is_array($datas)) {
        logMessage('ERROR', 'Unexpected pricing plans API response (missing data array)', [
            'top_level_keys' => is_array($pricingPlansResponse) ? array_keys($pricingPlansResponse) : gettype($pricingPlansResponse),
        ]);
        return false;
    }

    $sql = "INSERT INTO pricing_plans (
                external_pricing_plan_id,
                external_property_id,
                name,
                slug,
                external_board_name_id,
                external_policy_id,
                external_restriction_plan_id,
                external_board_id,
                booking_engine,
                description,
                type,
                copy_periods,
                variation_type,
                variation_amount,
                parent_id,
                first_meal,
                date_created,
                prices_per_person_active,
                locked_price,
                raw
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE
                external_property_id = VALUES(external_property_id),
                name = VALUES(name),
                slug = VALUES(slug),
                external_board_name_id = VALUES(external_board_name_id),
                external_policy_id = VALUES(external_policy_id),
                external_restriction_plan_id = VALUES(external_restriction_plan_id),
                external_board_id = VALUES(external_board_id),
                booking_engine = VALUES(booking_engine),
                description = VALUES(description),
                type = VALUES(type),
                copy_periods = VALUES(copy_periods),
                variation_type = VALUES(variation_type),
                variation_amount = VALUES(variation_amount),
                parent_id = VALUES(parent_id),
                first_meal = VALUES(first_meal),
                date_created = VALUES(date_created),
                prices_per_person_active = VALUES(prices_per_person_active),
                locked_price = VALUES(locked_price),
                raw = VALUES(raw),
                updated_at = CURRENT_TIMESTAMP";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        logMessage('ERROR', 'Failed to prepare pricing_plans UPSERT', [
            'errno' => $conn->errno ?? null,
            'sqlstate' => $conn->sqlstate ?? null,
            'mysqli_error' => $conn->error,
        ]);
        return false;
    }

    $inserted = 0;
    $updated = 0;
    $skipped = 0;
    $failed_execute = 0;
    $total = 0;

    foreach ($datas as $plan) {
        if (!is_array($plan)) {
            $skipped++;
            continue;
        }

        $externalPricingPlanId = isset($plan['id_pricing_plans']) ? (int)$plan['id_pricing_plans'] : 0;
        if ($externalPricingPlanId <= 0) {
            $skipped++;
            continue;
        }

        $externalPropertyId = isset($plan['id_properties']) ? (int)$plan['id_properties'] : (int)$propertyId;
        $name = (string)($plan['name'] ?? '');
        $slug = createRatePlanSlug($externalPricingPlanId, $name);
        $externalBoardNameId = isset($plan['id_board_names']) ? (int)$plan['id_board_names'] : null;
        $externalPolicyId = isset($plan['id_policies']) ? (int)$plan['id_policies'] : null;
        $externalRestrictionPlanId = isset($plan['id_restriction_plans']) ? (int)$plan['id_restriction_plans'] : null;
        $externalBoardId = isset($plan['id_boards']) ? (int)$plan['id_boards'] : null;

        $bookingEngine = isset($plan['booking_engine']) ? (int)$plan['booking_engine'] : 0;
        $description = (string)($plan['description'] ?? '');
        $type = isset($plan['type']) ? (string)$plan['type'] : null;
        $copyPeriods = isset($plan['copy_periods']) ? (int)$plan['copy_periods'] : null;
        $variationType = isset($plan['variation_type']) ? (string)$plan['variation_type'] : null;
        $variationAmount = isset($plan['variation_amount']) ? (float)$plan['variation_amount'] : null;
        $parentId = isset($plan['parent_id']) ? (int)$plan['parent_id'] : null;
        $firstMeal = isset($plan['first_meal']) ? (string)$plan['first_meal'] : null;
        $dateCreated = isset($plan['date_created']) ? (string)$plan['date_created'] : null;
        $pricesPerPersonActive = isset($plan['prices_per_person_active']) ? (int)$plan['prices_per_person_active'] : 0;
        $lockedPrice = isset($plan['locked_price']) ? (int)$plan['locked_price'] : 0;
        $raw = json_encode($plan, JSON_UNESCAPED_UNICODE);

        $bindOk = $stmt->bind_param(
            'iissiiiisssisdisiiss',
            $externalPricingPlanId,
            $externalPropertyId,
            $name,
            $slug,
            $externalBoardNameId,
            $externalPolicyId,
            $externalRestrictionPlanId,
            $externalBoardId,
            $bookingEngine,
            $description,
            $type,
            $copyPeriods,
            $variationType,
            $variationAmount,
            $parentId,
            $firstMeal,
            $dateCreated,
            $pricesPerPersonActive,
            $lockedPrice,
            $raw
        );

        if ($bindOk === false) {
            $failed_execute++;
            logMessage(
                'ERROR', 
                'Failed to bind params for pricing_plans UPSERT',
                [
                    'errno' => $stmt->errno ?? null,
                    'sqlstate' => $stmt->sqlstate ?? null,
                    'mysqli_error' => $stmt->error,
                    'external_pricing_plan_id' => $externalPricingPlanId,
                ]
            );
            continue;
        }

        $execOk = $stmt->execute();
        if ($execOk === false) {
            $failed_execute++;
            logMessage(
                'ERROR', 
                'Failed to execute pricing_plans UPSERT', 
                [
                    'errno' => $stmt->errno ?? null,
                    'sqlstate' => $stmt->sqlstate ?? null,
                    'mysqli_error' => $stmt->error,
                    'external_pricing_plan_id' => $externalPricingPlanId,
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

    $stmt->close();

    return [
        'table' => 'pricing_plans',
        'inserted' => $inserted,
        'updated' => $updated,
        'skipped' => $skipped,
        'failed_execute' => $failed_execute,
        'total' => $total,
    ];
}

?>