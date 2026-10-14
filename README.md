# QR Code Attendance System — Project Skeleton

A minimal, working starting point for the full system described in the project plan.
Built with PHP + MySQL (PDO) + vanilla JS, designed to run under XAMPP.

## 1. Setup

1. Copy the `qr-attendance` folder into your XAMPP `htdocs` directory, e.g.:
   `C:\xampp\htdocs\qr-attendance` (Windows) or `/Applications/XAMPP/htdocs/qr-attendance` (Mac).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`), click **Import**, and import
   `sql/schema.sql`. This creates the `qr_attendance` database with the `students`,
   `attendance`, and `admins` tables, plus a default admin account.
4. If your MySQL root user has a password, update `config/database.php` (`DB_PASS`).
5. Visit `http://localhost/qr-attendance/` in your browser.

## 2. Default login

- **Username:** `admin`
- **Password:** `admin123`

Change this immediately in production (there's no "change password" UI yet — update the
`admins` table directly, using `password_hash()` in PHP to generate a new hash).

## 3. What's included

| Area | File(s) | Notes |
|---|---|---|
| Login/session | `login.php`, `logout.php`, `includes/auth.php` | Simple single-role login. Multi-role (admin/teacher) is a listed future improvement. |
| Student CRUD | `admin/students.php`, `add_student.php`, `edit_student.php`, `delete_student.php` | Search, add, edit, delete. |
| QR generation | `admin/qr_code.php` | Generates a QR code **client-side** (via `qrcode.js`) from a `STU-<studentID>` value stored in the DB; downloadable as PNG, printable. |
| Scanner | `scanner/scan.php`, `assets/js/scanner.js` | Uses `html5-qrcode` to read the webcam feed, shows live status ("Waiting…", "Student Found!", "Attendance Recorded"), keeps a short recent-scans log. |
| Attendance logging | `scanner/verify_attendance.php` | Looks up the student by QR value, blocks duplicate same-day records (DB has a `UNIQUE(student_id, date)` constraint as a second safety net), inserts the record. |
| Attendance dashboard | `attendance/dashboard.php`, `export_csv.php` | Filter by date, search by name/ID, export CSV. |

## 4. How the QR codes work

Each student gets a `qr_value` of the form `STU-<student_id>` stored in the database when
they're added. The QR image itself is never stored — it's generated on the fly whenever you
view/download/print it, and the scanner just needs to read that same string back and match it
against the `qr_value` column. This keeps the DB small and makes regenerating a lost QR trivial
(future improvement: add a "regenerate QR" button — since the value is derived from the student
ID, this is basically already supported, just re-open `qr_code.php`).

## 5. Things to build next (from the project plan's future improvements)

- Split login into Admin vs Teacher roles with different permissions.
- Add student profile photos (extend `students` table with a `photo_path` column).
- Attendance statistics/reports page (e.g. chart of attendance % per section).
- Manual attendance entry (for when a QR code is lost/damaged).
- Email/SMS absence notifications (would need a cron job scanning yesterday's absentees).
- Mobile-friendly polish (basic responsive CSS is already in place as a starting point).

## 6. Notes / things to double check

- The scanner requires HTTPS or `localhost` for camera access in most browsers — `http://localhost/...`
  is fine for local development, but a production deployment on a different domain will need HTTPS.
- `html5-qrcode` and `qrcode.js` are loaded from a public CDN (cdnjs) — if you need a fully offline
  setup, download those two JS files and reference them locally instead of the CDN URLs in
  `scanner/scan.php` and `admin/qr_code.php`.
