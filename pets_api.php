<?php
include 'config.php';

header('Content-Type: application/json');

try {
    // Removed availability filter to return all pets
    $pets_query = $conn->prepare("SELECT id, name, breed, type, availability FROM `pets` ORDER BY name");
    $pets_query->execute();
    $available_pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($available_pets);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch pets: ' . $e->getMessage()]);
}
?>
