<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$date = $_GET['date'] ?? date('Y-m-d');
$search = trim($_GET['q'] ?? '');

$sql = "SELECT a.student_id, s.full_name, s.grade, s.section, a.date, a.time, a.status
        FROM attendance a
        JOIN students s ON s.student_id = a.student_id
        WHERE a.date = ?";
$params = [$date];

if ($search !== '') {
    $sql .= " AND (s.full_name LIKE ? OR s.student_id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql .= " ORDER BY a.time DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$records = $stmt->fetchAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendance_' . $date . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Student ID', 'Full Name', 'Grade', 'Section', 'Date', 'Time', 'Status']);
foreach ($records as $r) {
    fputcsv($out, [
        $r['student_id'], $r['full_name'], $r['grade'], $r['section'],
        $r['date'], $r['time'], $r['status']
    ]);
}
fclose($out);
exit;
