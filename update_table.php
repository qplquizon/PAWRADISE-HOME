<?php
include 'config.php';

try {
    $sql = "ALTER TABLE `pets` ADD COLUMN `featured` TINYINT(1) DEFAULT 0";
    $conn->exec($sql);
    echo "Featured column added successfully to the pets table.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
