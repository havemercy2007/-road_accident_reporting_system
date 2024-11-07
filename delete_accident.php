<?php
// Database connection
$host = 'localhost';
$dbname = 'accidents_db';
$username = 'root';
$password = 'Tony1234567!@';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the accident report to get the image name for deletion
    $stmt = $conn->prepare("SELECT image FROM accidents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accident = $result->fetch_assoc();

    if ($accident) {
        $image = $accident['image'];

        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM accidents WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // If there was an image, delete it from the server
            if ($image) {
                unlink("uploads/$image");
            }
            echo json_encode(['message' => 'Accident report deleted successfully.']);
        } else {
            echo json_encode(['message' => 'Error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['message' => 'Accident not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['message' => 'Invalid ID.']);
}

$conn->close();
?>
