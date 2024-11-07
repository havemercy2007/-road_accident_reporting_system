<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the accident ID from the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Database connection
    $servername = "localhost";
    $username = "root"; // Your DB username
    $password = "Tony1234567!@";     // Your DB password
    $dbname = "accidents_db"; // Your DB name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the accident report based on the ID
    $sql = "SELECT * FROM accidents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch the accident details
        $report = $result->fetch_assoc();
        echo json_encode($report); // Return the report details as JSON
    } else {
        echo json_encode(["error" => "Accident report not found"]);
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "No ID provided"]);
}
?>
