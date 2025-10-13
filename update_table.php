<?php
include 'config.php';

try {
    // Check if column exists
    $check = $conn->prepare("DESCRIBE `pets`");
    $check->execute();
    $columns = $check->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('featured', $columns)) {
        $sql = "ALTER TABLE `pets` ADD COLUMN `featured` TINYINT(1) DEFAULT 0";
        $conn->exec($sql);
        echo "Featured column added successfully to the pets table.";
    } else {
        echo "Featured column already exists in the pets table.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
