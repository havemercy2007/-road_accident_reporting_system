<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=accidents_db", "root", "Tony1234567!@"); // Update with your DB credentials
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
