<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $stmt = getDB()->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
}

header('Location: students.php?deleted=1');
exit;
