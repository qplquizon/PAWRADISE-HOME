<?php
include 'config.php';

try {
    // Create pets table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS `pets` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        breed VARCHAR(255) NOT NULL,
        description TEXT,
        image VARCHAR(255),
        availability TINYINT(1) DEFAULT 1,
        type VARCHAR(50) DEFAULT 'dog',
        featured TINYINT(1) DEFAULT 0
    )";
    $conn->exec($sql);
    echo "Pets table created successfully.<br>";

    // Check if there are any pets
    $check = $conn->prepare("SELECT COUNT(*) as count FROM `pets`");
    $check->execute();
    $result = $check->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] == 0) {
        // Insert sample pets
        $sample_pets = [
            ['Buddy', 'Golden Retriever', 'Friendly and energetic dog looking for a loving home.', 'uploads/default-pet.png', 1, 'dog'],
            ['Whiskers', 'Siamese Cat', 'Playful Siamese cat with beautiful blue eyes.', 'uploads/default-pet.png', 1, 'cat'],
            ['Max', 'Labrador', 'Loyal Labrador who loves to play fetch.', 'uploads/default-pet.png', 1, 'dog'],
            ['Luna', 'Persian Cat', 'Calm and affectionate Persian cat.', 'uploads/default-pet.png', 1, 'cat']
        ];

        $insert = $conn->prepare("INSERT INTO `pets` (name, breed, description, image, availability, type) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($sample_pets as $pet) {
            $insert->execute($pet);
        }
        echo "Sample pets inserted successfully.<br>";
    } else {
        // Update existing pets to available if they are not
        $update = $conn->prepare("UPDATE `pets` SET availability = 1 WHERE availability = 0");
        $update->execute();
        $affected = $update->rowCount();
        if ($affected > 0) {
            echo "$affected pets updated to available.<br>";
        }
        echo "Existing pets checked and updated if necessary.<br>";
    }

    echo "Setup complete. Pets should now appear in the adoption dropdown.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
