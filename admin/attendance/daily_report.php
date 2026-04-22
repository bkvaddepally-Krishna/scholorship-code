<?php
/**
 * DAILY ATTENDANCE AUDIT v1.1
 * Location: C:\xampp\htdocs\school-erp-pro\admin\attendance\daily_report.php
 */

include '../../config/auth.php';
include '../../config/db.php';

// --- FILTERS ---
$class_id = $_GET['class_id'] ?? null;
$date     = $_GET['date'] ?? date('Y-m-d');

// Fetch classes for dropdown
$classes_query = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name ASC");

// --- DATA PROCESSING ---
$students_report = [];

if ($class_id) {
    // Fetch students and their attendance for the SPECIFIC date selected
    $sql = "SELECT s.id, s.name, a.status, a.created_at
            FROM students s 
            LEFT JOIN attendance a ON s.id = a.student_id AND a.date = ?
            WHERE s.class_id = ? 
            ORDER BY s.name ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $date, $class_id);
    $stmt->execute();
    $students_report = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Audit | MST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0f172a; --card: rgba(30, 41, 59, 0.7); --accent: #38bdf8; }
        body { background: var(--bg); color: #f1f5f9; font-family: 'Inter', sans-serif; padding: 20px; }
        .glass-card { background: var(--card); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 25px; }
        
        .status-badge { width: 100px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .status-Present { background: #166534; color: #4ade80; }
        .status-Absent { background: #7f1d1d; color: #f87171; }
        .status-Late { background: #78350f; color: #fbbf24; }
        .status-NotMarked { background: #334155; color: #94a3b8; }

        .form-select, .form-control { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
        
        @media print { 
            .no-print { display: none; } 
            body { background: white; color: black; padding: 0; }
            .glass-card { border: none; background: transparent; }
            .table { color: black !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="../index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left"></i> Dashboard</a>
        <h4 class="text-uppercase fw-bold mb-0" style="color: var(--accent);">Daily Attendance Audit</h4>
        <button onclick="window.print()" class="btn btn-info btn-sm px-4"><i class="bi bi-printer"></i> PRINT</button>
    </div>

    <div class="glass-card mb-4 no-print">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="small text-muted text-uppercase mb-2 d-block">Select Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Choose Class --</option>
                    <?php while($c = $classes_query->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= $class_id == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="small text-muted text-uppercase mb-2 d-block">Report Date</label>
                <input type="date" name="date" class="form-control" value="<?= $date ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">VIEW</button>
            </div>
        </form>
    </div>

    <?php if ($class_id): ?>
    <div class="glass-card p-0 overflow-hidden">
        <div class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Attendance Record</h5>
                <small class="text-muted">Target Date: <?= date('d M, Y', strtotime($date)) ?></small>
            </div>
            <div class="text-end">
                <span class="badge bg-dark text-info border border-info px-3">DPSS SIDDIPET</span>
            </div>
        </div>

        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="text-muted small">
                <tr>
                    <th class="ps-4">STUDENT ID</th>
                    <th>STUDENT NAME</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-end pe-4">LOG TIME</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $p = 0; $a = 0; $l = 0;
                while($row = $students_report->fetch_assoc()): 
                    $st = $row['status'] ?? 'Not Marked';
                    if($st == 'Present') $p++;
                    if($st == 'Absent') $a++;
                    if($st == 'Late') $l++;
                ?>
                <tr>
                    <td class="ps-4 text-info fw-bold">#<?= $row['id'] ?></td>
                    <td class="fw-medium"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center">
                        <span class="badge status-<?= str_replace(' ', '', $st) ?> status-badge">
                            <?= $st ?>
                        </span>
                    </td>
                    <td class="text-end pe-4 text-muted small">
                        <?= $row['created_at'] ? date('h:i A', strtotime($row['created_at'])) : '--:--' ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="p-4 bg-black bg-opacity-25 border-top border-secondary row mx-0 text-center">
            <div class="col-4 border-end border-secondary">
                <h4 class="mb-0 text-success fw-bold"><?= $p ?></h4>
                <small class="text-muted text-uppercase">Present</small>
            </div>
            <div class="col-4 border-end border-secondary">
                <h4 class="mb-0 text-danger fw-bold"><?= $a ?></h4>
                <small class="text-muted text-uppercase">Absent</small>
            </div>
            <div class="col-4">
                <h4 class="mb-0 text-warning fw-bold"><?= $l ?></h4>
                <small class="text-muted text-uppercase">Late</small>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>