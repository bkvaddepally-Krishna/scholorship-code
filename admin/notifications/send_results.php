<?php
/**
 * Ns TECH | DPSS SIDDIPET 
 * PREMIUM TEMPLATE DESIGNER + DISPATCH
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
include '../../config/db.php'; 

set_time_limit(0); 

$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
$classes  = $conn->query("SELECT * FROM classes ORDER BY class_name ASC");
$status = "";

// SAVE TEMPLATE CHANGES
if(isset($_POST['save_template'])){
    $stmt = $conn->prepare("UPDATE email_templates SET subject=?, body=?, button_text=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['subject'], $_POST['body'], $_POST['button_text'], $_POST['temp_id']);
    if($stmt->execute()) $status = "✅ Template Protocol Updated.";
}

// DISPATCH ENGINE (Refined for Class-Wise)
if(isset($_POST['send_broadcast'])){
    $tid = $_POST['temp_id'];
    $cid = $_POST['class_id'];
    $temp = $conn->query("SELECT * FROM email_templates WHERE id=$tid")->fetch_assoc();
    
    $sql = ($cid == 'ALL') 
        ? "SELECT s.*, c.class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.email != ''"
        : "SELECT s.*, c.class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.class_id = ".intval($cid)." AND s.email != ''";
    
    $students = $conn->query($sql);
    
    echo "<div style='background:#020617; color:#f8fafc; padding:20px; font-family:monospace; border-radius:12px; border:1px solid #334155; margin-bottom:20px;'>";
    echo "<b style='color:#38bdf8;'>[LOG]: Dispatching to " . $students->num_rows . " Students...</b><br><hr style='border-color:#1e293b;'>";

    while($r = $students->fetch_assoc()){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $settings['smtp_user'];
            $mail->Password = $settings['smtp_pass']; 
            $mail->Port = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPOptions = array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true));

            $mail->setFrom($settings['smtp_user'], $settings['school_name']);
            $mail->addAddress($r['email'], $r['name']);
            $mail->isHTML(true);

            // Placeholder Replacement
            $vars = ['{NAME}' => $r['name'], '{MS_NO}' => $r['ms_no'], '{SCHOOL}' => $settings['school_name']];
            $subject = str_replace(array_keys($vars), array_values($vars), $temp['subject']);
            $message = str_replace(array_keys($vars), array_values($vars), $temp['body']);

            $mail->Subject = $subject;
            
            // THE PREMIUM DESIGNED BODY
            $mail->Body = "
            <div style='background-color:#f1f5f9; padding:40px 10px; font-family:Helvetica, Arial, sans-serif;'>
                <div style='max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.05);'>
                    <div style='background:#1e293b; padding:30px; text-align:center;'>
                        <h1 style='color:#38bdf8; margin:0; font-size:22px; letter-spacing:1px;'>{$settings['school_name']}</h1>
                    </div>
                    <div style='padding:40px; color:#334155;'>
                        <h2 style='color:#1e293b; margin-top:0;'>Hello, {NAME}</h2>
                        <p style='line-height:1.8; font-size:16px;'>$message</p>
                        <div style='text-align:center; margin-top:40px;'>
                            <a href='#' style='background:#38bdf8; color:#ffffff; padding:15px 35px; text-decoration:none; border-radius:8px; font-weight:bold; display:inline-block;'>{$temp['button_text']}</a>
                        </div>
                    </div>
                    <div style='background:#f8fafc; padding:20px; text-align:center; border-top:1px solid #e2e8f0;'>
                        <p style='font-size:12px; color:#94a3b8; margin:0;'>Student ID: {$r['ms_no']} | Siddipet Node</p>
                    </div>
                </div>
            </div>";

            if($mail->send()){
                echo "<span style='color:#22c55e;'>[SUCCESS]:</span> {$r['name']} ({$r['email']})<br>";
            }
        } catch (Exception $e) {
            echo "<span style='color:#ef4444;'>[FAIL]:</span> {$r['email']} - {$mail->ErrorInfo}<br>";
        }
        flush();
    }
    echo "</div>";
}

$all_temps = $conn->query("SELECT * FROM email_templates")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Elite Template Hub | Ns TECH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary: #38bdf8; --bg: #020617; --card: #1e293b; }
        body{ background: var(--bg); color: #f8fafc; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .grid{ display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; max-width: 1300px; margin: auto; }
        .card{ background: var(--card); padding: 30px; border-radius: 24px; border: 1px solid #334155; }
        h3{ color: var(--primary); margin-top: 0; display: flex; align-items: center; gap: 10px; font-size: 1.1rem; }
        label{ font-size: 11px; color: #94a3b8; font-weight: bold; text-transform: uppercase; margin-bottom: 8px; display: block; }
        input, textarea, select { width: 100%; padding: 14px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 12px; margin-bottom: 20px; outline: none; }
        .btn { border: none; padding: 18px; border-radius: 12px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-save { background: #334155; color: white; }
        .btn-send { background: #22c55e; color: white; margin-top: 10px; }
        .status-msg { background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; border: 1px solid #22c55e; }
    </style>
</head>
<body>

<h2 style="text-align:center; color:var(--primary); margin-bottom:40px; letter-spacing:2px;">NODE_COMMUNICATION_HUB</h2>

<?php if($status) echo "<div class='status-msg'>$status</div>"; ?>

<div class="grid">
    <div class="card">
        <h3><i class="bi bi-palette2"></i> DESIGN_PROTOCOL</h3>
        <form method="POST">
            <label>Template Select</label>
            <select name="temp_id" onchange="loadTemp(this.value)">
                <?php foreach($all_temps as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['template_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Subject Line</label>
            <input type="text" name="subject" id="t_subject" value="<?= $all_temps[0]['subject'] ?>">

            <label>Message Content</label>
            <textarea name="body" id="t_body" rows="10"><?= $all_temps[0]['body'] ?></textarea>

            <label>Button Action Text</label>
            <input type="text" name="button_text" id="t_btn" value="<?= $all_temps[0]['button_text'] ?>">

            <button name="save_template" class="btn btn-save">UPDATE_CHANGES</button>
        </form>
    </div>

    <div class="card" style="border-color: #22c55e;">
        <h3><i class="bi bi-send-check"></i> DISPATCH_CONTROL</h3>
        <form method="POST">
            <label>Target Population (Class)</label>
            <select name="class_id">
                <option value="ALL">All Students (Global Sync)</option>
                <?php while($c = $classes->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Active Template</label>
            <select name="temp_id">
                <?php foreach($all_temps as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['template_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <div style="background: #0f172a; padding: 20px; border-radius: 12px; font-size: 12px; line-height: 1.8; color: #38bdf8; margin-bottom: 20px;">
                <b>Variables Allowed:</b><br>
                {NAME} - Student Name<br>
                {MS_NO} - Admission Number<br>
                {SCHOOL} - School Name
            </div>

            <button name="send_broadcast" class="btn btn-send">EXECUTE_SYNC</button>
        </form>
    </div>
</div>

<script>
    const temps = <?= json_encode($all_temps) ?>;
    function loadTemp(id) {
        const t = temps.find(x => x.id == id);
        if(t){
            document.getElementById('t_subject').value = t.subject;
            document.getElementById('t_body').value = t.body;
            document.getElementById('t_btn').value = t.button_text;
        }
    }
</script>
</body>
</html>