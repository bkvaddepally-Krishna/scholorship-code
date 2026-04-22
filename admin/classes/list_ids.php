<?php
include '../../config/auth.php';
include '../../config/db.php';

$res = $conn->query("SELECT id, class_name FROM classes ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Class ID Reference</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #06130a; color: #22c55e; padding: 40px; font-family: 'Consolas', monospace; }
        .id-card { background: #0f2a1a; border: 1px solid #14532d; border-radius: 10px; padding: 20px; }
        table { color: white !important; }
        .text-neon { color: #22c55e; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <div class="id-card">
            <h4 class="text-neon mb-4"><i class="bi bi-key"></i> CLASS_ID_REFERENCE</h4>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CLASS NAME</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td class="text-neon"><?= $row['id'] ?></td>
                        <td><?= $row['class_name'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <small class="text-muted">Use the IDs in the green column for your CSV upload.</small>
        </div>
    </div>
</body>
</html>