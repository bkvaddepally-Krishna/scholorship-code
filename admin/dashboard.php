<?php
/**
 * Ns TECH | DPSS SIDDIPET
 * CORE CONTROL DASHBOARD v4.2 - "Full Navigation Enterprise Edition"
 * Integrated Admin Logic & Modern UI Refinement
 */

session_start();
include '../config/db.php'; 

// --- SECURITY: SESSION CHECK ---
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// --- LOGIC: ADD NEW ADMIN (MODAL PROCESSING) ---
$msg = "";
if (isset($_POST['add_admin'])) {
    $new_u = mysqli_real_escape_string($conn, $_POST['new_user']);
    $new_p = md5($_POST['new_pass']); // Matching your DB MD5 format

    $check = $conn->query("SELECT * FROM admins WHERE username='$new_u'");
    if ($check->num_rows > 0) {
        $msg = "alert-danger'>Username already exists in the system.";
    } else {
        if($conn->query("INSERT INTO admins (username, password) VALUES ('$new_u', '$new_p')")) {
            $conn->query("INSERT INTO logs (action) VALUES ('ADMIN_CREATED: New admin [$new_u] added to core.')");
            $msg = "alert-success'>Admin [$new_u] created successfully.";
        }
    }
}

// --- FETCH SYSTEM METRICS ---
$students    = $conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'] ?? 0;
$classes     = $conn->query("SELECT COUNT(*) c FROM classes")->fetch_assoc()['c'] ?? 0;
$subjects    = $conn->query("SELECT COUNT(*) c FROM subjects")->fetch_assoc()['c'] ?? 0;
$logs_count  = $conn->query("SELECT COUNT(*) c FROM logs")->fetch_assoc()['c'] ?? 0;

// --- FETCH REAL DATA FOR ANALYTICS ---
$real_logs = $conn->query("SELECT * FROM logs ORDER BY id DESC LIMIT 8");

