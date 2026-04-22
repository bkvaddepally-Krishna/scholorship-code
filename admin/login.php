<?php
session_start();
include '../config/db.php';

$error = "";

if($_POST){
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = md5($_POST['password']); 

    $q = $conn->query("SELECT * FROM admins WHERE username='$u' AND password='$p'");

    if($q->num_rows == 1){
        $_SESSION['admin'] = 1;
        $conn->query("INSERT INTO logs (action) VALUES ('SYSTEM_LOGIN: Admin [$u] accessed the core.')");
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        $error = "<div class='alert alert-danger border-danger bg-white text-danger p-2 text-center' style='font-size:0.85rem; margin-bottom:20px;'><i class='bi bi-shield-x'></i> ACCESS_DENIED</div>";
    }
}
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
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* FLOATING TEXT (P-GREEN) */
        .text-layer {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .floating-word {
            position: absolute;
            color: rgba(20, 83, 45, 0.15); 
            font-weight: 900;
            text-transform: uppercase;
            animation: floatUp var(--duration) infinite linear;
            font-size: var(--size);
            left: var(--left);
            bottom: -100px;
        }

        @keyframes floatUp {
            0% { transform: translateY(0); opacity: 0; }
            10% { opacity: 1; }
            100% { transform: translateY(-120vh); opacity: 0; }
        }

        /* MAIN CONTAINER */
        .main-wrapper {
            display: flex;
            z-index: 10;
            max-width: 950px;
            width: 100%;
            gap: 20px; /* Space between the twin cards */
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* TWIN CARD DESIGN */
        .glass-card {
            background: #fff;
            flex: 1;
            padding: 45px;
            border-radius: 15px;
            border: 1px solid var(--border-color);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top P-Green Accent Bar (Same on both) */
        .glass-card::before {
            content: ''; 
            position: absolute; 
            top: 0; left: 0; right: 0; 
            height: 5px; 
            background: var(--p-green);
        }

        /* SCROLLING NOTICE AREA */
        .notice-scroll {
            overflow-y: auto;
            max-height: 350px;
            padding-right: 10px;
            margin-top: 15px;
        }

        .notice-scroll::-webkit-scrollbar { width: 3px; }
        .notice-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

        .notice-item {
            padding: 12px;
            border-bottom: 1px solid #f9f9f9;
            font-size: 0.9rem;
            color: #444;
            transition: 0.2s;
        }
        .notice-item:hover { background: #fcfcfc; padding-left: 15px; color: var(--p-green); }

        /* INPUTS & BUTTONS */
        .form-control { border: 1px solid #ddd; padding: 12px; border-radius: 8px; }
        .form-control:focus { border-color: var(--p-green); box-shadow: none; }

        .btn-matrix { 
            background: var(--p-green); color: #fff; font-weight: bold; width: 100%; padding: 14px;
            border: none; border-radius: 8px; transition: 0.3s;
        }
        .btn-matrix:hover { background: #000; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .pulse {
            height: 10px; width: 10px; background: #22c55e; border-radius: 50%;
            display: inline-block; margin-right: 8px;
            box-shadow: 0 0 8px #22c55e;
        }

        /* RESPONSIVE STACKING */
        @media (max-width: 850px) {
            .main-wrapper { flex-direction: column; align-items: center; }
            .glass-card { width: 100%; max-width: 450px; padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="text-layer">
        <div class="floating-word" style="--left:5%; --duration:15s; --size:1.2rem;">MERIT</div>
        <div class="floating-word" style="--left:25%; --duration:12s; --size:1.4rem;">DPSS</div>
        <div class="floating-word" style="--left:50%; --duration:18s; --size:1rem;">SCHOLAR</div>
        <div class="floating-word" style="--left:75%; --duration:10s; --size:1.2rem;">2026-27</div>
    </div>

    <div class="main-wrapper">
        
        <div class="glass-card">
            <div class="text-center mb-4">
                <img src="logo.png" style="max-height: 70px;" alt="Logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2602/2602414.png'">
                <h4 class="fw-bold mt-3" style="color: var(--p-green); letter-spacing: 1px;">DPSS_AUTH</h4>
                <p class="text-muted small">Siddipet Branch Admin Portal</p>
            </div>

            <?= $error ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Security Key" required>
                </div>
                <button type="submit" class="btn btn-matrix">INITIALIZE SESSION</button>
            </form>
            <div class="text-center mt-4 small text-muted opacity-50">System v3.0 // Secure</div>
        </div>

        <div class="glass-card">
            <div class="mb-3 d-flex align-items-center">
                <span class="pulse"></span>
                <h5 class="m-0 fw-bold" style="color: var(--p-green);">LIVE UPDATES</h5>
            </div>
            
            <p class="text-muted small border-bottom pb-2">Broadcasts for the 2026-27 Academic Cycle</p>

            <div class="notice-scroll">
                <div class="notice-item">
                    <i class="bi bi- megaphone text-success me-2"></i>
                    <b>Merit Scholarship Test:</b> Registration open for Class I-IX.
                </div>
                <div class="notice-item">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    <b>Exam Schedule:</b> Final dates for Siddipet Zone set for April 26.
                </div>
                <div class="notice-item">
                    <i class="bi bi-person-plus text-info me-2"></i>
                    <b>Teacher Recruitment:</b> New applications are under screening.
                </div>
                <div class="notice-item">
                    <i class="bi bi-shield-lock text-danger me-2"></i>
                    <b>Security Protocol:</b> Password rotation mandatory this week.
                </div>
                <div class="notice-item">
                    <i class="bi bi-cloud-upload text-warning me-2"></i>
                    <b>Database Sync:</b> Bulk student upload module updated.
                </div>
                <div class="notice-item">
                    <i class="bi bi-info-circle text-secondary me-2"></i>
                    <b>Technical Support:</b> Reach Ns TECH for portal issues.
                </div>
            </div>

            <div class="mt-auto pt-3 text-center small text-muted border-top">
                © 2026 Delhi Public Secondary School
            </div>
        </div>

    </div>

</body>
</html>