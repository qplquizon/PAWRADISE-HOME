<?php
include 'config.php';

header('Content-Type: application/json');

try {
    // Try query with type column, only available pets
    $pets_query = $conn->prepare("SELECT id, name, breed, description, image, availability, COALESCE(type, 'Unknown') as type FROM `pets` WHERE availability = 1 ORDER BY name");
    $pets_query->execute();
    $available_pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($available_pets);
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Unknown column \'type\'') !== false) {
        // Retry without type column, only available pets
        $pets_query = $conn->prepare("SELECT id, name, breed, description, image, availability FROM `pets` WHERE availability = 1 ORDER BY name");
        $pets_query->execute();
        $available_pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
        // Add type as 'Unknown' for each pet
        foreach ($available_pets as &$pet) {
            $pet['type'] = 'Unknown';
        }
        echo json_encode($available_pets);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch pets: ' . $e->getMessage()]);
    }
}
?>
