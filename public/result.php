<?php
/**
 * ==========================================
 * DPSS SECURE MERIT PORTAL - v10.0 (CLEAN)
 * ==========================================
 * Developer: Ns TECH
 * Target: Delhi Public Secondary School
 * Layout: Centered Glassmorphism (Poster Removed)
 * Logic: Multi-student detection & Settings-based publishing
 */

// --- SECTION 1: DATABASE & SYSTEM INITIALIZATION ---
include '../config/db.php';

// Fetch global publishing settings from the admin panel
$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
$is_results_live = ($settings['result_status'] === 'published');
$is_hall_ticket_live = ($settings['hall_ticket_status'] === 'published');

$student = null;
$multiple_students = [];
$error = null;

// --- SECTION 2: SEARCH & AUTHENTICATION LOGIC ---
if(isset($_POST['check']) || isset($_GET['sid'])){
    
    // Check if we are selecting a specific ID or searching by text input
    $login_input = isset($_POST['login_id']) ? trim($_POST['login_id']) : null;
    $selected_id = isset($_GET['sid']) ? $_GET['sid'] : null;

    if($selected_id) {
        // Fetch specific student data after selection
        $stmt = $conn->prepare("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id=?");
        $stmt->bind_param("i", $selected_id);
    } else {
        // Initial lookup using MS Number or Phone
        $stmt = $conn->prepare("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.ms_no=? OR s.phone=?");
        $stmt->bind_param("ss", $login_input, $login_input);
    }
    
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 1){
        // Multiple students found (siblings/shared phone)
        while($row = $res->fetch_assoc()){ $multiple_students[] = $row; }
    } else if($res->num_rows == 1){
        // Success: Found individual record
        $student = $res->fetch_assoc();
    } else {
        // No match found
        $error = "❌ Credentials not recognized. Please verify and try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>DPSS | Merit Portal 2026-27</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* --- SECTION 3: CSS VARIABLES & GLOBAL STYLES --- */
        :root { 
            --dpss-green: #1a5928; 
            --glass-bg: rgba(255, 255, 255, 0.96);
        }

        body { 
            background: url('../upload/2929.jpg') no-repeat center center fixed; 
            background-size: cover;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Background overlay for better contrast */
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.3); z-index: -1;
        }

        /* --- SECTION 4: GLASSMORPHISM COMPONENTS --- */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
        }

        /* Dashboard Percentage Typography */
        .percentage-hero {
            font-size: 5.5rem;
            font-weight: 900;
            color: var(--dpss-green);
            line-height: 1;
            letter-spacing: -3px;
        }

        /* --- SECTION 5: RESPONSIVE MOBILE STYLES --- */
        @media (max-width: 576px) {
            .dash-container { margin-top: 10px; padding: 10px; }
            .percentage-hero { font-size: 4rem; }
            
            /* Mobile Bottom Sheet for Student Selection */
            .modal-dialog { margin: 0; position: fixed; bottom: 0; width: 100%; }
            .modal-content { border-radius: 30px 30px 0 0; padding-bottom: 30px; }
        }

        /* Interactive Action Cards */
        .action-card {
            background: #fff; border-radius: 18px; padding: 18px; 
            display: flex; align-items: center; border: 1px solid #f1f5f9;
            transition: 0.2s; text-decoration: none !important; color: inherit;
        }
        .action-card:hover { transform: translateY(-3px); border-color: var(--dpss-green); }

        /* --- SECTION 6: PRINT LAYOUT --- */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            body::before { display: none; }
            .glass-card { box-shadow: none; border: none; background: white; border-radius: 0; }
            @page { size: A4; margin: 1cm; }
        }
    </style>
</head>
<body>

