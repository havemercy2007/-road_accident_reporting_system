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

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $severity = $_POST['severity'];
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        if (getimagesize($_FILES['image']['tmp_name'])) {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = basename($_FILES['image']['name']);
            } else {
                echo json_encode(['message' => 'Sorry, there was an error uploading your file.']);
                exit;
            }
        } else {
            echo json_encode(['message' => 'The file is not an image.']);
            exit;
        }
    }

    // Insert accident report into database
    $stmt = $conn->prepare("INSERT INTO accidents (description, date, location, severity, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $description, $date, $location, $severity, $image);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Accident report added successfully.']);
    } else {
        echo json_encode(['message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
