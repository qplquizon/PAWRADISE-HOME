<?php
include 'config.php';

try {
    $stmt = $conn->query('DESCRIBE adoption_requests');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Current columns in adoption_requests table:\n";
    foreach($columns as $col) {
        echo $col['Field'] . ' (' . $col['Type'] . '), ';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
