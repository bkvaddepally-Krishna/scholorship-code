<?php
/**
 * Ns TECH - DPSS SIDDIPET ERP DASHBOARD
 * Location: /mst/index.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sync with your folder structure: /mst/config/config.php
if (file_exists('config/db.php')) {
    include 'config/db.php';
} else {
    die("CRITICAL ERROR: Configuration node not found. Please check /config/db.php");
}

// Data Analytics Fetch
$student_count = 0;
$class_count   = 0;

if (isset($conn)) {
    $res_s = $conn->query("SELECT COUNT(id) as total FROM students");
    $student_count = ($res_s) ? $res_s->fetch_assoc()['total'] : 0;
    
    $res_c = $conn->query("SELECT COUNT(id) as total FROM classes");
    $class_count = ($res_c) ? $res_c->fetch_assoc()['total'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPSS Siddipet | Ns TECH ERP</title>

    <?php if(function_exists('ns_branding')) ns_branding(); ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root { --dpss-gold: #fbbf24; --dpss-blue: #0ea5e9; --bg: #020617; --card: #1e293b; }
        body { 
            background: radial-gradient(circle at top right, #0f172a, #020617); 
            color: #f1f5f9; 
            font-family: 'Inter', sans-serif; 
            min-height: 100vh;
        }
        .header-section {
            border-bottom: 2px solid var(--dpss-gold);
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .glass-card { 
            background: rgba(30, 41, 59, 0.6); 
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08); 
            border-radius: 20px; 
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .glass-card:hover {
            transform: translateY(-10px);
            border-color: var(--dpss-gold);
            box-shadow: 0 15px 30px rgba(0,0,0,0.5);
        }
        .school-title { font-weight: 900; letter-spacing: -1px; text-transform: uppercase; color: #fff; }
        .org-tagline { color: var(--dpss-gold); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; }
        
        .stat-icon { font-size: 2.5rem; color: var(--dpss-blue); }
        .nav-box { padding: 40px 20px; text-align: center; text-decoration: none; color: white; display: block; height: 100%; }
        .nav-box i { font-size: 3.5rem; margin-bottom: 20px; display: block; transition: 0.3s; }
        .nav-box:hover i { color: var(--dpss-gold); transform: scale(1.2); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="header-section text-center">
        <img src="Upload/logo.JPEG" alt="DPSS Logo" style="height: 100px; margin-bottom: 15px; filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.3));">
        <h1 class="school-title">Delhi Public Secondary School</h1>
        <h4 class="text-info fw-bold">SIDDIPET</h4>
        <div class="org-tagline mt-2">
            <i class="bi bi-globe"></i> A UNIT OF DELHI PUBLIC INTERNATIONAL ORGANIZATION
        </div>
    </div>

    <div class="row g-4 mb-5 justify-content-center">
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <i class="bi bi-people stat-icon"></i>
                <h2 class="fw-black mt-2 mb-0"><?= $student_count ?></h2>
                <span class="text-secondary small text-uppercase fw-bold">Students</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <i class="bi bi-door-open stat-icon"></i>
                <h2 class="fw-black mt-2 mb-0"><?= $class_count ?></h2>
                <span class="text-secondary small text-uppercase fw-bold">Classes</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="admin/marks/class_full_marks.php" class="glass-card nav-box">
                <i class="bi bi-clipboard2-pulse"></i>
                <h4 class="fw-bold">Marks Management</h4>
                <p class="text-secondary small">Update performance nodes and generate class results.</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="admin/students/index.php" class="glass-card nav-box">
                <i class="bi bi-person-badge"></i>
                <h4 class="fw-bold">Student Registry</h4>
                <p class="text-secondary small">Maintain candidate profiles and enrollment data.</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="admin/exams/hall_tickets.php" class="glass-card nav-box">
                <i class="bi bi-printer"></i>
                <h4 class="fw-bold">Hall Tickets</h4>
                <p class="text-secondary small">Generate official examination permits for students.</p>
            </a>
        </div>
    </div>

    <footer class="mt-5 pt-5 text-center">
        <p class="text-secondary small mb-1">&copy; 2026 Developed by <strong>Ns TECH</strong></p>
        <div class="badge bg-dark border border-secondary text-info">Terminal v7.5 Stable</div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>