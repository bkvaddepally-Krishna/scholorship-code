<?php
include __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

/* GET STUDENT - Including Email */
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

/* GET CLASSES FOR DROPDOWN */
$classes = $conn->query("SELECT * FROM classes ORDER BY id ASC");

/* UPDATE LOGIC */
if(isset($_POST['update'])){

    $ms_no   = $_POST['ms_no'];
    $name    = $_POST['name'];
    $email   = $_POST['email']; // New Field
    $phone   = $_POST['phone'];
    $father  = $_POST['father_name'];
    $mother  = $_POST['mother_name'];
    $old_sch = $_POST['old_school'];
    $class_id = $_POST['class_id'];

    // Added 'email=?' and an extra 's' in bind_param
    $update = $conn->prepare("
        UPDATE students 
        SET ms_no=?, name=?, email=?, phone=?, father_name=?, mother_name=?, old_school=?, class_id=?
        WHERE id=?
    ");

    $update->bind_param(
        "sssssssii", // 7 strings, 2 integers
        $ms_no,
        $name,
        $email,
        $phone,
        $father,
        $mother,
        $old_sch,
        $class_id,
        $id
    );

    if($update->execute()){
        echo "<script>alert('Student Data Synced Successfully');window.location='students.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile | Matrix Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background:#06130a; color:white; font-family:'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .glass-form { width:500px; background:rgba(15, 42, 26, 0.9); padding:30px; border-radius:15px; border: 1px solid #14532d; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
        h2 { color: #22c55e; text-align: center; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 2px; }
        
        label { font-size: 11px; color: #22c55e; text-transform: uppercase; margin-bottom: 5px; display: block; }
        input, select { 
            width:100%; padding:12px; margin-bottom:15px; border:1px solid #14532d; 
            background:#0b1f13; color:white; border-radius: 5px; box-sizing: border-box;
        }
        input:focus, select:focus { outline: none; border-color: #22c55e; background: #0f2a1a; }
        
        .btn-update { 
            background:#22c55e; color:#000; border:none; padding:15px; width:100%; 
            cursor:pointer; font-weight:bold; border-radius: 5px; transition: 0.3s;
        }
        .btn-update:hover { background:#fff; transform: scale(1.02); }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 13px; }
        .back-link:hover { color: #22c55e; }
    </style>
</head>
<body>

<div class="glass-form">
    <h2><i class="bi bi-pencil-square"></i> Update_Record</h2>

    <form method="POST">
        <label>Merit Serial (MS_NO)</label>
        <input type="text" name="ms_no" value="<?= htmlspecialchars($data['ms_no']) ?>" required>

        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" placeholder="example@mail.com">

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>">

        <div style="display:flex; gap:10px;">
            <div style="flex:1;">
                <label>Father's Name</label>
                <input type="text" name="father_name" value="<?= htmlspecialchars($data['father_name']) ?>">
            </div>
            <div style="flex:1;">
                <label>Mother's Name</label>
                <input type="text" name="mother_name" value="<?= htmlspecialchars($data['mother_name']) ?>">
            </div>
        </div>

        <label>Previous School</label>
        <input type="text" name="old_school" value="<?= htmlspecialchars($data['old_school']) ?>">

        <label>Assigned Class</label>
        <select name="class_id" required>
            <option value="">-- Select Class --</option>
            <?php while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $data['class_id']) ? 'selected' : '' ?>>
                    <?= $c['class_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" name="update" class="btn-update">APPLY_CHANGES_COMMAND</button>
        <a href="view.php" class="back-link">CANCEL_AND_RETURN</a>
    </form>
</div>

</body>
</html>