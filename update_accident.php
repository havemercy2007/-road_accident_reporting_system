<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if data is provided via POST
if (isset($_POST['id'])) {
    // Retrieve the data from the POST request
    $id = $_POST['id'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $severity = $_POST['severity'];

    // Image upload handling
    $image = ''; // Default if no image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the target directory for uploads
        $target_dir = "uploads/"; // Ensure this folder exists and is writable
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the file type (optional)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Successfully uploaded
                $image = basename($_FILES["image"]["name"]);
            } else {
                echo json_encode(["error" => "Sorry, there was an error uploading your file."]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Sorry, only JPG, JPEG, PNG, and GIF files are allowed."]);
            exit;
        }
    }

    // Database connection details
    $servername = "localhost";
    $username = "root";  // Replace with your DB username
    $password = "Tony1234567!@";      // Replace with your DB password
    $dbname = "accidents_db";  // Replace with your DB name

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the update query
    if ($image) {
        // If there's an image, update it in the database
        $sql = "UPDATE accidents SET description = ?, date = ?, location = ?, severity = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $description, $date, $location, $severity, $image, $id);
    } else {
        // If no new image, do not update the image column
        $sql = "UPDATE accidents SET description = ?, date = ?, location = ?, severity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $description, $date, $location, $severity, $id);
    }

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        echo json_encode(["message" => "Accident report updated successfully"]);
    } else {
        echo json_encode(["error" => "Failed to update the accident report"]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "No ID provided"]);
}
?>
