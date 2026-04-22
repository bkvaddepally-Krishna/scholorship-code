<?php
include '../../config/db.php';

/* ================= FETCH CLASSES ================= */
$classes = $conn->query("SELECT * FROM classes");

/* ================= AUTO MS_NO GENERATOR ================= */
function generate_msno($conn){
    $year = date("Y");

    $res = $conn->query("SELECT ms_no FROM students ORDER BY id DESC LIMIT 1");

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();

        $last = (int) substr($row['ms_no'], -4);
        $next = str_pad($last + 1, 4, "0", STR_PAD_LEFT);
    } else {
        $next = "0001";
    }

    return "MST".$year.$next;
}

/* ================= INSERT STUDENT ================= */
if($_POST){

$ms_no = generate_msno($conn);

$name = $_POST['name'] ?? '';
$class_id = (int) ($_POST['class_id'] ?? 0);
$phone = $_POST['phone'] ?? '';

/* VALIDATION */
if($name == '' || $class_id == 0){
    die("Name and Class are required");
}

/* SAFE INSERT */
$stmt = $conn->prepare("
INSERT INTO students (ms_no, name, class_id, phone)
VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssis", $ms_no, $name, $class_id, $phone);

$stmt->execute();

echo "<script>alert('Student Added: $ms_no');window.location='add.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Student</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
    font-family:Segoe UI;
}

.card-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.header{
    background:#0f172a;
    color:white;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}
</style>

</head>

<body>

<div class="container mt-4">

<div class="header">
<h3>👨‍🎓 Add New Student</h3>
<p class="mb-0">Merit Scholarship Test 2026–27</p>
</div>

<div class="card-box">

<form method="POST">

<!-- STUDENT NAME -->
<label>Student Name</label>
<input type="text" name="name" class="form-control" required>
<br>

<!-- CLASS DROPDOWN -->
<label>🏫 Class</label>
<select name="class_id" class="form-control" required>
<option value="">-- Select Class --</option>

<?php while($c = $classes->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>">
<?= $c['class_name'] ?>
</option>
<?php } ?>

</select>

<br>

<!-- PHONE -->
<label>📞 Phone</label>
<input type="text" name="phone" class="form-control">

<br>

<button class="btn btn-success w-100">
➕ Add Student
</button>

</form>

</div>

</div>

</body>
</html>