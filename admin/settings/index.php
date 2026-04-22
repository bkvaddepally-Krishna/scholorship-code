<?php
include '../../config/db.php';

// Fetch Current Configuration
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

if(isset($_POST['save'])){
    $school = $_POST['school_name'];
    $r_status = $_POST['result_status'];
    $h_status = $_POST['hall_ticket_status'];
    
    $host = $_POST['smtp_host'] ?? 'localhost';
    $user = $_POST['smtp_user'] ?? '';
    $pass = $_POST['smtp_pass'] ?? '';
    $port = !empty($_POST['smtp_port']) ? intval($_POST['smtp_port']) : 465;
    $enc  = $_POST['smtp_encryption'] ?? 'ssl';

    $logo = $set['logo'];
    if(!empty($_FILES['logo']['name'])){
        $uploadDir = "../../uploads/";
        $fileName = "logo_".time().".".pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir.$fileName)){
            $logo = "uploads/".$fileName;
        }
    }

    $stmt = $conn->prepare("UPDATE settings SET school_name=?, logo=?, result_status=?, hall_ticket_status=?, smtp_host=?, smtp_user=?, smtp_pass=?, smtp_port=?, smtp_encryption=? WHERE id=1");
    $stmt->bind_param("sssssssis", $school, $logo, $r_status, $h_status, $host, $user, $pass, $port, $enc);
    
    if($stmt->execute()){
        echo "<script>alert('Global System Sync Successful'); window.location='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings | Ns TECH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{ background:#020617; color:#f8fafc; font-family:'Segoe UI', sans-serif; padding-bottom:50px; }
        .header{ background:linear-gradient(to right, #0f172a, #1e293b); padding:40px; border-bottom:1px solid #334155; text-align:center; }
        .container{ max-width:850px; margin:20px auto; padding:0 20px; }
        .card{ background:#1e293b; padding:25px; border-radius:16px; margin-bottom:20px; border:1px solid #334155; }
        h3{ margin:0 0 20px 0; font-size:1rem; display:flex; align-items:center; gap:10px; color:#38bdf8; text-transform:uppercase; }
        label{ display:block; margin-bottom:8px; font-size:0.75rem; color:#94a3b8; font-weight:700; }
        input, select{ width:100%; padding:12px; border-radius:8px; border:1px solid #334155; background:#0f172a; color:white; margin-bottom:15px; box-sizing:border-box; }
        .row-flex{ display:flex; gap:20px; } .row-flex > div{ flex:1; }
        button{ width:100%; padding:18px; background:#38bdf8; border:none; border-radius:12px; color:#0f172a; font-weight:bold; cursor:pointer; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0; color: #38bdf8;">SYSTEM_CORE_CONTROL</h2>
        <p style="color:#94a3b8; margin-top:5px;">Institutional Node | Ns TECH</p>
    </div>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="card">
                <h3><i class="bi bi-shield-lock"></i> SMTP Transmission Protocol</h3>
                <label>SMTP Host (Relay)</label>
                <input type="text" name="smtp_host" value="<?= $set['smtp_host'] ?>" placeholder="e.g. smtp.gmail.com">
                <div class="row-flex">
                    <div><label>User/Email</label><input type="text" name="smtp_user" value="<?= $set['smtp_user'] ?>"></div>
                    <div><label>Password (App Password)</label><input type="password" name="smtp_pass" value="<?= $set['smtp_pass'] ?>"></div>
                </div>
                <div class="row-flex">
                    <div><label>Port</label><input type="number" name="smtp_port" value="<?= $set['smtp_port'] ?>"></div>
                    <div><label>Encryption</label>
                        <select name="smtp_encryption">
                            <option value="ssl" <?= $set['smtp_encryption']=='ssl'?'selected':''?>>SSL (Port 465)</option>
                            <option value="tls" <?= $set['smtp_encryption']=='tls'?'selected':''?>>TLS (Port 587)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card">
                <h3><i class="bi bi-building"></i> Branding & Status</h3>
                <label>School Name</label>
                <input type="text" name="school_name" value="<?= $set['school_name'] ?>">
                <div class="row-flex">
                    <div><label>Logo</label><input type="file" name="logo"></div>
                    <div><label>Hall Ticket</label><select name="hall_ticket_status"><option value="draft" <?= $set['hall_ticket_status']=='draft'?'selected':''?>>🔒 Hidden</option><option value="published" <?= $set['hall_ticket_status']=='published'?'selected':''?>>🌐 Live</option></select></div>
                    <div><label>Results</label><select name="result_status"><option value="draft" <?= $set['result_status']=='draft'?'selected':''?>>🔒 Hidden</option><option value="published" <?= $set['result_status']=='published'?'selected':''?>>🌐 Live</option></select></div>
                </div>
            </div>
            <button name="save">APPLY_GLOBAL_CHANGES</button>
        </form>
    </div>
</body>
</html>