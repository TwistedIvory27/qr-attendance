<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Scanner';
require_once __DIR__ . '/includes/header.php';
?>
<div class="scanner-page">
    <h1>Attendance Scanner</h1>

    <div class="scanner-frame">
        <div id="reader"></div>
        <p id="status-text" class="status-text">Waiting for QR Code...</p>
    </div>

    <div class="recent-log">
        <h2>Recently Scanned</h2>
        <ul id="recent-list"></ul>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script src="/qr-attendance/assets/js/scanner.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
