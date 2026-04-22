<?php
/**
 * CORE CONTROL DASHBOARD v3.4 - "Ultimate Enterprise Edition"
 * Location: C:\xampp\htdocs\school-erp-pro\admin\index.php
 */

include '../config/auth.php'; 
include '../config/db.php';   

// --- FETCH SYSTEM METRICS ---
$students    = $conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'] ?? 0;
$classes     = $conn->query("SELECT COUNT(*) c FROM classes")->fetch_assoc()['c'] ?? 0;
$subjects    = $conn->query("SELECT COUNT(*) c FROM subjects")->fetch_assoc()['c'] ?? 0;
$logs_count  = $conn->query("SELECT COUNT(*) c FROM logs")->fetch_assoc()['c'] ?? 0;

// --- FETCH REAL DATA FOR ANALYTICS ---
$real_logs = $conn->query("SELECT * FROM logs ORDER BY id DESC LIMIT 6");

// FETCH ALL CLASSES (No Limit) for the Distribution Chart
$distribution = $conn->query("SELECT c.class_name, COUNT(s.id) as count 
                             FROM classes c 
                             LEFT JOIN students s ON c.id = s.class_id 
                             GROUP BY c.id 
                             ORDER BY c.class_name ASC");

$chart_labels = [];
$chart_data = [];
while($row = $distribution->fetch_assoc()) {
    $chart_labels[] = $row['class_name'];
    $chart_data[] = (int)$row['count'];
}

$uptime = "99.9% - Stable";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Control | MST 2026</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-deep: #0f172a;       
            --bg-card: rgba(30, 41, 59, 0.7); 
            --sidebar-blue: #1e293b;  
            --accent-primary: #38bdf8; 
            --accent-success: #22c55e; 
            --border-muted: rgba(51, 65, 85, 0.5); 
            --text-main: #f1f5f9;      
            --text-dim: #94a3b8;       
        }

        body {
            background: var(--bg-deep);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed; width: 260px; height: 100vh;
            background: var(--sidebar-blue); padding: 20px;
            border-right: 1px solid var(--border-muted); z-index: 1000;
            overflow-y: auto;
        }

        .sidebar h3 { color: var(--accent-primary); font-weight: 800; font-size: 1.2rem; }
        
        .sidebar a {
            display: flex; align-items: center; padding: 10px 15px;
            color: var(--text-dim); text-decoration: none;
            border-radius: 8px; margin-bottom: 5px; transition: 0.3s;
            font-size: 0.85rem;
        }

        .sidebar a i { margin-right: 10px; font-size: 1.1rem; }
        .sidebar a:hover { background: rgba(56, 189, 248, 0.1); color: var(--accent-primary); }
        .sidebar a.active { background: var(--accent-primary); color: #0f172a; font-weight: bold; }

        /* Main Area */
        .main { margin-left: 280px; padding: 25px; }

        .card-box {
            background: var(--bg-card); backdrop-filter: blur(12px);
            padding: 20px; border-radius: 12px; border: 1px solid var(--border-muted);
            transition: 0.3s;
        }

        .card-box:hover { border-color: var(--accent-primary); transform: translateY(-3px); }

        .section-title {
            color: var(--accent-primary); font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 2px;
            margin: 30px 0 15px 0; border-left: 3px solid var(--accent-primary); padding-left: 10px;
        }

        /* Console Buttons */
        .btn-console {
            background: rgba(56, 189, 248, 0.05); border: 1px solid var(--border-muted);
            color: var(--text-dim); padding: 15px; border-radius: 10px;
            transition: 0.3s; text-decoration: none; display: block; text-align: center;
        }

        .btn-console:hover {
            border-color: var(--accent-primary); background: var(--accent-primary);
            color: #0f172a; box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
        }

        .pulse-dot {
            height: 8px; width: 8px; background-color: var(--accent-success);
            border-radius: 50%; display: inline-block; margin-right: 5px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

        .table-dark { background: transparent !important; }
        .table-dark td, .table-dark th { border-color: var(--border-muted); background: transparent !important; }

        @media(max-width: 992px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .main { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 class="mb-4"><i class="bi bi-cpu-fill"></i> MST CORE v3.4</h3>
    
    <a href="index.php" class="active"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    
    <div class="text-muted small mt-4 mb-2 px-3 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">REGISTRATION</div>
    <a href="students/add.php"><i class="bi bi-person-plus"></i> New Student</a>
    <a href="students/bulk_upload.php"><i class="bi bi-file-earmark-arrow-up"></i> Bulk Upload</a>
    <a href="students/view.php"><i class="bi bi-person-badge"></i> View Students</a>
    
    <div class="text-muted small mt-4 mb-2 px-3 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">ACADEMIC CONTROL</div>
    <a href="classes/add.php"><i class="bi bi-building-add"></i> Manage Classes</a>
    <a href="classes/list_ids.php"><i class="bi bi-hash"></i> Class ID Master</a>
    <a href="subjects/add.php"><i class="bi bi-book"></i> Subjects</a>
    <a href="classes/assign_subjects.php"><i class="bi bi-link-45deg"></i> Assign Subjects</a>
    
<a href="attendance/mark_attendance.php" class="nav-link text-white mb-2 px-3 d-flex align-items-center">
    <i class="bi bi-calendar-check me-2 text-info"></i> 
    <span>Mark Attendance</span>
</a>

<a href="attendance/daily_report.php" class="nav-link text-white mb-2 px-3 d-flex align-items-center">
    <i class="bi bi-file-earmark-text me-2 text-warning"></i> 
    <span>Daily Report</span>
</a>

<a href="attendance/report.php" class="nav-link text-white mb-2 px-3 d-flex align-items-center">
    <i class="bi bi-graph-up-arrow me-2 text-success"></i> 
    <span>Monthly Analytics</span>
</a>
    
    <div class="text-muted small mt-4 mb-2 px-3 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">EXAMS & RESULTS</div>
    <a href="exams/create.php"><i class="bi bi-pencil-square"></i> Create Exams</a>
    <a href="marks/class_marks.php"><i class="bi bi-file-earmark-spreadsheet"></i> Marks Entry</a>
     <a href="marks/class_full_marks.php"><i class="bi bi-file-earmark-spreadsheet"></i> Marks Entry</a>
    <a href="notifications/send_results.php"><i class="bi bi-whatsapp"></i> Dispatch Results</a>
    <a href="add_notice.php"><i class="bi bi-megaphone"></i> Notice Board</a>

    <div class="text-muted small mt-4 mb-2 px-3 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">SYSTEM TOOLS</div>
    <a href="../public/hallticket.php" target="_blank"><i class="bi bi-printer"></i> Hall Ticket</a>
    <a href="../public/result.php" target="_blank"><i class="bi bi-mortarboard"></i> Results (Public)</a>
    <a href="logs/"><i class="bi bi-journal-text"></i> System Logs</a>
    <a href="settings/"><i class="bi bi-gear"></i> Settings</a>
</div>

<div class="main">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-uppercase" style="color: var(--accent-primary);">System Overview</h2>
            <span class="text-dim"><span class="pulse-dot"></span> Node: Siddipet_Central_v3.4</span>
        </div>
        <div class="text-end">
            <div class="badge border border-info text-info mb-1">NETWORK UPTIME: <?= $uptime ?></div>
            <div class="text-dim small text-uppercase" style="font-size: 0.7rem;"><?= date('D, d M Y | H:i') ?></div>
        </div>
    </div>

    <div class="section-title">Command shortcuts</div>
    <div class="row g-3 mb-4">
        <div class="col-md-2"><a href="students/view.php" class="btn-console"><i class="bi bi-search d-block mb-1 fs-5"></i> SEARCH</a></div>
        <div class="col-md-2"><a href="notifications/send_results.php" class="btn-console"><i class="bi bi-chat-dots d-block mb-1 fs-5"></i> RESULTS</a></div>
        <div class="col-md-2"><a href="attendance/mark_attendance.php" class="btn-console"><i class="bi bi-calendar-check d-block mb-1 fs-5"></i> ATTEND</a></div>
        <div class="col-md-2"><a href="add_notice.php" class="btn-console"><i class="bi bi-plus-circle d-block mb-1 fs-5"></i> NOTICE</a></div>
        <div class="col-md-2"><a href="logs/" class="btn-console"><i class="bi bi-shield-lock d-block mb-1 fs-5"></i> SECURITY</a></div>
        <div class="col-md-2"><a href="settings/" class="btn-console"><i class="bi bi-sliders d-block mb-1 fs-5"></i> CONFIG</a></div>
    </div>
<div class="row g-4 mt-2 mb-4">
    <div class="col-md-4">
        <a href="attendance/mark_attendance.php" class="text-decoration-none">
            <div class="card-box text-center p-4 hover-effect">
                <i class="bi bi-fingerprint fs-1 text-info mb-3"></i>
                <h5 class="text-white">Take Attendance</h5>
                <p class="small text-dim mb-0">Register student presence for today</p>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="attendance/daily_report.php" class="text-decoration-none">
            <div class="card-box text-center p-4 hover-effect">
                <i class="bi bi-clipboard-data fs-1 text-warning mb-3"></i>
                <h5 class="text-white">Daily Audit</h5>
                <p class="small text-dim mb-0">View date-wise attendance logs</p>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="attendance/report.php" class="text-decoration-none">
            <div class="card-box text-center p-4 hover-effect">
                <i class="bi bi-graph-up-arrow fs-1 text-success mb-3"></i>
                <h5 class="text-white">Monthly Analytics</h5>
                <p class="small text-dim mb-0">Heatmap & monthly performance</p>
            </div>
        </a>
    </div>
</div>
   

<style>
.hover-effect { transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1); }
.hover-effect:hover { transform: translateY(-5px); background: rgba(56, 189, 248, 0.1); border-color: #38bdf8; }
</style>
    <div class="row g-3">
        <div class="col-md-3"><div class="card-box text-center"><small class="text-dim text-uppercase d-block mb-1">Students</small><h2 class="mb-0 fw-bold"><?= number_format($students) ?></h2></div></div>
        <div class="col-md-3"><div class="card-box text-center"><small class="text-dim text-uppercase d-block mb-1">Active Classes</small><h2 class="mb-0 fw-bold text-info"><?= $classes ?></h2></div></div>
        <div class="col-md-3"><div class="card-box text-center"><small class="text-dim text-uppercase d-block mb-1">Total Subjects</small><h2 class="mb-0 fw-bold text-warning"><?= $subjects ?></h2></div></div>
        <div class="col-md-3"><div class="card-box text-center"><small class="text-dim text-uppercase d-block mb-1">Audit Entries</small><h2 class="mb-0 fw-bold text-danger"><?= $logs_count ?></h2></div></div>
        
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="section-title">Registry Distribution (All Classes)</div>
            <div class="card-box" style="height: 400px;">
                <canvas id="distChart"></canvas>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="section-title">Live Audit Trail (Direct DB)</div>
            <div class="card-box" style="height: 400px; overflow-y: auto;">
                <table class="table table-dark table-hover mb-0" style="font-size: 0.85rem;">
                    <thead><tr><th class="text-dim">TIME</th><th class="text-dim">USER</th><th class="text-dim">ACTION</th></tr></thead>
                    <tbody>
                        <?php while($l = $real_logs->fetch_assoc()): ?>
                        <tr>
                            <td class="text-info"><?= isset($l['created_at']) ? date('H:i', strtotime($l['created_at'])) : '00:00' ?></td>
                            <td><span class="badge border border-secondary text-dim"><?= $l['user'] ?? 'Root' ?></span></td>
                            <td><?= $l['action'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center text-dim small py-5">&copy; 2026 Ns TECH | DPSS Siddipet Node</div>
</div>

<script>
    // Premium Cyber-Ring Design
    const ctx = document.getElementById('distChart').getContext('2d');
    
    // Central Text Plugin
    const centerTextPlugin = {
        id: 'centerText',
        afterDraw: (chart) => {
            const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
            ctx.save();
            ctx.font = 'bold 2.2rem Inter';
            ctx.fillStyle = '#f1f5f9';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('<?= $students ?>', width / 2, (height / 2) + top - 10);
            ctx.font = '10px Inter';
            ctx.fillStyle = '#94a3b8';
            ctx.letterSpacing = '2px';
            ctx.fillText('TOTAL REGISTRY', width / 2, (height / 2) + top + 20);
            ctx.restore();
        }
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: [
                    '#38bdf8', '#22c55e', '#fbbf24', '#f43f5e', '#818cf8', 
                    '#ec4899', '#06b6d4', '#f97316', '#8b5cf6', '#a855f7'
                ],
                borderColor: '#0f172a',
                borderWidth: 4,
                hoverOffset: 20,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { size: 10 }, padding: 20 }
                }
            },
            cutout: '82%',
        },
        plugins: [centerTextPlugin]
    });
</script>

</body>
</html>