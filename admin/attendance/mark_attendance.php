<?php
/**
 * ATTENDANCE TERMINAL v3.5 - Corrected Variable Scoping
 */
include '../../config/auth.php';
include '../../config/db.php';

$class_id = $_GET['class_id'] ?? null;
$date = $_GET['date'] ?? date('Y-m-d');

// Fetch classes for dropdown
$classes_query = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name ASC");

$students = [];
if ($class_id) {
    $sql = "SELECT s.id, s.name, a.status 
            FROM students s 
            LEFT JOIN attendance a ON s.id = a.student_id AND a.date = ?
            WHERE s.class_id = ? 
            ORDER BY s.id ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $date, $class_id);
    $stmt->execute();
    $students = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Terminal | MST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0f172a; --card: rgba(30, 41, 59, 0.7); --accent: #38bdf8; }
        body { background: var(--bg); color: #f1f5f9; font-family: 'Inter', sans-serif; padding: 20px; }
        .glass { background: var(--card); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 20px; }
        .form-control, .form-select { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
        .btn-check:checked + .btn-outline-success { background: #22c55e !important; color: #fff; }
        .btn-check:checked + .btn-outline-warning { background: #fbbf24 !important; color: #000; }
        .btn-check:checked + .btn-outline-danger { background: #f43f5e !important; color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <a href="../index.php" class="btn btn-sm btn-outline-secondary">← Dashboard</a>
        <h4 class="fw-bold text-uppercase" style="color: var(--accent);">Attendance Terminal</h4>
        <span class="badge bg-primary px-3 py-2"><?= $date ?></span>
    </div>

    <div class="glass mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Class --</option>
                    <?php while($c = $classes_query->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= ($class_id == $c['id']) ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="date" name="date" class="form-control" value="<?= $date ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <?php if ($class_id && $students): ?>
    <form action="save_attendance.php" method="POST">
        <input type="hidden" name="class_id" value="<?= $class_id ?>">
        <input type="hidden" name="date" value="<?= $date ?>">
        
        <div class="glass p-0 overflow-hidden">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr class="text-muted small">
                        <th class="ps-4">STUDENT ID</th>
                        <th>NAME</th>
                        <th class="text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($s = $students->fetch_assoc()): 
                        // We define $current_status HERE, inside the loop
                        $current_status = $s['status'] ?? 'Present'; 
                    ?>
                    <tr class="align-middle">
                        <td class="ps-4 text-info">#<?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td class="text-center py-3">
                            <div class="btn-group btn-group-sm">
                                <input type="radio" class="btn-check" name="status[<?= $s['id'] ?>]" id="p<?= $s['id'] ?>" value="Present" <?= ($current_status == 'Present') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-success px-3" for="p<?= $s['id'] ?>">P</label>

                                <input type="radio" class="btn-check" name="status[<?= $s['id'] ?>]" id="l<?= $s['id'] ?>" value="Late" <?= ($current_status == 'Late') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-warning px-3" for="l<?= $s['id'] ?>">L</label>

                                <input type="radio" class="btn-check" name="status[<?= $s['id'] ?>]" id="a<?= $s['id'] ?>" value="Absent" <?= ($current_status == 'Absent') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-danger px-3" for="a<?= $s['id'] ?>">A</label>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="p-3 bg-black bg-opacity-25 text-end">
                <button type="submit" class="btn btn-info px-5 fw-bold">SAVE ATTENDANCE</button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>
</body>
</html>