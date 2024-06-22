<?php
require 'db.php';

// Fetch classes for the dropdown
$sql = "SELECT * FROM classes";
$stmt = $pdo->query($sql);
$classes = $stmt->fetchAll();

$errors = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the student record
    $sql = "SELECT * FROM student WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $student = $stmt->fetch();

    if (!$student) {
        echo "Student not found.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $class_id = $_POST['class_id'];
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_error = $_FILES['image']['error'];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        $allowed_types = ['image/jpeg', 'image/png'];
        $image_type = mime_content_type($image_tmp);
        if ($image && !in_array($image_type, $allowed_types)) {
            $errors[] = 'Invalid image format. Only JPG and PNG are allowed';
        }

        if ($image_error != UPLOAD_ERR_OK && $image_error != UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Error uploading the file';
        }

        if (empty($errors)) {
            if ($image) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $target_file = $target_dir . basename($image);

                $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
                $target_file = $target_dir . uniqid() . '.' . $file_extension;

                if (move_uploaded_file($image_tmp, $target_file)) {
                    // Delete the old image if a new one is uploaded
                    if (!empty($student['image']) && file_exists($student['image'])) {
                        unlink($student['image']);
                    }
                    $image_path = $target_file;
                } else {
                    $errors[] = 'Failed to move uploaded file';
                }
            } else {
                // Keep the existing image path
                $image_path = $student['image'];
            }

            // Update the student record
            $sql = "UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $address, $class_id, $image_path, $id]);

            header('Location: index.php');
            exit;
        }
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Student</h1>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($student['address']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Class</label>
            <select name="class_id" class="form-control">
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['class_id'] ?>" <?= $class['class_id'] == $student['class_id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            <?php if (!empty($student['image'])): ?>
                <img src="<?= htmlspecialchars($student['image']) ?>" width="100" height="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>
</body>
</html>
