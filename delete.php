<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the student record to get the image path
    $sql = "SELECT image FROM student WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $student = $stmt->fetch();

    if ($student) {
        // Delete the student record
        $sql = "DELETE FROM student WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        // Delete the image file from the server
        if (!empty($student['image']) && file_exists($student['image'])) {
            unlink($student['image']);
        }

        header('Location: index.php');
        exit;
    } else {
        echo "Student not found.";
    }
} else {
    echo "Invalid request.";
}
