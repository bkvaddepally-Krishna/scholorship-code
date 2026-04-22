<?php
/**
 * OVERALL MARKS TERMINAL v3.2 - "Total Marks Mode"
 * Location: C:\xampp\htdocs\school-erp-pro\admin\marks\class_full_marks.php
 */

include '../../config/db.php';

/* ================= DATABASE STRUCTURE AUTO-FIX ================= */
$conn->query("ALTER TABLE students ADD COLUMN IF NOT EXISTS last_total DECIMAL(10,2) DEFAULT 0.00");
$conn->query("ALTER TABLE students ADD COLUMN IF NOT EXISTS last_percentage DECIMAL(10,2) DEFAULT 0.00");

/* ================= SETTINGS & FILTERS ================= */
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$exam_id  = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;

$classes = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name ASC");
$exams   = $conn->query("SELECT id, name FROM exams ORDER BY id DESC");

/* ================= DATA LOAD ================= */
$students_data = [];
if($class_id && $exam_id){
    $sql = "SELECT id, name, last_total, last_percentage 
            FROM students 
            WHERE class_id = '$class_id' 
            ORDER BY name ASC";
    $students_data = $conn->query($sql);
}

/* ================= SAVE OVERALL MARKS ================= */
if(isset($_POST['save_overall'])){
    foreach($_POST['marks'] as $student_id => $data){
        $obtained  = (float)$data['obtained'];
        // Explicitly set to 100 for calculation
        $max_marks = 100; 
        
        // Calculate percentage (Obtained / 100 * 100 is just the obtained value)
        $percentage = ($obtained / $max_marks) * 100;

        // Update student record
        $stmt = $conn->prepare("UPDATE students SET last_total = ?, last_percentage = ? WHERE id = ?");
        $stmt->bind_param("ddi", $obtained, $percentage, $student_id);
        $stmt->execute();
    }

    echo "<script>alert('Overall Marks Updated Successfully!'); window.location='class_full_marks.php?class_id=$class_id&exam_id=$exam_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overall Marks Terminal | Ns TECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-deep: #0f172a;
            --bg-card: #1e293b;
            --accent: #38bdf8;
            --success: #22c55e;
            --border: rgba(51, 65, 85, 0.5);
        }

        body {
            background: var(--bg-deep);
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }

        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .header-title {
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .form-control, .form-select {
            background: #0f172a !important;
            color: white !important;
            border: 1px solid var(--border) !important;
        }

        .table-dark th { 
            background: rgba(30, 41, 59, 0.8); 
            color: var(--accent);
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 15px;
        }

        .mark-input {
            width: 130px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .readonly-max {
            background: rgba(15, 23, 42, 0.5) !important;
            color: #94a3b8 !important;
            border: 1px dashed var(--border) !important;
            cursor: not-allowed;
        }

        .btn-save {
            background: linear-gradient(90deg, #22c55e, #16a34a);
            border: none;
            font-weight: bold;
            padding: 15px;
            transition: 0.3s;
        }

        .percentage-badge {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid rgba(56, 189, 248, 0.2);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="header-title mb-0"><i class="bi bi-trophy"></i> Marks Terminal</h2>
            <small class="text-secondary text-uppercase">Total Marks Mode (Max: 100) - Siddipet Node v3.2</small>
        </div>
        <a href="../marks/class_full_marks.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> CORE DASHBOARD
        </a>
    </div>

    <div class="glass-card p-3 mb-4">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="class_id" class="form-select" required>
                    <option value="">-- SELECT CLASS --</option>
                    <?php while($c = $classes->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= $class_id == $c['id'] ? 'selected' : '' ?>>
                            <?= strtoupper($c['class_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="exam_id" class="form-select" required>
                    <option value="">-- SELECT EXAM --</option>
                    <?php while($e = $exams->fetch_assoc()): ?>
                        <option value="<?= $e['id'] ?>" <?= $exam_id == $e['id'] ? 'selected' : '' ?>>
                            <?= strtoupper($e['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 fw-bold">LOAD STUDENT REGISTRY</button>
            </div>
        </form>
    </div>

    <?php if($class_id && $exam_id): ?>
    <form method="POST">
        <div class="glass-card overflow-hidden">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">UID</th>
                        <th>STUDENT NAME</th>
                        <th class="text-center">OBTAINED (Out of 100)</th>
                        <th class="text-center">MAXIMUM</th>
                        <th class="text-center">SAVED %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($students_data->num_rows > 0): ?>
                        <?php while($st = $students_data->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 text-secondary small">#<?= $st['id'] ?></td>
                            <td class="fw-bold"><?= strtoupper($st['name']) ?></td>
                            <td>
                                <input type="number" step="0.01" max="100"
                                       name="marks[<?= $st['id'] ?>][obtained]" 
                                       class="form-control mark-input mx-auto" 
                                       value="<?= $st['last_total'] ?>" required>
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control mark-input mx-auto readonly-max" 
                                       value="100" readonly>
                            </td>
                            <td class="text-center">
                                <span class="percentage-badge">
                                    <?= number_format($st['last_percentage'], 1) ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-secondary">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="p-4 bg-white bg-opacity-5 border-top border-secondary">
                <button name="save_overall" type="submit" class="btn btn-save btn-success w-100">
                    <i class="bi bi-cloud-upload"></i> UPLOAD & SAVE ALL RESULTS
                </button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

</body>
</html>