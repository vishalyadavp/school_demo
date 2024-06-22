<?php
require 'db.php';

$sql = "SELECT student.id, student.name, student.email, student.created_at, student.image, classes.name AS class_name 
        FROM student 
        LEFT JOIN classes ON student.class_id = classes.class_id";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Students</h1>
    <a href="create.php" class="btn btn-primary mb-3">Add Student</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Created At</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['class_name']) ?></td>
                <td><?= htmlspecialchars($student['created_at']) ?></td>
                <td><img src="<?= htmlspecialchars($student['image']) ?>" width="50" height="50"></td>
                <td>
                    <a href="view.php?id=<?= $student['id'] ?>" class="btn btn-info">View</a>
                    <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