<?php if (!$student): ?>
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
        <div class="glass-card p-5 text-center" style="max-width: 440px;">
            <img src="../Upload/logo.JPEG" width="80" class="mb-3 rounded-circle shadow-sm">
            <h4 class="fw-bold text-dark mb-0">DPSS SIDDIPET</h4>
            <p class="text-muted small mb-4">Merit Scholarship Portal 2026-27</p>
            
            <?php if($error): ?><div class="alert alert-danger py-2 small"><?= $error ?></div><?php endif; ?>

            <form method="POST" class="text-start">
                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-2 text-uppercase ls-1">MS Number / Phone</label>
                    <input name="login_id" class="form-control form-control-lg border-2 rounded-4 shadow-none" placeholder="Enter credentials" required>
                </div>
                <button name="check" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-lg">VIEW DASHBOARD</button>
            </form>

            <div class="mt-5 social-bar no-print border-top pt-3">
                <a href="https://www.instagram.com/dpsssiddipet/" class="text-muted mx-2 fs-5"><i class="bi bi-instagram"></i></a>
                <a href="https://www.facebook.com/p/Delhi-Public-Secondary-School-Siddipet-100091992364096/" class="text-muted mx-2 fs-5"><i class="bi bi-facebook"></i></a>
                <a href="http://www.dpsssiddipet.com/" class="text-muted mx-2 fs-5"><i class="bi bi-globe"></i></a>
            </div>
        </div>
    </div>

    <?php if(!empty($multiple_students)): ?>
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.75);">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Identify Profile</h5>
                    <a href="portal.php" class="btn-close"></a>
                </div>
                <div class="modal-body p-4">
                    <?php foreach($multiple_students as $s): ?>
                        <a href="?sid=<?= $s['id'] ?>" class="action-card mb-3 shadow-sm">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width:45px; height:45px; flex-shrink:0;">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <b class="d-block"><?= strtoupper($s['name']) ?></b>
                                <span class="small text-muted">Class: <?= $s['class_name'] ?> | MS: <?= $s['ms_no'] ?></span>
                            </div>
                            <i class="bi bi-chevron-right text-success"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    <div class="container py-4 dash-container" style="max-width: 850px;">
        
        <div class="glass-card mb-4">
            <div class="p-3 bg-white text-center border-bottom">
                <img src="../upload/header.png" class="img-fluid" style="max-height: 80px;">
            </div>
            
            <div class="px-4 py-5 text-center">
                <?php if($is_results_live): ?>
                    <div class="percentage-hero"><?= $student['last_percentage'] ?>%</div>
                    <div class="badge bg-success rounded-pill px-4 py-2 mt-2">QUALIFIED FOR MERIT</div>
                <?php else: ?>
                    <div class="py-4 px-5 border-2 border-dashed rounded-pill d-inline-block bg-white text-muted">
                        <i class="bi bi-shield-lock me-2"></i> Result Data Protected
                    </div>
                <?php endif; ?>

                <div class="row g-2 p-4 bg-white rounded-4 border shadow-sm mt-5 text-start">
                    <div class="col-6 col-sm-3 border-end">
                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">Name</small>
                        <span class="fw-bold text-dark"><?= strtoupper($student['name']) ?></span>
                    </div>
                    <div class="col-6 col-sm-3 border-end">
                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">Roll No</small>
                        <span class="fw-bold text-dark"><?= $student['id'] + 1000 ?></span>
                    </div>
                    <div class="col-6 col-sm-3 border-end">
                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">Class</small>
                        <span class="fw-bold text-dark"><?= $student['class_name'] ?></span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">MS ID</small>
                        <span class="fw-bold text-dark"><?= $student['ms_no'] ?></span>
                    </div>
                </div>

                <div class="row mt-5 pt-4">
                    <div class="col-6"><div class="mx-auto border-top border-dark pt-1 small fw-bold" style="max-width: 130px;">Exam Head</div></div>
                    <div class="col-6"><div class="mx-auto border-top border-dark pt-1 small fw-bold" style="max-width: 130px;">Principal</div></div>
                </div>
            </div>
            <div class="px-4 pb-4 bg-white"><img src="../upload/footer.png" class="img-fluid"></div>
        </div>

        <div class="no-print pb-5">
            <h6 class="fw-bold text-white mb-3"><i class="bi bi-collection-fill me-2"></i>DOWNLOADS</h6>
            <div class="row g-2">
                
                <?php if($is_results_live): ?>
                <div class="col-12 col-sm-6">
                    <a href="javascript:void(0)" onclick="window.print()" class="action-card">
                        <i class="bi bi-printer-fill fs-4 me-3 text-dark"></i>
                        <div><b>Print Result</b><span class="d-block small text-muted">Official A4 Marksheet</span></div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if($is_hall_ticket_live): ?>
                <div class="col-12 col-sm-6">
                    <a href="generate_hallticket.php?id=<?= $student['id'] ?>" class="action-card">
                        <i class="bi bi-file-earmark-person-fill fs-4 me-3 text-primary"></i>
                        <div><b>Hall Ticket</b><span class="d-block small text-muted">2026-27 Admission</span></div>
                    </a>
                </div>
                <?php endif; ?>

                <div class="col-12">
                    <a href="generate_cert.php?type=participation&id=<?= $student['id'] ?>" class="action-card">
                        <i class="bi bi-award-fill fs-4 me-3 text-info"></i>
                        <div><b>Participation Certificate</b><span class="d-block small text-muted">Official DPSS Recognition</span></div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="portal.php" class="btn btn-sm btn-light border px-4 rounded-pill fw-bold text-danger shadow-sm">
                    <i class="bi bi-power me-1"></i> LOGOUT SESSION
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>