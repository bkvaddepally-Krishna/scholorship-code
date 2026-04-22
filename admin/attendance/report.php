<?php
/**
 * ATTENDANCE INTELLIGENCE REPORT v1.0
 * Location: C:\xampp\htdocs\school-erp-pro\admin\attendance\report.php
 */

include '../../config/auth.php';
include '../../config/db.php';

// --- FILTERS ---
$class_id = $_GET['class_id'] ?? null;
$month    = $_GET['month'] ?? date('m');
$year     = $_GET['year'] ?? date('Y');

// Calculate days in the selected month
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Fetch classes for dropdown
$classes_query = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name ASC");

// --- DATA PROCESSING ---
$report_data = [];
$student_names = [];

if ($class_id) {
    // 1. Get all students in the class
    $students_res = $conn->query("SELECT id, name FROM students WHERE class_id = $class_id ORDER BY name ASC");
    
    while ($row = $students_res->fetch_assoc()) {
        $student_names[$row['id']] = $row['name'];
        // Initialize all days as empty
        for ($d = 1; $d <= $days_in_month; $d++) {
            $report_data[$row['id']][$d] = '-'; 
        }
    }

    // 2. Fetch attendance records for this class and month
    $att_res = $conn->query("SELECT student_id, DAY(date) as day, status 
                             FROM attendance 
                             WHERE MONTH(date) = '$month' 
                             AND YEAR(date) = '$year'
                             AND student_id IN (" . implode(',', array_keys($student_names)) . ")");

    while ($att = $att_res->fetch_assoc()) {
        $report_data[$att['student_id']][$att['day']] = $att['status'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Intelligence | MST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0f172a; --card: rgba(30, 41, 59, 0.7); --accent: #38bdf8; }
        body { background: var(--bg); color: #f1f5f9; font-family: 'Inter', sans-serif; padding: 20px; }
        .glass-card { background: var(--card); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 20px; }
        
        /* Table Styles */
        .table-report { font-size: 0.75rem; border-collapse: separate; border-spacing: 2px; }
        .table-report th, .table-report td { padding: 4px; text-align: center; border: none !important; min-width: 25px; }
        .st-name { text-align: left !important; min-width: 150px; font-weight: 600; color: var(--accent); }
        
        /* Status Indicators */
        .cell-P { background: #166534; color: #4ade80; border-radius: 3px; } /* Present */
        .cell-A { background: #7f1d1d; color: #f87171; border-radius: 3px; } /* Absent */
        .cell-L { background: #78350f; color: #fbbf24; border-radius: 3px; } /* Late */
        .cell-empty { background: rgba(255,255,255,0.03); color: #475569; border-radius: 3px; }

        .form-select, .form-control { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
        @media print { .no-print { display: none; } body { background: #white; color: black; } }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="../index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left"></i> Dashboard</a>
        <h4 class="text-uppercase fw-bold mb-0">Monthly Attendance Report</h4>
        <button onclick="window.print()" class="btn btn-info btn-sm px-4"><i class="bi bi-printer"></i> PRINT REPORT</button>
    </div>

    <div class="glass-card mb-4 no-print">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="small text-muted text-uppercase mb-1">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Choose Class --</option>
                    <?php while($c = $classes_query->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= $class_id == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small text-muted text-uppercase mb-1">Month</label>
                <select name="month" class="form-select">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?= sprintf('%02d', $m) ?>" <?= $month == $m ? 'selected' : '' ?>>
                            <?= date('F', mktime(0,0,0,$m,1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small text-muted text-uppercase mb-1">Year</label>
                <select name="year" class="form-select">
                    <option value="2026" selected>2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 fw-bold">GENERATE</button>
            </div>
        </form>
    </div>

    <?php if ($class_id && !empty($student_names)): ?>
    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-report">
                <thead>
                    <tr>
                        <th class="st-name">Student Name</th>
                        <?php for($d=1; $d<=$days_in_month; $d++): ?>
                            <th><?= $d ?></th>
                        <?php endfor; ?>
                        <th class="ps-3 text-success">P</th>
                        <th class="text-danger">A</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($student_names as $id => $name): 
                        $p_count = 0; $a_count = 0;
                    ?>
                    <tr>
                        <td class="st-name"><?= $name ?></td>
                        <?php for($d=1; $d<=$days_in_month; $d++): 
                            $st = $report_data[$id][$d];
                            $class = "cell-empty";
                            if($st == 'Present') { $class = "cell-P"; $p_count++; }
                            if($st == 'Absent') { $class = "cell-A"; $a_count++; }
                            if($st == 'Late') { $class = "cell-L"; }
                        ?>
                            <td class="<?= $class ?>"><?= substr($st, 0, 1) ?></td>
                        <?php endfor; ?>
                        <td class="ps-3 text-success fw-bold"><?= $p_count ?></td>
                        <td class="text-danger fw-bold"><?= $a_count ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif($class_id): ?>
        <div class="alert alert-warning">No students found in this class.</div>
    <?php endif; ?>

    <div class="mt-4 no-print">
        <span class="badge cell-P me-2">P = Present</span>
        <span class="badge cell-A me-2">A = Absent</span>
        <span class="badge cell-L me-2">L = Late</span>
    </div>
</div>

</body>
</html>