<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$totalStudents = $db->query("SELECT COUNT(*) c FROM students")->fetch()['c'];
$today = date('Y-m-d');
$presentToday = $db->prepare("SELECT COUNT(*) c FROM attendance WHERE date = ?");
$presentToday->execute([$today]);
$presentToday = $presentToday->fetch()['c'];

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Dashboard</h1>
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-number"><?php echo $totalStudents; ?></span>
        <span class="stat-label">Total Students</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $presentToday; ?></span>
        <span class="stat-label">Present Today</span>
    </div>
</div>

<div class="quick-links">
    <a class="btn btn-secondary" href="students.php">Manage Students</a>
    <a class="btn btn-secondary" href="../scanner/scan.php">Open Scanner</a>
    <a class="btn btn-secondary" href="../attendance/dashboard.php">View Attendance</a>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

