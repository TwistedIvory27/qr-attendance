<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$qrValue = trim($input['qr_value'] ?? '');

if ($qrValue === '') {
    echo json_encode(['status' => 'error', 'message' => 'No QR value received.']);
    exit;
}

$db = getDB();

// Look up the student by QR value
$stmt = $db->prepare("SELECT * FROM students WHERE qr_value = ?");
$stmt->execute([$qrValue]);
$student = $stmt->fetch();

if (!$student) {
    echo json_encode(['status' => 'not_found', 'message' => 'Student not found.']);
    exit;
}

$today = date('Y-m-d');
$now = date('H:i:s');

// Check for an existing attendance record today (prevents duplicates)
$check = $db->prepare("SELECT * FROM attendance WHERE student_id = ? AND date = ?");
$check->execute([$student['student_id'], $today]);
$existing = $check->fetch();

if ($existing) {
    echo json_encode([
        'status' => 'duplicate',
        'message' => 'Attendance already recorded today.',
        'student' => [
            'student_id' => $student['student_id'],
            'full_name'  => $student['full_name'],
            'time'       => $existing['time'],
        ]
    ]);
    exit;
}

// Insert new attendance record
$insert = $db->prepare(
    "INSERT INTO attendance (student_id, date, time, status) VALUES (?, ?, ?, 'Present')"
);
$insert->execute([$student['student_id'], $today, $now]);

echo json_encode([
    'status' => 'success',
    'message' => 'Attendance recorded successfully!',
    'student' => [
        'student_id' => $student['student_id'],
        'full_name'  => $student['full_name'],
        'grade'      => $student['grade'],
        'section'    => $student['section'],
        'time'       => $now,
    ]
]);
