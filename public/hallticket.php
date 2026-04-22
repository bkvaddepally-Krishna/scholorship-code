<?php
/**
 * DPSS HALL TICKET PORTAL v2.2
 * Cleaned: No Undefined Key Warnings
 * Security: Settings-based Global Lock
 * UI: Glassmorphism Login, Selection Screen, & A4 Optimized Print
 * Developer: Ns TECH
 */
include('../config/db.php');

/* ================= 1. FETCH GLOBAL STATUS FIRST ================= */
$settings_res = $conn->query("SELECT hall_ticket_status FROM settings WHERE id=1");
$settings = $settings_res ? $settings_res->fetch_assoc() : ['hall_ticket_status' => 'draft'];
$is_released = ($settings['hall_ticket_status'] === 'published');

$student = null;
$multiple_students = []; // Container for shared phone matches
$subjects = [];
$exam = null;
$error = null;

/* ================= 2. HANDLE SEARCH & SELECTION LOGIC ================= */
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) || isset($_GET['sid'])) {
    
    if (!$is_released) {
        $error = "⛔ Hall Tickets are not yet released by the administration.";
    } else {
        $login_id = isset($_POST['login_id']) ? trim($_POST['login_id']) : '';
        $selected_sid = isset($_GET['sid']) ? $_GET['sid'] : null;

        // If a specific student ID is provided (from selection screen)
        if ($selected_sid) {
            $stmt = $conn->prepare("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
            $stmt->bind_param("i", $selected_sid);
        } 
        // Initial search by MS Number or Phone
        else if (!empty($login_id)) {
            $stmt = $conn->prepare("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.ms_no = ? OR s.phone = ?");
            $stmt->bind_param("ss", $login_id, $login_id);
        } else {
            $error = "❌ Please enter your MS Number or Phone.";
        }

        if (isset($stmt)) {
            $stmt->execute();
            $res = $stmt->get_result();

            // Check if multiple students share the same search criteria (siblings)
            if ($res->num_rows > 1 && !$selected_sid) {
                while ($row = $res->fetch_assoc()) {
                    $multiple_students[] = $row;
                }
            } 
            // If single student found or specific ID selected
            else if ($res->num_rows == 1) {
                $student = $res->fetch_assoc();
                
                // Fetch latest Exam details
                $exam_res = $conn->query("SELECT * FROM exams ORDER BY id DESC LIMIT 1");
                $exam = $exam_res ? $exam_res->fetch_assoc() : null;
                
                // Fetch Subjects for this student's class
                $class_id = $student['class_id'];
                $sub_res = $conn->query("
                    SELECT s.subject_name
                    FROM class_subjects cs
                    JOIN subjects s ON cs.subject_id = s.id
                    WHERE cs.class_id = $class_id
                ");

                while ($row = $sub_res->fetch_assoc()) {
                    $subjects[] = $row['subject_name'];
                }
            } else if (!$selected_sid) {
                $error = "❌ No record found. Verify your credentials.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPSS | Hall Ticket 2026-27</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* ================= CSS VARIABLES ================= */
        :root {
            --primary-green: #1a5928;
            --accent-green: #2d8a41;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --dpss-green: #1a5928;
        }

        /* ================= GLOBAL & LOGIN STYLES ================= */
        body { 
            background: linear-gradient(-45deg, #f0fdf4, #ffffff, #dcfce7, #f8fafc);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
            min-height: 100vh;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .search-box { 
            max-width: 480px; 
            width: 90%;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            padding: 3rem !important;
            transition: transform 0.3s ease;
        }

        .logo-container {
            width: 100px; height: 100px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: -80px auto 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.08); padding: 15px;
        }
        .logo-container img { max-width: 100%; height: auto; object-fit: contain; }

        .school-name { letter-spacing: 1px; font-size: 1.75rem; color: var(--primary-green); }
        .form-label { font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b; }
        .input-group { border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .input-group-text { background: #f8fafc; border-color: #e2e8f0; color: var(--primary-green); padding-left: 1.25rem; }
        .form-control { border-color: #e2e8f0; padding: 0.8rem 1rem; font-size: 1rem; }
        .form-control:focus { border-color: var(--accent-green); box-shadow: none; }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
            border: none; border-radius: 1rem; padding: 1rem; font-size: 1rem; transition: all 0.3s ease;
        }
        .btn-submit:hover { filter: brightness(1.1); transform: scale(1.02); box-shadow: 0 10px 15px -3px rgba(26, 89, 40, 0.3); }

        /* Multi-Student Selection Cards */
        .selection-card {
            background: #fff; border-radius: 15px; padding: 15px; margin-bottom: 12px;
            display: flex; align-items: center; border: 1px solid #e2e8f0;
            text-decoration: none !important; color: inherit; transition: 0.2s;
        }
        .selection-card:hover { border-color: var(--primary-green); transform: translateX(5px); background: #f0fdf4; }

        /* ================= TICKET DESIGN (A4 Layout) ================= */
        .ticket {
            width: 210mm; min-height: 297mm; margin: 20px auto;
            background: white; padding: 10mm 15mm; position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1); display: flex; flex-direction: column;
            border: 1px solid #eee; box-sizing: border-box;
        }

        .header img { width: 100%; max-height: 100px; object-fit: contain; }
        .main-title { font-size: 26px; font-weight: 900; color: var(--dpss-green); text-transform: uppercase; margin-top: 5px; }
        .sub-title { 
            background: var(--dpss-green) !important; color: white !important;
            display: inline-block; padding: 5px 40px; border-radius: 4px; font-weight: bold; margin-top: 5px;
        }

        .exam-bar {
            background: #f1f8f3 !important; border-top: 2px solid var(--dpss-green); border-bottom: 2px solid var(--dpss-green);
            padding: 10px; margin: 15px 0; display: flex; justify-content: space-around; font-weight: bold;
        }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 10px; }
        .info-item { padding: 8px; border-bottom: 1px solid #eee; font-size: 14px; }
        .info-item b { color: var(--dpss-green); width: 120px; display: inline-block; }

        .center-box {
            border: 2px dashed var(--dpss-green); background: #fffdf0 !important;
            padding: 12px; text-align: center; margin: 15px 0; border-radius: 8px;
        }

        .subject-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
        .sub-tag {
            background: var(--dpss-green) !important; color: white !important;
            padding: 6px; text-align: center; border-radius: 4px; font-size: 12px; font-weight: bold;
        }

        .instructions-box { margin-top: 20px; padding: 15px; background: #fafafa !important; border: 1px solid #ddd; border-radius: 8px; }
        .instructions-list { font-size: 12px; padding-left: 18px; margin-top: 8px; columns: 2; }

        .sign-area { margin-top: auto; display: flex; justify-content: space-between; padding: 20px 40px; }
        .sign-box { text-align: center; border-top: 1px solid #000; width: 180px; padding-top: 5px; font-weight: bold; font-size: 13px; }

        .footer-img { text-align: center; margin-top: 10px; }
        .footer-img img { width: 100%; max-height: 80px; object-fit: contain; }

        @media (max-width: 576px) {
            .search-box { padding: 2rem 1.5rem !important; margin-top: 60px; }
            .logo-container { width: 85px; height: 85px; margin-top: -65px; }
            .school-name { font-size: 1.4rem; }
        }

        @media print {
            @page { size: A4; margin: 0; }
            body { background: white !important; animation: none !important; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .ticket {
                margin: 0 !important; border: none !important; box-shadow: none !important;
                width: 210mm; height: 297mm;
                -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
            }
            .container { padding: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>

<?php if (!$student): ?>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="search-box card border-0 text-center">
            
            <div class="logo-container">
                <img src="../Upload/logo.JPEG" alt="DPSS Logo">
            </div>
            
            <div class="mb-4">
                <h3 class="fw-bolder school-name">DPSS SIDDIPET</h3>
                <div class="badge rounded-pill bg-light text-success border border-success px-3 py-2 mt-1" style="font-size: 0.7rem; font-weight: 600;">
                   Merit Scholarship Test 2026-27 HALL TICKET
                </div>
            </div>
            
            <?php if (!$is_released): ?>
                <div class="py-4">
                    <div class="bg-light rounded-4 p-4 border border-warning border-opacity-25">
                        <i class="bi bi-shield-lock text-warning mb-2" style="font-size: 2.5rem;"></i>
                        <h5 class="fw-bold text-dark mt-2">Access Locked</h5>
                        <p class="text-muted small mb-0">Hall Tickets will be available immediately after the official notification.</p>
                    </div>
                </div>
            <?php elseif (!empty($multiple_students)): ?>
                <div class="text-start">
                    <p class="small fw-bold text-muted mb-3 text-uppercase"><i class="bi bi-people-fill me-1"></i> Multiple Profiles Found:</p>
                    <?php foreach ($multiple_students as $s): ?>
                        <a href="?sid=<?= $s['id'] ?>" class="selection-card">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width:40px; height:40px; flex-shrink:0;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="flex-grow-1">
                                <b class="d-block text-dark small"><?= strtoupper($s['name']) ?></b>
                                <span class="text-muted" style="font-size:11px;">Class: <?= $s['class_name'] ?> | MS: <?= $s['ms_no'] ?></span>
                            </div>
                            <i class="bi bi-chevron-right text-success small"></i>
                        </a>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="hallticket.php" class="small text-decoration-none text-danger fw-bold">← Back to Search</a>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($error): ?> 
                    <div class="alert alert-danger border-0 rounded-3 py-2 small">
                        <i class="bi bi-exclamation-circle me-1"></i> <?= $error ?>
                    </div> 
                <?php endif; ?>
                
                <form method="POST" class="text-start">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-uppercase">Identification Number</label>
                        <div class="input-group">
                            <span class="input-group-text border-2 border-end-0">
                                <i class="bi bi-qr-code-scan"></i>
                            </span>
                            <input name="login_id" 
                                   type="text"
                                   class="form-control border-2 border-start-0" 
                                   placeholder="Enter MS No or Phone" 
                                   required 
                                   autocomplete="off">
                        </div>
                        <div class="form-text text-center mt-2" style="font-size: 0.7rem;">
                            Use the mobile number registered during application.
                        </div>
                    </div>
                    
                    <button name="search" class="btn btn-submit text-white w-100 fw-bold">
                        <i class="bi bi-download me-2"></i> FETCH HALL TICKET
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="mt-5 pt-2 border-top border-light">
                <p class="text-muted small mb-3">Facing issues? Contact School Admin</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="../public/result.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" style="font-size: 0.75rem;">
                       <i class="bi bi-trophy-fill me-1"></i> Check Results
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container py-4">
        <div class="ticket">
            <div class="header text-center">
                <img src="/assets/Upload/header.png" alt="Header">
            </div>

            <div class="text-center">
                <h2 class="main-title">Merit Scholarship Test 2026-27</h2>
                <div class="sub-title">HALL TICKET</div>
            </div>

            <div class="exam-bar">
                <span><i class="bi bi-calendar-check"></i> DATE: <?= date('d-M-Y', strtotime($exam['exam_date'] ?? '2026-04-26')) ?></span>
                <span><i class="bi bi-clock"></i> 10:00 AM TO 01:00 PM</span>
            </div>

            <div class="info-grid">
                <div class="info-item"><b>Student Name:</b> <?= strtoupper($student['name']) ?></div>
                <div class="info-item"><b>MS Number:</b> <?= $student['ms_no'] ?></div>
                <div class="info-item"><b>Father's Name:</b> <?= strtoupper($student['father_name'] ?? '---') ?></div>
                <div class="info-item"><b>Phone Number:</b> <?= $student['phone'] ?></div>
                <div class="info-item"><b>Class / Grade:</b> <?= $student['class_name'] ?></div>
                <div class="info-item"><b>Exam Roll No:</b> <?= $student['id'] ?></div>
            </div>

            <div class="center-box">
                <h6 class="fw-bold mb-1" style="color:#d63384;">📍 EXAMINATION CENTER</h6>
                <h5 class="fw-bold mb-0" style="color: var(--dpss-green);">DELHI PUBLIC SECONDARY SCHOOL</h5>
                <p class="mb-0 small">Beside Collectorate Office, Siddipet, Telangana - 502103</p>
            </div>

            <div class="subject-section">
                <p class="fw-bold mb-1 small text-uppercase" style="color: var(--dpss-green);"><i class="bi bi-book"></i> Subjects Included:</p>
                <div class="subject-grid">
                    <?php if(!empty($subjects)): ?>
                        <?php foreach($subjects as $s): ?>
                            <div class="sub-tag"><?= $s ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="instructions-box">
                <p class="fw-bold mb-0 small text-uppercase"><i class="bi bi-info-circle"></i> Important Instructions:</p>
                <ul class="instructions-list">
                    <li>Reporting time: 09:15 AM.</li>
                    <li>Carry a physical copy of this ticket.</li>
                    <li>Only Blue/Black ball pens allowed.</li>
                    <li>No electronic gadgets or calculators.</li>
                    <li>Maintain silence during the exam.</li>
                    <li>Don't leave until the exam ends.</li>
                </ul>
            </div>

            <div class="sign-area">
                <div class="sign-box">Candidate's Signature</div>
                <div class="sign-box">Principal's Signature</div>
            </div>

            <div class="footer-img">
               <img src="/assets/Upload/footer.png" alt="Header">
            </div>
        </div>

        <div class="text-center mt-4 mb-5 no-print">
            <button class="btn btn-success btn-lg px-5 shadow-sm fw-bold" onclick="window.print()">
                <i class="bi bi-printer"></i> PRINT HALL TICKET
            </button>
            <a href="hallticket.php" class="btn btn-outline-secondary btn-lg ms-2 fw-bold">BACK</a>
        </div>

        <div class="social-bar no-print border-top pt-3 text-center">
            <a href="https://www.instagram.com/dpsssiddipet/" class="text-muted mx-2 fs-5"><i class="bi bi-instagram"></i></a>
            <a href="https://www.facebook.com/p/Delhi-Public-Secondary-School-Siddipet-100091992364096/" class="text-muted mx-2 fs-5"><i class="bi bi-facebook"></i></a>
            <a href="http://www.dpsssiddipet.com/" class="text-muted mx-2 fs-5"><i class="bi bi-globe"></i></a>
        </div>
    </div>
<?php endif; ?>

</body>
</html>