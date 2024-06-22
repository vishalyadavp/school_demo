<?php
require 'db.php';

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
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($image);

        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $target_file = $target_dir . uniqid() . '.' . $file_extension;

        if (move_uploaded_file($image_tmp, $target_file)) {
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

<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Create Student</h1>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Class</label>
            <select name="class_id" class="form-control">
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>
</body>
</html>
