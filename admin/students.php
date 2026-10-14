<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $db->prepare(
        "SELECT * FROM students
         WHERE student_id LIKE ? OR full_name LIKE ? OR grade LIKE ? OR section LIKE ?
         ORDER BY full_name"
    );
    $like = "%$search%";
    $stmt->execute([$like, $like, $like, $like]);
} else {
    $stmt = $db->query("SELECT * FROM students ORDER BY full_name");
}
$students = $stmt->fetchAll();

$pageTitle = 'Students';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <h1>Students</h1>
    <a class="btn btn-primary" href="add_student.php">+ Add Student</a>
</div>

<form method="get" class="search-form">
    <input type="text" name="q" placeholder="Search by ID, name, grade, or section"
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn btn-secondary">Search</button>
</form>

<table class="data-table">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Grade</th>
            <th>Section</th>
            <th>Strand</th>
            <th>QR Code</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!$students): ?>
        <tr><td colspan="7" class="empty">No students found.</td></tr>
    <?php endif; ?>
    <?php foreach ($students as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['student_id']); ?></td>
            <td><?php echo htmlspecialchars($s['full_name']); ?></td>
            <td><?php echo htmlspecialchars($s['grade']); ?></td>
            <td><?php echo htmlspecialchars($s['section']); ?></td>
            <td><?php echo htmlspecialchars($s['strand'] ?? '-'); ?></td>
            <td><a href="qr_code.php?id=<?php echo urlencode($s['student_id']); ?>">View/Download</a></td>
            <td class="actions">
                <a href="edit_student.php?id=<?php echo urlencode($s['student_id']); ?>">Edit</a>
                <form method="post" action="delete_student.php" onsubmit="return confirm('Delete this student?');" style="display:inline">
                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($s['student_id']); ?>">
                    <button type="submit" class="link-btn danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
