<?php
include 'config.php';

try {
    // Update all pets to available
    $update = $conn->prepare("UPDATE `pets` SET availability = 1 WHERE availability = 0");
    $update->execute();
    $affected = $update->rowCount();

    if ($affected > 0) {
        echo "$affected pets updated to available for adoption.<br>";
    } else {
        echo "All pets are already set to available, or no pets found.<br>";
    }

    // Check total pets
    $check = $conn->prepare("SELECT COUNT(*) as count FROM `pets` WHERE availability = 1");
    $check->execute();
    $result = $check->fetch(PDO::FETCH_ASSOC);
    echo "Total available pets: " . $result['count'] . "<br>";

    echo "Update complete. The pets should now appear in the adoption dropdown.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
