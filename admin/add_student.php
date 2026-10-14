<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$error = '';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    $grade      = trim($_POST['grade'] ?? '');
    $section    = trim($_POST['section'] ?? '');
    $strand     = trim($_POST['strand'] ?? '');

    if ($student_id === '' || $full_name === '' || $grade === '' || $section === '') {
        $error = 'Please fill in all required fields.';
    } else {
        
        $qr_value = 'STU-' . $student_id;

        try {
            $stmt = $db->prepare(
                "INSERT INTO students (student_id, full_name, grade, section, strand, qr_value)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$student_id, $full_name, $grade, $section, $strand ?: null, $qr_value]);
            header('Location: qr_code.php?id=' . urlencode($student_id) . '&new=1');
            exit;
        } catch (PDOException $e) {
            $error = ($e->getCode() == 23000)
                ? 'A student with that ID already exists.'
                : 'Error saving student: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Add Student';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Add Student</h1>
<?php if ($error): ?>
    <p class="alert alert-error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" class="card form-card">
    <label>Student ID *
        <input type="text" name="student_id" required value="<?php echo htmlspecialchars($_POST['student_id'] ?? ''); ?>">
    </label>
    <label>Full Name *
        <input type="text" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
    </label>
    <label>Grade *
        <input type="text" name="grade" required value="<?php echo htmlspecialchars($_POST['grade'] ?? ''); ?>">
    </label>
    <label>Section *
        <input type="text" name="section" required value="<?php echo htmlspecialchars($_POST['section'] ?? ''); ?>">
    </label>
    <label>Course/Strand (optional)
        <input type="text" name="strand" value="<?php echo htmlspecialchars($_POST['strand'] ?? ''); ?>">
    </label>
    <button type="submit" class="btn btn-primary">Save & Generate QR</button>
    <a href="students.php" class="btn btn-secondary">Cancel</a>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
