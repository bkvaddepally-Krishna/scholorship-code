<?php
// 1. Core System Connections
include '../../config/db.php';

// 2. Fetch Global Node Settings (School Name, SMTP User, etc.)
$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
$classes  = $conn->query("SELECT * FROM classes ORDER BY id ASC");
$status   = "";

/**
 * LOGIC PART 1: TEMPLATE CONFIGURATION
 * Updates the editable template in the database
 */
if(isset($_POST['update_template'])){
    $tid  = $_POST['temp_id'];
    $sub  = $_POST['subject'];
    $body = $_POST['body'];
    $btn  = $_POST['btn_text'];
    
    $stmt = $conn->prepare("UPDATE email_templates SET subject=?, body=?, button_text=? WHERE id=?");
    $stmt->bind_param("sssi", $sub, $body, $btn, $tid);
    $stmt->execute();
    $status = "✅ Protocol Templates Synced to Database.";
}

/**
 * LOGIC PART 2: THE DISPATCH ENGINE
 * Replaces the 11 key variables and sends the HTML mail
 */
if(isset($_POST['send_broadcast'])){
    $tid = $_POST['temp_id'];
    $cid = $_POST['class_id'];
    
    // Load the selected template
    $temp = $conn->query("SELECT * FROM email_templates WHERE id=$tid")->fetch_assoc();
    
    // Target specific class or global
    $sql = "SELECT * FROM students";
    if ($cid != 'ALL') { $sql .= " WHERE class_id = " . intval($cid); }
    $students = $conn->query($sql);
    
    $success_count = 0;

    while($r = $students->fetch_assoc()){
        if(empty($r['email'])) continue;

        // --- VARIABLE MAPPING ---
        $vars = [
            '{NAME}'        => strtoupper($r['name'] ?? 'Student'),
            '{MS_NO}'       => $r['ms_no'] ?? 'N/A',
            '{FATHER}'      => strtoupper($r['father_name'] ?? 'Parent'),
            '{MOTHER}'      => strtoupper($r['mother_name'] ?? 'Parent'),
            '{DOB}'         => !empty($r['dob']) ? date("d-M-Y", strtotime($r['dob'])) : 'N/A',
            '{SCHOOL_NAME}' => $settings['school_name'],
            '{OLD_SCHOOL}'  => $r['old_school'] ?? 'N/A',
            '{ADDRESS}'     => $r['address'] ?? 'Registered Address',
            '{CONTACT}'     => $r['father_contact'] ?? 'N/A',
            '{PERCENT}'     => ($r['last_percentage'] ?? '0') . "%",
            '{TOTAL}'       => $r['last_total'] ?? '0.00'
        ];

        // --- CONTENT PERSONALIZATION ---
        $final_sub  = str_replace(array_keys($vars), array_values($vars), $temp['subject']);
        $final_body = str_replace(array_keys($vars), array_values($vars), $temp['body']);

        // --- HTML EMAIL WRAPPER ---
        $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: {$settings['school_name']} <{$settings['smtp_user']}>\r\n";
        
        $email_html = "
        <div style='background:#0f172a; padding:40px 10px; font-family:sans-serif;'>
            <div style='max-width:600px; margin:auto; background:#ffffff; border-radius:16px; overflow:hidden;'>
                <div style='background:#1e293b; padding:25px; text-align:center; border-bottom:4px solid #38bdf8;'>
                    <h2 style='color:#38bdf8; margin:0;'>{$settings['school_name']}</h2>
                </div>
                <div style='padding:35px; color:#334155;'>
                    <p style='line-height:1.7; font-size:16px;'>$final_body</p>
                    <div align='center' style='margin-top:30px;'>
                        <a href='https://dpss.edu/portal' style='background:#38bdf8; color:#0f172a; padding:15px 35px; text-decoration:none; font-weight:bold; border-radius:8px;'>{$temp['button_text']}</a>
                    </div>
                </div>
                <div style='background:#f8fafc; padding:20px; text-align:center; color:#94a3b8; font-size:11px; border-top:1px solid #e2e8f0;'>
                    Candidate: {$vars['{NAME}']} | MS_ID: {$vars['{MS_NO}']} <br>
                    Address: {$vars['{ADDRESS}']}
                </div>
            </div>
        </div>";

        if(mail($r['email'], $final_sub, $email_html, $headers)){
            $success_count++;
            $conn->query("UPDATE students SET last_notified = NOW() WHERE id = {$r['id']}");
        }
    }
    $status = "🚀 TRANSMISSION COMPLETE: $success_count Emails Deployed.";
}

