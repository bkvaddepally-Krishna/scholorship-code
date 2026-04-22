<?php
/**
 * Ns TECH | DPSS SIDDIPET
 * MST SCHOLAR PORTAL - TOTAL WHITE MINIMALIST
 */
include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MST Portal | DPSS Siddipet</title>

    <?php if(function_exists('ns_branding')) ns_branding(); ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --school-green: #166534; /* Rich Forest Green */
            --text-dark: #000000;
            --text-muted: #64748b;
            --border-color: #f1f5f9;
        }
        
        body { 
            background-color: #ffffff !important; /* TOTAL WHITE */
            color: var(--text-dark); 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .main-wrapper { 
            width: 100%; 
            max-width: 1100px; 
            margin: auto; 
            padding: 40px 20px;
        }

        /* Branding Styles */
        .logo-img { 
            height: 130px; 
            width: auto;
            margin-bottom: 20px;
        }

        .school-header {
            color: var(--school-green);
            font-weight: 800;
            font-size: 3.5rem;
            letter-spacing: -1.5px;
            line-height: 1.1;
            margin-bottom: 5px;
        }

        .location-header {
            font-weight: 400;
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .tagline {
            font-size: 0.9rem;
            color: var(--text-muted);
            letter-spacing: 4px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 50px;
        }

        /* Portal Navigation */
        .portal-btn {
            background: #ffffff;
            border: 2px solid #f8fafc;
            border-radius: 24px;
            padding: 40px 20px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            display: block;
            height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .portal-btn:hover {
            border-color: var(--school-green);
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .portal-btn i {
            font-size: 3rem;
            color: var(--text-dark);
            margin-bottom: 20px;
            display: block;
        }

        .portal-btn h4 {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .portal-btn p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .action-label {
            display: inline-block;
            padding: 8px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-student { background-color: var(--school-green); color: #fff; }
        .btn-admin { background-color: #000; color: #fff; }

        footer {
            margin-top: 80px;
            color: var(--text-muted);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="main-wrapper text-center">
    <img src="Upload/logo.JPEG" alt="DPSS Logo" class="logo-img">

    <h1 class="school-header">Delhi Public Secondary School</h1>
    <h2 class="location-header text-uppercase">Siddipet</h2>
    <p class="tagline">A Unit of Delhi Public International Organization</p>

    <div class="row g-4 mt-4 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <a href="/public/hallticket.php" class="portal-btn">
                <i class="bi bi-person-check"></i>
                <h4>Student Panel</h4>
                <p>Access Hall Tickets, Exam Schedules, and Result Cards.</p>
                <span class="action-label btn-student">Login</span>
            </a>
        </div>

        <div class="col-md-5 col-lg-4">
            <a href="/admin/login.php" class="portal-btn">
                <i class="bi bi-cpu"></i>
                <h4>Admin Panel</h4>
                <p>Manage candidate data, marks entry, and system sync.</p>
                <span class="action-label btn-admin">Secure Access</span>
            </a>
        </div>
    </div>

    <footer>
        <p class="mb-0 text-uppercase" style="letter-spacing: 2px;">
            Merit Scholarship Test &bull; 2026 &bull; System Powered by <strong>DPSS</strong>
        </p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>