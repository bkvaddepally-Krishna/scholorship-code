<?php
include '../../config/db.php';

$status = "";

// --- UPDATE LOGIC ---
if(isset($_POST['update_marks'])){
    $sid    = $_POST['student_id'];
    $total  = $_POST['last_total'];
    $per    = $_POST['last_percentage'];
    
    $stmt = $conn->prepare("UPDATE students SET last_total=?, last_percentage=? WHERE id=?");
    $stmt->bind_param("ddi", $total, $per, $sid);
    
    if($stmt->execute()){
        $status = "✅ RECORD_UPDATED: ID #$sid Sync Successful.";
    }
}

// --- FETCH DATA ---
$search = $_GET['search'] ?? '';
$class_filter = $_GET['class_id'] ?? 'ALL';

$sql = "SELECT s.*, c.class_name FROM students s 
        LEFT JOIN classes c ON s.class_id = c.id WHERE 1";

if(!empty($search)){
    $sql .= " AND (s.name LIKE '%$search%' OR s.ms_no LIKE '%$search%')";
}
if($class_filter != 'ALL'){
    $sql .= " AND s.class_id = " . intval($class_filter);
}

$students = $conn->query($sql);
$classes  = $conn->query("SELECT * FROM classes ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Result Manager | Ns TECH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{ background:#020617; color:#f8fafc; font-family:'Segoe UI', sans-serif; padding:40px; margin:0; }
        .container{ max-width:1200px; margin:auto; }
        .header-flex{ display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
        .filter-bar{ background:#1e293b; padding:20px; border-radius:15px; border:1px solid #334155; display:flex; gap:15px; margin-bottom:30px; }
        input, select{ background:#0f172a; border:1px solid #334155; color:white; padding:10px; border-radius:8px; outline:none; }
        .btn-search{ background:#38bdf8; color:#020617; border:none; padding:10px 25px; border-radius:8px; font-weight:bold; cursor:pointer; }
        
        table{ width:100%; border-collapse:collapse; background:#1e293b; border-radius:15px; overflow:hidden; border:1px solid #334155; }
        th{ background:#0f172a; color:#38bdf8; text-align:left; padding:15px; font-size:12px; text-transform:uppercase; }
        td{ padding:15px; border-bottom:1px solid #334155; font-size:14px; }
        tr:hover{ background:#1e293b; filter: brightness(1.2); }
        
        .input-edit{ width:80px; padding:5px; background:#020617; border:1px solid #38bdf8; color:white; border-radius:4px; text-align:center; }
        .btn-save{ background:#22c55e; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer; font-size:12px; }
        .badge{ padding:4px 8px; border-radius:4px; font-size:10px; font-weight:bold; background:#334155; color:#94a3b8; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h2 style="color:#38bdf8; margin:0;"><i class="bi bi-journal-check"></i> RESULT_DATA_ENTRY</h2>
        <span><?= $status ?></span>
    </div>

    <form class="filter-bar" method="GET">
        <input type="text" name="search" placeholder="Search Name or MS No..." value="<?= $search ?>" style="flex:2;">
        <select name="class_id" style="flex:1;">
            <option value="ALL">All Classes</option>
            <?php while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= $class_filter == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button class="btn-search">FILTER_RESULTS</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Candidate Details</th>
                <th>Class</th>
                <th>Total Marks</th>
                <th>Percentage (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($students->num_rows > 0): ?>
                <?php while($s = $students->fetch_assoc()): ?>
                <form method="POST">
                    <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                    <tr>
                        <td>
                            <div style="font-weight:bold; color:#fff;"><?= strtoupper($s['name']) ?></div>
                            <div style="font-size:11px; color:#94a3b8;">MS_NO: <?= $s['ms_no'] ?></div>
                        </td>
                        <td><span class="badge"><?= $s['class_name'] ?></span></td>
                        <td>
                            <input type="number" step="0.01" name="last_total" class="input-edit" value="<?= $s['last_total'] ?>">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="last_percentage" class="input-edit" value="<?= $s['last_percentage'] ?>">
                        </td>
                        <td>
                            <button name="update_marks" class="btn-save">
                                <i class="bi bi-save"></i> UPDATE
                            </button>
                        </td>
                    </tr>
                </form>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; color:#94a3b8; padding:40px;">No student records found matching your query.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>