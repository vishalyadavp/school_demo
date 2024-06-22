<?php
require 'db.php';

// Fetch classes from the database
$sql = "SELECT * FROM classes";
$stmt = $pdo->query($sql);
$classes = $stmt->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];

    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    $allowed_types = ['image/jpeg', 'image/png'];
    $image_type = mime_content_type($image_tmp);
    if ($image && !in_array($image_type, $allowed_types)) {
        $errors[] = 'Invalid image format. Only JPG and PNG are allowed';
    }

    if ($image_error != UPLOAD_ERR_OK) {
        $errors[] = 'Error uploading the file';
    }

    if (empty($errors)) {
        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        // Ensure unique file name
        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $target_file = $target_dir . uniqid() . '.' . $file_extension;

        if (move_uploaded_file($image_tmp, $target_file)) {
            // Insert student into the database
            $sql = "INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $address, $class_id, $target_file]);

            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Failed to move uploaded file';
        }
    }
}
?>
