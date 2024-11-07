<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "Tony1234567!@";
$dbname = "accidents_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch accident reports
$sql = "SELECT * FROM accidents"; // Change to your actual table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store the results in an array
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    echo json_encode($reports); // Return the results as JSON
} else {
    echo json_encode(["error" => "No records found"]);
}

$conn->close();
?>
