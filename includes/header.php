<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' – ' : ''; ?>QR Attendance System</title>
<link rel="stylesheet" href="/qr-attendance/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand"><a href="/qr-attendance/index.php" style="color: inherit; text-decoration: none;">Comlab QR</a></div>
    <?php if (!empty($_SESSION['admin_id'])): ?>
    <nav>
        <a href="/qr-attendance/admin/students.php">Students</a>
        <a href="/qr-attendance/attendance/dashboard.php">Attendance</a>
        <a href="/qr-attendance/logout.php">Logout</a>
    </nav>
    <?php else: ?>
    <nav>
        <a href="/qr-attendance/index.php">Scanner</a>
        <a href="/qr-attendance/login.php">Admin Login</a>
    </nav>
    <?php endif; ?>
</header>
<main class="container">
