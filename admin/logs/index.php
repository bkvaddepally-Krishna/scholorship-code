<?php
include '../../config/auth.php';
include '../../config/db.php';
$r = $conn->query("SELECT * FROM logs ORDER BY id DESC LIMIT 200");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Live System Stream</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #06130a; color: #22c55e; font-family: 'Consolas', monospace; padding: 20px; }
        .log-row { border-bottom: 1px solid #14532d; padding: 5px 0; font-size: 0.9rem; }
        .timestamp { color: #555; margin-right: 15px; }
        .text-critical { color: #ff5f56; font-weight: bold; } /* Red for deletes */
        .text-ui { color: #facc15; } /* Yellow for clicks */
    </style>
</head>
<body>
    <h3><i class="bi bi-cpu"></i> FULL_SYSTEM_STREAM</h3>
    <div class="mt-4">
        <?php foreach($r as $row): 
            $class = "";
            if(strpos($row['action'], 'CRITICAL') !== false) $class = "text-critical";
            if(strpos($row['action'], 'UI_') !== false) $class = "text-ui";
        ?>
        <div class="log-row">
            <span class="timestamp">[<?= $row['created_at'] ?>]</span>
            <span class="<?= $class ?>">> <?= htmlspecialchars($row['action']) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>