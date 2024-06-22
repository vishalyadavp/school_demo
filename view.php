<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the student record along with class name
    $sql = "SELECT student.name, student.email, student.address, student.created_at, student.image, classes.name AS class_name 
            FROM student 
            LEFT JOIN classes ON student.class_id = classes.class_id 
            WHERE student.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $student = $stmt->fetch();

    if (!$student) {
        echo "Student not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>View Student</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($student['name']) ?></h5>
            <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
            <p class="card-text"><strong>Class:</strong> <?= htmlspecialchars($student['class_name']) ?></p>
            <p class="card-text"><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
            <p class="card-text"><strong>Image:</strong></p>
            <img src="<?= htmlspecialchars($student['image']) ?>" width="200" height="200">
            <br><br>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>
</body>
</html>
