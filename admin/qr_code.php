<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? '';
$stmt = getDB()->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: students.php');
    exit;
}

$pageTitle = 'QR Code';
require_once __DIR__ . '/../includes/header.php';
?>
<?php if (!empty($_GET['new'])): ?>
    <p class="alert alert-success">Student added successfully! QR code generated below.</p>
<?php endif; ?>

<h1>QR Code — <?php echo htmlspecialchars($student['full_name']); ?></h1>

<div class="card qr-card">
    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
    <p><strong>Grade &amp; Section:</strong> <?php echo htmlspecialchars($student['grade'] . ' - ' . $student['section']); ?></p>

    <div id="qrcode" class="qr-render"></div>

    <div class="qr-actions">
        <button class="btn btn-primary" onclick="downloadQR()">Download PNG</button>
        <button class="btn btn-secondary" onclick="window.print()">Print</button>
        <a class="btn btn-secondary" href="students.php">Back to Students</a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
const qrValue = <?php echo json_encode($student['qr_value']); ?>;
const studentId = <?php echo json_encode($student['student_id']); ?>;

const qrDiv = document.getElementById('qrcode');
new QRCode(qrDiv, {
    text: qrValue,
    width: 256,
    height: 256,
    correctLevel: QRCode.CorrectLevel.H
});

function downloadQR() {
    // qrcodejs renders either an <img> or a <canvas> depending on browser
    const img = qrDiv.querySelector('img');
    const canvas = qrDiv.querySelector('canvas');
    const dataUrl = canvas ? canvas.toDataURL('image/png') : img.src;

    const link = document.createElement('a');
    link.href = dataUrl;
    link.download = 'QR_' + studentId + '.png';
    link.click();
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
