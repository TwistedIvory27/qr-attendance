<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$id = $_GET['id'] ?? '';
$error = '';

$stmt = $db->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: students.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $grade     = trim($_POST['grade'] ?? '');
    $section   = trim($_POST['section'] ?? '');
    $strand    = trim($_POST['strand'] ?? '');

    if ($full_name === '' || $grade === '' || $section === '') {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $db->prepare(
            "UPDATE students SET full_name = ?, grade = ?, section = ?, strand = ? WHERE student_id = ?"
        );
        $stmt->execute([$full_name, $grade, $section, $strand ?: null, $id]);
        header('Location: students.php?updated=1');
        exit;
    }
}

$pageTitle = 'Edit Student';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Edit Student — <?php echo htmlspecialchars($student['student_id']); ?></h1>
<?php if ($error): ?>
    <p class="alert alert-error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" class="card form-card">
    <label>Student ID
        <input type="text" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
    </label>
    <label>Full Name *
        <input type="text" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? $student['full_name']); ?>">
    </label>
    <label>Grade *
        <input type="text" name="grade" required value="<?php echo htmlspecialchars($_POST['grade'] ?? $student['grade']); ?>">
    </label>
    <label>Section *
        <input type="text" name="section" required value="<?php echo htmlspecialchars($_POST['section'] ?? $student['section']); ?>">
    </label>
    <label>Course/Strand (optional)
        <input type="text" name="strand" value="<?php echo htmlspecialchars($_POST['strand'] ?? $student['strand']); ?>">
    </label>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="students.php" class="btn btn-secondary">Cancel</a>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
