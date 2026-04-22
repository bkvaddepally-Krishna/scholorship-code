<?php
include '../../config/db.php';

// Fetch Current Settings
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

if(isset($_POST['save'])){
    $school = $_POST['school_name'];
    $r_status = $_POST['result_status'];
    $h_status = $_POST['hall_ticket_status'];
    
    // SMTP Data
    $host = $_POST['smtp_host'];
    $user = $_POST['smtp_user'];
    $pass = $_POST['smtp_pass'];
    $port = $_POST['smtp_port'];
    $enc  = $_POST['smtp_encryption'];

    $logo = $set['logo'];
    if(!empty($_FILES['logo']['name'])){
        $uploadDir = "../../uploads/";
        $fileName = "logo_".time().".".pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir.$fileName)){
            $logo = "uploads/".$fileName;
        }
    }

    $stmt = $conn->prepare("UPDATE settings SET 
        school_name=?, logo=?, result_status=?, hall_ticket_status=?, 
        smtp_host=?, smtp_user=?, smtp_pass=?, smtp_port=?, smtp_encryption=? 
        WHERE id=1");
    
    $stmt->bind_param("sssssssis", 
        $school, $logo, $r_status, $h_status, 
        $host, $user, $pass, $port, $enc
    );
    
    $stmt->execute();
    echo "<script>alert('System Config Updated'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings | DPSS Node</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{ background:#020617; color:#f8fafc; font-family:'Segoe UI', sans-serif; padding: 40px 0; }
        .container{ max-width:800px; margin:auto; }
        .card{ background:#1e293b; padding:25px; border-radius:16px; border:1px solid #334155; margin-bottom:20px; }
        h3{ color:#38bdf8; font-size:1.1rem; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        label{ display:block; color:#94a3b8; font-size:0.75rem; font-weight:bold; margin-bottom:8px; text-transform:uppercase; }
        input, select{ width:100%; padding:12px; background:#0f172a; border:1px solid #334155; color:white; border-radius:8px; margin-bottom:15px; box-sizing:border-box; }
        .row{ display:flex; gap:15px; } .row div{ flex:1; }
        button{ width:100%; padding:16px; background:#38bdf8; border:none; border-radius:12px; font-weight:bold; cursor:pointer; }
    </style>
</head>
<body>
<div class="container">
    <h2 style="text-align:center; margin-bottom:30px;">⚙️ System_Core_Settings</h2>
    <form method="POST" enctype="multipart/form-data">
        
        <div class="card">
            <h3><i class="bi bi-shield-lock"></i> SMTP Mail Protocol</h3>
            <label>Server Host</label>
            <input type="text" name="smtp_host" value="<?= $set['smtp_host'] ?>" placeholder="smtp.gmail.com">
            <div class="row">
                <div><label>Username</label><input type="text" name="smtp_user" value="<?= $set['smtp_user'] ?>"></div>
                <div><label>Password</label><input type="password" name="smtp_pass" value="<?= $set['smtp_pass'] ?>"></div>
            </div>
            <div class="row">
                <div><label>Port</label><input type="number" name="smtp_port" value="<?= $set['smtp_port'] ?>"></div>
                <div><label>Encryption</label>
                    <select name="smtp_encryption">
                        <option value="ssl" <?= $set['smtp_encryption']=='ssl'?'selected':''?>>SSL</option>
                        <option value="tls" <?= $set['smtp_encryption']=='tls'?'selected':''?>>TLS</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <h3><i class="bi bi-eye"></i> Portal Visibility</h3>
            <div class="row">
                <div>
                    <label>Hall Ticket Release</label>
                    <select name="hall_ticket_status">
                        <option value="draft" <?= $set['hall_ticket_status']=='draft'?'selected':''?>>Locked</option>
                        <option value="published" <?= $set['hall_ticket_status']=='published'?'selected':''?>>Live</option>
                    </select>
                </div>
                <div>
                    <label>Result Status</label>
                    <select name="result_status">
                        <option value="draft" <?= $set['result_status']=='draft'?'selected':''?>>Processing</option>
                        <option value="published" <?= $set['result_status']=='published'?'selected':''?>>Live</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <h3><i class="bi bi-building"></i> Institutional Branding</h3>
            <label>School Name</label>
            <input type="text" name="school_name" value="<?= $set['school_name'] ?>">
            <label>Upload New Logo</label>
            <input type="file" name="logo">
        </div>

        <button name="save">APPLY_GLOBAL_CHANGES</button>
    </form>
</div>
</body>
</html>