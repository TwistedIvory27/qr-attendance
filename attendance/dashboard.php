<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$db = getDB();

$date = $_GET['date'] ?? date('Y-m-d');
$search = trim($_GET['q'] ?? '');

$sql = "SELECT a.*, s.full_name, s.grade, s.section
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

$pageTitle = 'Attendance';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <h1>Attendance Records</h1>
    
</div>

<form method="get" class="search-form">
    <label>Date
        <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
    </label>
    <label>Search
        <input type="text" name="q" placeholder="Student name or ID" value="<?php echo htmlspecialchars($search); ?>">
    </label>
    <button type="submit" class="btn btn-secondary">Filter</button>
</form>

<table class="data-table">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Grade &amp; Section</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!$records): ?>
        <tr><td colspan="6" class="empty">No attendance records for this date.</td></tr>
    <?php endif; ?>
    <?php foreach ($records as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r['student_id']); ?></td>
            <td><?php echo htmlspecialchars($r['full_name']); ?></td>
            <td><?php echo htmlspecialchars($r['grade'] . ' - ' . $r['section']); ?></td>
            <td><?php echo htmlspecialchars($r['date']); ?></td>
            <td><?php echo htmlspecialchars($r['time']); ?></td>
            <td><span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
