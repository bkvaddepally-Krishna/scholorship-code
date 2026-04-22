<?php
include '../../config/db.php';

/* ================= SAFE ERROR HANDLING ================= */
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ================= SETTINGS ================= */
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
$show_percentage = $set['show_percentage'] ?? 1;

/* ================= DROPDOWNS ================= */
$classes = $conn->query("SELECT * FROM classes");
$exams   = $conn->query("SELECT * FROM exams");

/* ================= INPUTS ================= */
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$exam_id  = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;

/* ================= DATA LOAD ================= */
$students = [];
$subjects = [];

if($class_id && $exam_id){

$students = $conn->query("
SELECT * FROM students WHERE class_id='$class_id'
");

$subjects = $conn->query("
SELECT s.* FROM subjects s
JOIN class_subjects cs ON s.id = cs.subject_id
WHERE cs.class_id='$class_id'
");

}

/* ================= SAVE MARKS ================= */
if(isset($_POST['save_marks'])){

$exam_id = (int)$_POST['exam_id'];

foreach($_POST['marks'] as $student_id => $subject_marks){

$total = 0;
$count = 0;

foreach($subject_marks as $subject_id => $mark){

$mark = (int)$mark;
$total += $mark;
$count++;

$conn->query("
INSERT INTO marks(student_id,exam_id,subject_id,marks)
VALUES('$student_id','$exam_id','$subject_id','$mark')
ON DUPLICATE KEY UPDATE marks='$mark'
");

}

/* OPTIONAL STORE RESULT */
$percentage = ($count > 0) ? ($total / ($count * 100)) * 100 : 0;

$conn->query("
UPDATE students
SET last_total='$total',
last_percentage='$percentage'
WHERE id='$student_id'
");

}

echo "<script>alert('Marks Saved Successfully');window.location='class_marks.php?class_id=$class_id&exam_id=$exam_id';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Class Marks System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#0b1220;
color:white;
font-family:Segoe UI;
}

/* HEADER */
.header{
background:#0f172a;
padding:15px;
border-radius:10px;
margin-bottom:15px;
}

/* FILTER */
.filter{
background:#111827;
padding:10px;
border-radius:10px;
}

/* TABLE WRAPPER */
.table-wrapper{
max-height:70vh;
overflow:auto;
border-radius:10px;
}

/* TABLE */
table{
min-width:900px;
background:#0f172a;
}

thead th{
position:sticky;
top:0;
background:#14532d;
color:white;
z-index:5;
}

th,td{
text-align:center;
vertical-align:middle;
}

input{
width:70px;
text-align:center;
}

/* TOTAL COLUMN */
.total{
background:#1f2937;
font-weight:bold;
}

/* MOBILE */
@media(max-width:768px){
input{width:55px;}
table{font-size:12px;}
}

</style>

</head>

<body>

<div class="container-fluid p-3">

<div class="header">
<h3>📊 Advanced Excel Marks System (ERP FIXED)</h3>
</div>

<!-- FILTER -->
<form method="GET" class="row filter">

<div class="col-md-4">
<select name="class_id" class="form-control" required>
<option value="">Select Class</option>

<?php while($c=$classes->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>" <?= ($class_id==$c['id'])?'selected':'' ?>>
<?= $c['class_name'] ?>
</option>
<?php } ?>

</select>
</div>

<div class="col-md-4">
<select name="exam_id" class="form-control" required>
<option value="">Select Exam</option>

<?php while($e=$exams->fetch_assoc()){ ?>
<option value="<?= $e['id'] ?>" <?= ($exam_id==$e['id'])?'selected':'' ?>>

<?= $e['name'] ?? 'Exam' ?>

</option>
<?php } ?>

</select>
</div>

<div class="col-md-4">
<button class="btn btn-success w-100">Load Sheet</button>
</div>

</form>

<!-- SHEET -->
<?php if($class_id && $exam_id){ ?>

<form method="POST">

<input type="hidden" name="exam_id" value="<?= $exam_id ?>">

<div class="table-wrapper mt-3">

<table class="table table-bordered">

<thead>
<tr>
<th>S.No</th>
<th>Student</th>

<?php
$sub_list = [];
while($sub=$subjects->fetch_assoc()){
$sub_list[] = $sub;
?>
<th><?= $sub['subject_name'] ?></th>
<?php } ?>

<th class="total">Total</th>

<?php if($show_percentage){ ?>
<th class="total">%</th>
<?php } ?>

</tr>
</thead>

<tbody>

<?php
$i=1;
while($st=$students->fetch_assoc()){
?>

<tr>

<td><?= $i++ ?></td>
<td><?= $st['name'] ?></td>

<?php foreach($sub_list as $sub){ ?>

<td>
<input type="number"
name="marks[<?= $st['id'] ?>][<?= $sub['id'] ?>]"
min="0" max="100">
</td>

<?php } ?>

<td class="total">0</td>

<?php if($show_percentage){ ?>
<td class="total">0%</td>
<?php } ?>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<br>

<button class="btn btn-primary w-100">
💾 Save All Marks
</button>

</form>

<?php } ?>

</div>

</body>
</html>