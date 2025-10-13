<?php
include 'config.php';

try {
    // Set some pets as featured (replace with actual pet IDs you want to feature)
    $featured_pets = [1, 2, 3]; // Example: feature pets with IDs 1, 2, 3

    $update = $conn->prepare("UPDATE `pets` SET featured = 1 WHERE id = ?");
    foreach ($featured_pets as $pet_id) {
        $update->execute([$pet_id]);
    }

    echo "Pets with IDs " . implode(', ', $featured_pets) . " have been set as featured.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
