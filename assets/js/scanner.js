const statusText = document.getElementById('status-text');
const recentList = document.getElementById('recent-list');

let isProcessing = false;      
let lastScannedValue = null;
let lastScannedAt = 0;
const RESCAN_COOLDOWN_MS = 4000;

function setStatus(text, type) {
    statusText.textContent = text;
    statusText.className = 'status-text ' + (type || '');
}

function addToRecentLog(entry) {
    const li = document.createElement('li');
    li.className = 'recent-item ' + entry.type;
    li.innerHTML = `
        <span class="recent-name">${entry.name}</span>
        <span class="recent-meta">${entry.id} · ${entry.time}</span>
        <span class="recent-status">${entry.label}</span>
    `;
    recentList.prepend(li);

  
    while (recentList.children.length > 8) {
        recentList.removeChild(recentList.lastChild);
    }
}

async function onScanSuccess(decodedText) {
    const now = Date.now();

    if (decodedText === lastScannedValue && (now - lastScannedAt) < RESCAN_COOLDOWN_MS) {
        return;
    }
    if (isProcessing) return;

    isProcessing = true;
    lastScannedValue = decodedText;
    lastScannedAt = now;

    setStatus('Student Found! Verifying...', 'checking');

    try {
        const response = await fetch('/qr-attendance/scanner/verify_attendance.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ qr_value: decodedText })
        });
        const data = await response.json();

        if (data.status === 'success') {
            setStatus('Attendance Recorded Successfully!', 'success');
            addToRecentLog({
                name: data.student.full_name,
                id: data.student.student_id,
                time: data.student.time,
                label: 'Present',
                type: 'success'
            });
        } else if (data.status === 'duplicate') {
            setStatus('Already recorded today (' + data.student.time + ')', 'warning');
            addToRecentLog({
                name: data.student.full_name,
                id: data.student.student_id,
                time: data.student.time,
                label: 'Duplicate',
                type: 'warning'
            });
        } else if (data.status === 'not_found') {
            setStatus('QR Code not recognized.', 'error');
        } else {
            setStatus(data.message || 'Something went wrong.', 'error');
        }
    } catch (err) {
        setStatus('Network error — could not reach server.', 'error');
    }

    setTimeout(() => {
        if (statusText.classList.contains('success') || statusText.classList.contains('warning') || statusText.classList.contains('error')) {
            setStatus('Waiting for QR Code...', '');
        }
        isProcessing = false;
    }, 2500);
}

function onScanFailure() {

}

document.addEventListener('DOMContentLoaded', () => {
    const html5QrCode = new Html5Qrcode('reader');

    html5QrCode.start(
        { facingMode: 'environment' },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanFailure
    ).catch((err) => {
        setStatus('Camera access denied or unavailable.', 'error');
        console.error('Unable to start scanner:', err);
    });
});
