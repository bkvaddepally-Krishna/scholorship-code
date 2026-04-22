<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/db.php';

$error = "";

// 1. LOGIN LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = md5($_POST['password']); 

    $q = $conn->query("SELECT * FROM admins WHERE username='$u' AND password='$p'");

    if ($q->num_rows == 1) {
        $_SESSION['admin'] = 1;
        $conn->query("INSERT INTO logs (action) VALUES ('SYSTEM_LOGIN: Admin [$u] accessed the core.')");
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        $error = "<div class='alert alert-danger border-danger bg-white text-danger p-2 text-center' style='font-size:0.85rem; margin-bottom:20px;'><i class='bi bi-shield-x'></i> ACCESS_DENIED</div>";
    }
}

// 2. FETCH SYSTEM LOGS (Using your specific 'logs' table)
$log_query = "SELECT * FROM logs ORDER BY id DESC LIMIT 15";
$updates = $conn->query($log_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login | DPSS CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --p-green: #14532d;
            --accent-gold: #fbbf24;
            --pure-white: #ffffff;
            --border-color: #eee;
        }

        body { 
            background: var(--pure-white); 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }

        /* BACKGROUND DECORATION */
        .text-layer { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; }
        .floating-word {
            position: absolute; color: rgba(20, 83, 45, 0.05); font-weight: 900;
            text-transform: uppercase; animation: floatUp var(--duration) infinite linear;
            font-size: var(--size); left: var(--left); bottom: -100px;
        }
        @keyframes floatUp { 0% { transform: translateY(0); opacity: 0; } 10% { opacity: 1; } 100% { transform: translateY(-120vh); opacity: 0; } }

        /* LAYOUT */
        .main-wrapper { display: flex; z-index: 10; max-width: 1000px; width: 100%; gap: 25px; animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .glass-card {
            background: #fff; flex: 1; padding: 40px; border-radius: 20px;
            border: 1px solid var(--border-color); box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            position: relative; overflow: hidden; display: flex; flex-direction: column;
        }

        .glass-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: var(--p-green); }

        /* LOGS STYLING */
        .notice-scroll { overflow-y: auto; max-height: 380px; padding-right: 8px; margin-top: 10px; }
        .notice-scroll::-webkit-scrollbar { width: 4px; }
        .notice-scroll::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }

        .notice-item {
            padding: 14px; border-bottom: 1px solid #f8f8f8;
            font-size: 0.85rem; color: #444; transition: 0.3s;
            display: flex; gap: 12px; align-items: start;
        }
        .notice-item:hover { background: #f9fafb; border-radius: 8px; padding-left: 18px; }
        .notice-item i { font-size: 1.1rem; }

        /* FORM ELEMENTS */
        .form-control { border: 1px solid #ddd; padding: 14px; border-radius: 10px; background: #fafafa; }
        .form-control:focus { border-color: var(--p-green); box-shadow: 0 0 0 4px rgba(20, 83, 45, 0.05); background: #fff; }

        .btn-matrix { 
            background: var(--p-green); color: #fff; font-weight: 700; width: 100%; padding: 15px;
            border: none; border-radius: 10px; transition: 0.4s; letter-spacing: 1.5px;
        }
        .btn-matrix:hover { background: #000; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

        .pulse {
            height: 10px; width: 10px; background: #10b981; border-radius: 50%;
            display: inline-block; margin-right: 12px;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.6);
            animation: pulse-ring 2s infinite;
        }
        @keyframes pulse-ring { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }

        @media (max-width: 850px) {
            .main-wrapper { flex-direction: column; }
            .glass-card { padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="text-layer">
        <div class="floating-word" style="--left:10%; --duration:20s; --size:1.5rem;">DPSS</div>
        <div class="floating-word" style="--left:40%; --duration:15s; --size:1.2rem;">SIDDIPET</div>
        <div class="floating-word" style="--left:70%; --duration:25s; --size:1.8rem;">CORE</div>
        <div class="floating-word" style="--left:85%; --duration:18s; --size:1rem;">2026</div>
    </div>

    <div class="main-wrapper">
        
        <div class="glass-card">
            <div class="text-center mb-4">
                <img src="../Upload/logo.JPEG" style="max-height: 85px; border-radius: 50%;" alt="DPSS Logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2602/2602414.png'">
                <h4 class="fw-bold mt-3 mb-1" style="color: var(--p-green); letter-spacing: 2px;">CORE_AUTH</h4>
                <p class="text-muted small">Administrative Access Protocol</p>
            </div>

            <?= $error ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control border-start-0" placeholder="Username" required>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="Security Key" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-matrix">INITIALIZE CORE</button>
            </form>

            <div class="mt-4 pt-3 border-top text-center">
                <span class="badge bg-light text-dark fw-normal p-2" style="font-size: 0.7rem;">
                    ENCRYPTED SHA-256 SESSION
                </span>
            </div>
        </div>

        <div class="glass-card">
            <div class="d-flex align-items-center mb-1">
                <span class="pulse"></span>
                <h5 class="m-0 fw-bold" style="color: var(--p-green);">SYSTEM_LOGS</h5>
            </div>
            <p class="text-muted small border-bottom pb-2">Tracking activities for the Siddipet Node</p>

            <div class="notice-scroll">
                <?php if ($updates && $updates->num_rows > 0): ?>
                    <?php while ($row = $updates->fetch_assoc()): ?>
                        <div class="notice-item">
                            <?php 
                                // Color coding based on your SQL actions
                                $icon = "bi-dot"; $color = "text-secondary";
                                if (strpos($row['action'], 'LOGIN') !== false) { $icon = "bi-key-fill"; $color = "text-success"; }
                                if (strpos($row['action'], 'MODIFIED') !== false) { $icon = "bi-pencil-fill"; $color = "text-warning"; }
                                if (strpos($row['action'], 'DELETED') !== false) { $icon = "bi-exclamation-triangle-fill"; $color = "text-danger"; }
                                if (strpos($row['action'], 'CRITICAL') !== false) { $icon = "bi-shield-fire"; $color = "text-danger"; }
                            ?>
                            <i class="bi <?= $icon ?> <?= $color ?>"></i>
                            <div>
                                <span class="d-block fw-bold" style="font-size: 0.65rem; color: #aaa;">
                                    <?= date('M d, Y | H:i', strtotime($row['created_at'])) ?>
                                </span>
                                <span class="text-dark"><?= htmlspecialchars($row['action']) ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cloud-slash fs-1 text-light"></i>
                        <p class="text-muted small mt-2">No logs found in registry.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-auto pt-3 text-center small text-muted border-top" style="font-size: 0.7rem;">
                Managed by <b>Ns TECH</b> &copy; 2026
            </div>
        </div>

    </div>

</body>
</html>