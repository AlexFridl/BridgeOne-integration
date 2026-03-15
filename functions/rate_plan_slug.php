<?php 
    function createRatePlanSlug($ratePlanId, $mealPlan)
{
    $slug = strtolower($mealPlan);

    $slug = preg_replace('/\s+/', '-', $slug);

    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

    $slug = preg_replace('/-+/', '-', $slug);

    return "RP-" . $ratePlanId . "-" . $slug;
}


?>