// Reload templates for the UI
$all_temps = $conn->query("SELECT * FROM email_templates")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Broadcast Center | Ns TECH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{ background:#020617; color:#f8fafc; font-family:'Segoe UI', sans-serif; padding:40px; }
        .container{ max-width:1150px; margin:auto; display:grid; grid-template-columns: 1fr 1fr; gap:30px; }
        .card{ background:#1e293b; padding:30px; border-radius:20px; border:1px solid #334155; }
        h3{ color:#38bdf8; margin-top:0; display:flex; align-items:center; gap:10px; }
        label{ display:block; margin-bottom:8px; font-size:11px; color:#94a3b8; font-weight:bold; text-transform:uppercase; }
        input, textarea, select{ width:100%; padding:14px; background:#0f172a; border:1px solid #334155; color:white; border-radius:12px; margin-bottom:15px; box-sizing:border-box; outline:none; }
        .btn{ width:100%; padding:18px; border:none; border-radius:12px; font-weight:800; cursor:pointer; transition:0.3s; text-transform:uppercase; }
        .btn-update{ background:#38bdf8; color:#020617; }
        .btn-send{ background:#22c55e; color:white; }
        .code-box{ background:#0f172a; padding:15px; border-radius:10px; border:1px solid #334155; margin-top:10px; font-size:11px; color:#38bdf8; line-height:1.8; }
    </style>
</head>
<body>

<h2 style="text-align:center; color:#38bdf8; margin-bottom:40px;">NODE_MASS_BROADCAST_HUB</h2>

<div class="container">
    <div class="card">
        <h3><i class="bi bi-pencil-square"></i> PROTOCOL_EDITOR</h3>
        <form method="POST">
            <label>Current Template</label>
            <select name="temp_id" onchange="loadData(this.value)">
                <?php foreach($all_temps as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['template_name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Subject Line</label>
            <input type="text" name="subject" id="subject" value="<?= $all_temps[0]['subject'] ?>">
            
            <label>Message Content</label>
            <textarea name="body" id="body" rows="7"><?= $all_temps[0]['body'] ?></textarea>
            
            <label>Action Button Text</label>
            <input type="text" name="btn_text" id="btn_text" value="<?= $all_temps[0]['button_text'] ?>">
            
            <button name="update_template" class="btn btn-update">SAVE_PROTOCOL</button>
        </form>
    </div>

    <div class="card" style="border-color:#22c55e;">
        <h3><i class="bi bi-cpu"></i> DISPATCH_HUB</h3>
        <?php if($status) echo "<p style='color:#4ade80; text-align:center; font-weight:bold;'>$status</p>"; ?>
        
        <form method="POST">
            <label>Select Target Population</label>
            <select name="class_id">
                <option value="ALL">ALL_STUDENTS (Global)</option>
                <?php while($c = $classes->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Execution Template</label>
            <select name="temp_id">
                <?php foreach($all_temps as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['template_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <div class="code-box">
                <strong>SYSTEM_PLACEHOLDERS:</strong><br>
                {NAME}, {MS_NO}, {FATHER}, {MOTHER}, {DOB}, {SCHOOL_NAME}, {OLD_SCHOOL}, {ADDRESS}, {CONTACT}, {PERCENT}, {TOTAL}
            </div>
            
            <button name="send_broadcast" class="btn btn-send" style="margin-top:20px;">
                <i class="bi bi-lightning-charge-fill"></i> EXECUTE_SYNC
            </button>
        </form>
    </div>
</div>

<script>
    const data = <?= json_encode($all_temps) ?>;
    function loadData(id) {
        const t = data.find(item => item.id == id);
        document.getElementById('subject').value = t.subject;
        document.getElementById('body').value = t.body;
        document.getElementById('btn_text').value = t.button_text;
    }
</script>

</body>
</html>