// FETCH CLASSES for Distribution Chart
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
    <title>Admin Panel | DPSS Siddipet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --school-green: #15803d; 
            --green-light: #dcfce7;  
            --bg-main: #f8fafc;      
            --bg-card: #ffffff;      
            --text-dark: #0f172a;    
            --text-muted: #64748b;   
            --border-color: #e2e8f0; 
            --accent-primary: #38bdf8;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            overflow-x: hidden;
            margin: 0;
        }

        /* --- SIDEBAR DESIGN --- */
        .sidebar {
            position: fixed; width: 280px; height: 100vh;
            background: var(--bg-card); 
            border-right: 1px solid var(--border-color); 
            z-index: 1000; overflow-y: auto;
            padding: 20px 15px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background-color: var(--border-color); border-radius: 10px; }

        .sidebar-logo { height: 75px; margin-bottom: 10px; border-radius: 50%; border: 2px solid var(--border-color); }
        .sidebar-brand-name { color: var(--school-green); font-weight: 900; font-size: 1.1rem; text-transform: uppercase; letter-spacing: -0.5px; }
        
        .sidebar a {
            display: flex; align-items: center; padding: 10px 15px;
            color: var(--text-muted); text-decoration: none;
            border-radius: 12px; margin-bottom: 2px; transition: all 0.2s ease;
            font-size: 0.85rem; font-weight: 600;
        }

        .sidebar a i { margin-right: 12px; font-size: 1.1rem; }
        .sidebar a:hover { background: var(--bg-main); color: var(--text-dark); transform: translateX(4px); }
        .sidebar a.active { background: var(--green-light); color: var(--school-green); font-weight: 700; }
        .sidebar a.active i { color: var(--school-green); }

        .nav-section-title {
            color: #94a3b8; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px;
            margin: 25px 0 10px 15px;
        }

        /* --- MAIN DASHBOARD AREA --- */
        .main { margin-left: 280px; padding: 35px 40px; }

        .school-title-header { 
            color: var(--school-green); font-weight: 900; 
            text-transform: uppercase; letter-spacing: -1px; margin-bottom: 0; font-size: 2.2rem;
        }

        .card-box {
            background: var(--bg-card); 
            padding: 25px; border-radius: 20px; 
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-box:hover { 
            border-color: var(--school-green); 
            box-shadow: 0 10px 25px -5px rgba(21, 128, 61, 0.1); 
            transform: translateY(-4px); 
        }

        .section-title {
            color: var(--text-dark); font-size: 1.1rem; font-weight: 800;
            margin: 0 0 20px 0; display: flex; align-items: center;
        }
        .section-title i { color: var(--school-green); margin-right: 10px; font-size: 1.4rem; }

        /* --- BUTTONS & ACTIONS --- */
        .btn-console {
            background: var(--bg-card); border: 1px solid var(--border-color);
            color: var(--text-dark); padding: 20px 15px; border-radius: 14px;
            transition: 0.3s; text-decoration: none; display: block; text-align: center;
            font-weight: 600; font-size: 0.85rem;
        }

        .btn-console i { color: var(--school-green); transition: 0.3s; }
        .btn-console:hover {
            border-color: var(--school-green); background: var(--school-green); color: #fff;
        }
        .btn-console:hover i { color: #fff; transform: scale(1.1); }

        .pulse-dot {
            height: 10px; width: 10px; background-color: var(--school-green);
            border-radius: 50%; display: inline-block; margin-right: 6px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(21, 128, 61, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(21, 128, 61, 0); } 100% { box-shadow: 0 0 0 0 rgba(21, 128, 61, 0); } }

        .table-custom th { background: var(--bg-main); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid var(--border-color); }
        .table-custom td { border-bottom: 1px solid var(--border-color); vertical-align: middle; color: var(--text-dark); font-size: 0.85rem; padding: 12px; }

        @media(max-width: 992px) {
            .sidebar { position: relative; width: 100%; height: auto; border-right: none; border-bottom: 1px solid var(--border-color); }
            .main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4 mt-2">
        <img src="../Upload/logo.JPEG" class="sidebar-logo" alt="DPSS Logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2602/2602414.png'">
        <div class="sidebar-brand-name">DPSS SIDDIPET</div>
        <div class="text-muted small" style="font-size: 0.65rem; letter-spacing: 1px;">MANAGEMENT PORTAL</div>
    </div>
    
    <a href="dashboard.php" class="active"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    
    <div class="nav-section-title">Registration</div>
    <a href="students/add.php"><i class="bi bi-person-plus"></i> New Student</a>
    <a href="students/bulk_upload.php"><i class="bi bi-file-earmark-arrow-up"></i> Bulk Upload</a>
    <a href="students/view.php"><i class="bi bi-person-badge"></i> View Students</a>
    
    <div class="nav-section-title">Academic Control</div>
    <a href="classes/add.php"><i class="bi bi-building-add"></i> Manage Classes</a>
    <a href="classes/list_ids.php"><i class="bi bi-hash"></i> Class ID Master</a>
    <a href="subjects/add.php"><i class="bi bi-book"></i> Subjects</a>
    <a href="classes/assign_subjects.php"><i class="bi bi-link-45deg"></i> Assign Subjects</a>
    
    <div class="nav-section-title">Attendance</div>
    <a href="attendance/mark_attendance.php"><i class="bi bi-calendar-check text-info"></i> Mark Attendance</a>
    <a href="attendance/daily_report.php"><i class="bi bi-file-earmark-text text-warning"></i> Daily Report</a>
    <a href="attendance/report.php"><i class="bi bi-graph-up-arrow text-success"></i> Monthly Analytics</a>
    
    <div class="nav-section-title">Exams & Results</div>
    <a href="exams/create.php"><i class="bi bi-pencil-square"></i> Create Exams</a>
    <a href="marks/class_marks.php"><i class="bi bi-file-earmark-spreadsheet"></i> Marks Entry</a>
    <a href="marks/class_full_marks.php"><i class="bi bi-file-earmark-spreadsheet"></i> Overall Marks Entry</a>
    <a href="marks/results_edit.php"><i class="bi bi-pencil-square"></i> Edit Results</a>
    <a href="notifications/send_results.php"><i class="bi bi-whatsapp"></i> Dispatch Results</a>
    <a href="add_notice.php"><i class="bi bi-megaphone"></i> Notice Board</a>

    <div class="nav-section-title">System Tools</div>
    <a href="../public/hallticket.php" target="_blank"><i class="bi bi-printer"></i> Hall Ticket</a>
    <a href="../public/result.php" target="_blank"><i class="bi bi-mortarboard"></i> Results (Public)</a>
    <a href="logs/"><i class="bi bi-journal-text"></i> System Logs</a>
    
    <div class="mt-4 pt-3 border-top">
        <button class="btn btn-sm btn-dark w-100 mb-2 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="bi bi-shield-lock me-2"></i> Add Admin
        </button>
        <a href="logout.php" class="text-danger fw-bold d-block text-center py-2"><i class="bi bi-power"></i> Secure Logout</a>
    </div>
</div>

<div class="main">
    
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-4" style="border-color: var(--border-color) !important;">
        <div>
            <h1 class="school-title-header">Delhi Public Secondary School</h1>
            <div class="text-muted mt-2 fw-medium">
                <span class="pulse-dot"></span> Node: Siddipet_Central_v4.2
            </div>
        </div>
        <div class="text-end d-none d-md-block">
            <div class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill mb-2 fw-bold">
                NETWORK UPTIME: <?= $uptime ?>
            </div>
            <div class="text-muted fw-bold small text-uppercase"><?= date('D, d M Y | H:i') ?></div>
        </div>
    </div>

    <?php if($msg != ""): ?>
        <div class="alert <?= $msg ?> alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> System Message: <?= substr($msg, 15) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h4 class="section-title"><i class="bi bi-lightning-charge-fill"></i> Command Shortcuts</h4>
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-2"><a href="students/view.php" class="btn-console"><i class="bi bi-search d-block mb-2 fs-3"></i> SEARCH</a></div>
        <div class="col-6 col-md-2"><a href="notifications/send_results.php" class="btn-console"><i class="bi bi-chat-dots d-block mb-2 fs-3"></i> RESULTS</a></div>
        <div class="col-6 col-md-2"><a href="attendance/mark_attendance.php" class="btn-console"><i class="bi bi-calendar-check d-block mb-2 fs-3"></i> ATTEND</a></div>
        <div class="col-6 col-md-2"><a href="add_notice.php" class="btn-console"><i class="bi bi-plus-circle d-block mb-2 fs-3"></i> NOTICE</a></div>
        <div class="col-6 col-md-2"><a href="logs/" class="btn-console"><i class="bi bi-shield-lock d-block mb-2 fs-3"></i> SECURITY</a></div>
        <div class="col-6 col-md-2"><a href="settings/" class="btn-console"><i class="bi bi-sliders d-block mb-2 fs-3"></i> CONFIG</a></div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card-box border-start border-4 border-success">
                <div class="text-muted text-uppercase small fw-bold mb-2">Students</div>
                <h2 class="mb-0 fw-bold"><?= number_format($students) ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-start border-4 border-primary">
                <div class="text-muted text-uppercase small fw-bold mb-2">Active Classes</div>
                <h2 class="mb-0 fw-bold"><?= $classes ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-start border-4 border-warning">
                <div class="text-muted text-uppercase small fw-bold mb-2">Total Subjects</div>
                <h2 class="mb-0 fw-bold"><?= $subjects ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-start border-4 border-danger">
                <div class="text-muted text-uppercase small fw-bold mb-2">Audit Entries</div>
                <h2 class="mb-0 fw-bold"><?= $logs_count ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-box">
                <h4 class="section-title"><i class="bi bi-pie-chart-fill"></i> Registry Distribution</h4>
                <div style="height: 350px; position: relative;">
                    <canvas id="distChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-box">
                <h4 class="section-title"><i class="bi bi-activity"></i> Live Audit Trail</h4>
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>TIME</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($l = $real_logs->fetch_assoc()): ?>
                            <tr>
                                <td class="text-muted fw-medium">
                                    <i class="bi bi-clock me-1 text-success"></i> 
                                    <?= isset($l['created_at']) ? date('H:i', strtotime($l['created_at'])) : '00:00' ?>
                                </td>
                                <td class="fw-medium"><?= htmlspecialchars($l['action']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($real_logs->num_rows == 0): ?>
                            <tr><td colspan="2" class="text-center text-muted py-4">No recent activity.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-muted small py-5 mt-4 border-top" style="border-color: var(--border-color) !important;">
        <strong>Delhi Public Secondary School</strong> &bull; Merit Scholarship Test System<br>
        Developed & Maintained by <span class="fw-bold text-dark">Ns TECH</span> &copy; 2026
    </div>

</div>

<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-lock me-2 text-warning"></i> New System Access</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">Username</label>
                        <input type="text" name="new_user" class="form-control border-0 py-3 shadow-sm" placeholder="Enter username" required>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold text-muted mb-2">Security Key</label>
                        <input type="password" name="new_pass" class="form-control border-0 py-3 shadow-sm" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 pt-0 p-4">
                    <button type="button" class="btn btn-white rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_admin" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Authorize User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('distChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: ['#15803d', '#3b82f6', '#f59e0b', '#10b981', '#6366f1', '#ef4444', '#8b5cf6'],
                borderColor: '#ffffff',
                borderWidth: 3,
                cutout: '70%',
                borderRadius: 4,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'right', 
                    labels: { 
                        color: '#475569', 
                        font: { size: 12, family: "'Inter', sans-serif", weight: '500' },
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    } 
                }
            },
            layout: { padding: 20 }
        }
    });
</script>

</body>
</html>