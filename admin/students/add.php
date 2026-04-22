<?php
/**
 * Ns TECH | DPSS SIDDIPET
 * STUDENT REGISTRATION NODE v4.6
 */
include '../../config/db.php';

/* ================= FETCH CLASSES ================= */
$classes = $conn->query("SELECT * FROM classes ORDER BY id ASC");

/* ================= AUTO MS_NO GENERATOR ================= */
function generate_msno($conn){
    $year = date("Y");
    $res = $conn->query("SELECT ms_no FROM students ORDER BY id DESC LIMIT 1");

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        // Extract last 4 digits and increment
        $last = (int) substr($row['ms_no'], -4);
        $next = str_pad($last + 1, 4, "0", STR_PAD_LEFT);
    } else {
        $next = "0001";
    }
    return "MST".$year.$next;
}

/* ================= INSERT STUDENT ================= */
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $ms_no = generate_msno($conn);
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $father = $_POST['father_name'] ?? '';
    $mother = $_POST['mother_name'] ?? '';
    $old_sch = $_POST['old_school'] ?? '';
    $class_id = (int) ($_POST['class_id'] ?? 0);

    /* VALIDATION */
    if($name == '' || $class_id == 0){
        echo "<script>alert('Error: Name and Class are mandatory protocols.');</script>";
    } else {
        /* SAFE INSERT - Synced with Update Logic */
        $stmt = $conn->prepare("
            INSERT INTO students (ms_no, name, email, phone, father_name, mother_name, old_school, class_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("sssssssi", $ms_no, $name, $email, $phone, $father, $mother, $old_sch, $class_id);

        if($stmt->execute()){
            echo "<script>alert('Entry Successful: $ms_no Assigned');window.location='add.php';</script>";
        } else {
            echo "<script>alert('Protocol Error: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student | Ns TECH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background:#06130a; color:white; font-family:'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .glass-form { width:550px; background:rgba(15, 42, 26, 0.9); padding:35px; border-radius:20px; border: 1px solid #14532d; box-shadow: 0 0 30px rgba(0,0,0,0.6); }
        h2 { color: #22c55e; text-align: center; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 3px; font-weight: 900; }
        
        label { font-size: 10px; color: #22c55e; text-transform: uppercase; margin-bottom: 5px; display: block; font-weight: bold; letter-spacing: 1px; }
        input, select { 
            width:100%; padding:14px; margin-bottom:18px; border:1px solid #14532d; 
            background:#0b1f13; color:white; border-radius: 8px; box-sizing: border-box; font-size: 14px;
        }
        input:focus, select:focus { outline: none; border-color: #22c55e; background: #0f2a1a; box-shadow: 0 0 10px rgba(34, 197, 94, 0.2); }
        
        .row-flex { display:flex; gap:15px; } 
        .row-flex > div { flex:1; }

        .btn-add { 
            background:#22c55e; color:#000; border:none; padding:18px; width:100%; 
            cursor:pointer; font-weight:900; border-radius: 10px; transition: 0.3s;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-add:hover { background:#fff; transform: scale(1.01); }
        .footer-text { text-align: center; font-size: 11px; color: #4ade80; margin-top: 20px; opacity: 0.6; }
    </style>
</head>
<body>

<div class="glass-form">
    <h2><i class="bi bi-person-plus-fill"></i> New_Entry</h2>

    <form method="POST">
        <label>Full Name (Required)</label>
        <input type="text" name="name" placeholder="Enter Student Name" required>

        <div class="row-flex">
            <div style="flex:1.5;">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="example@mail.com">
            </div>
            <div>
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="91XXXXXXXX">
            </div>
        </div>

        <div class="row-flex">
            <div>
                <label>Father's Name</label>
                <input type="text" name="father_name" placeholder="Father's Name">
            </div>
            <div>
                <label>Mother's Name</label>
                <input type="text" name="mother_name" placeholder="Mother's Name">
            </div>
        </div>

        <label>Previous School</label>
        <input type="text" name="old_school" placeholder="Last School Attended">

        <label>Target Class (Required)</label>
        <select name="class_id" required>
            <option value="">-- Select Class --</option>
            <?php while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>">
                    <?= strtoupper($c['class_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn-add">
            <i class="bi bi-cpu-fill me-2"></i> INITIALIZE_STUDENT_DATA
        </button>
        
        <div class="footer-text">
            NS_TECH SYSTEM NODE // SIDDIPET_DPSS_MST_26
        </div>
    </form>
</div>

</body>
</html>