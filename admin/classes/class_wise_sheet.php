<?php
include '../../config/db.php';

$class_id = $_GET['class_id'] ?? 1;
$exam_id = $_GET['exam_id'] ?? 1;

$students = $conn->query("SELECT * FROM students WHERE class='$class_id'");

$subjects = $conn->query("
SELECT s.* FROM subjects s
JOIN class_subjects cs ON s.id = cs.subject_id
WHERE cs.class_id = '$class_id'
");

$exams = $conn->query("SELECT * FROM exams");
$classes = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html>
<head>
<title>Marks Sheet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{background:#f4f6f9;}

.sheet{
max-height:500px;
overflow:auto;
background:white;
}

thead th{
position:sticky;
top:0;
background:#111827;
color:white;
z-index:2;
}

td input{
width:70px;
text-align:center;
}
</style>

<script>
function calc(id){
let inputs=document.querySelectorAll(".row-"+id+" input");
let total=0;
inputs.forEach(i=>total+=parseInt(i.value||0));

document.getElementById("t-"+id).innerText=total;
}
</script>

</head>

<body>

<div class="container mt-3">

<h2>📊 Excel Style Marks Sheet</h2>

<!-- FILTERS -->
<form method="GET">

<select name="class_id">
<?php while($c=$classes->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
<?php } ?>
</select>

<select name="exam_id">
<?php while($e=$exams->fetch_assoc()){ ?>
<option value="<?= $e['id'] ?>"><?= $e['name'] ?></option>
<?php } ?>
</select>

<button>Load</button>
</form>

<br>

<form method="POST">

<div class="sheet">

<table class="table table-bordered text-center">

<thead>
<tr>
<th>S.No</th>
<th>Student</th>

<?php while($s=$subjects->fetch_assoc()){ ?>
<th><?= $s['subject_name'] ?></th>
<?php } ?>

<th>Total</th>
</tr>
</thead>

<tbody>

<?php
$i=1;
while($st=$students->fetch_assoc()){
?>

<tr class="row-<?= $st['id'] ?>">

<td><?= $i++ ?></td>
<td><?= $st['name'] ?></td>

<?php
$subjects2 = $conn->query("
SELECT s.* FROM subjects s
JOIN class_subjects cs ON s.id=cs.subject_id
WHERE cs.class_id='$class_id'
");

while($sub=$subjects2->fetch_assoc()){
?>

<td>
<input type="number"
name="marks[<?= $st['id'] ?>][<?= $sub['id'] ?>]"
oninput="calc(<?= $st['id'] ?>)">
</td>

<?php } ?>

<td id="t-<?= $st['id'] ?>">0</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<button class="btn btn-success w-100 mt-3">
💾 Save Marks
</button>

</form>

</div>

</body>
</html>