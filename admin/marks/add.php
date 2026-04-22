<?php
include '../../config/db.php';

$class = $_GET['class'] ?? '';

$students = $conn->query("SELECT * FROM students WHERE class='$class'");
$subjects = ['Math','Science','English','Social']; // can make dynamic later

if($_POST){

    foreach($_POST['marks'] as $student_id => $subMarks){

        $total = 0;

        foreach($subMarks as $m){
            $total += $m;
        }

        $percentage = ($total / (count($subMarks)*100)) * 100;

        foreach($subMarks as $subject => $mark){

            $conn->query("INSERT INTO marks(student_id,exam_id,subject,marks,max_marks)
            VALUES('$student_id','1','$subject','$mark','100')");
        }
    }

    echo "<script>alert('Marks Saved Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Class Wise Marks Sheet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:#f4f6f9;}

.card-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

table{
    background:white;
}

th{
    background:#111827;
    color:white;
    text-align:center;
}

td input{
    width:70px;
    text-align:center;
}

.total{
    font-weight:bold;
    color:green;
}

.perc{
    font-weight:bold;
    color:blue;
}
</style>

<script>
function calc(row){

    let inputs = document.querySelectorAll(".row-"+row+" input.mark");
    let total = 0;

    inputs.forEach(i=>{
        total += parseInt(i.value || 0);
    });

    document.getElementById("total-"+row).innerText = total;

    let percent = (total / (inputs.length*100)) * 100;
    document.getElementById("percent-"+row).innerText = percent.toFixed(2)+"%";
}
</script>

</head>

<body>

<div class="container mt-4">

<h2>📊 Class Wise Marks Entry Sheet</h2>

<form method="POST">

<div class="card-box">

<h5>Class: <?= $class ?></h5>

<table class="table table-bordered text-center">

<thead>
<tr>
<th>S.No</th>
<th>Student Name</th>

<?php foreach($subjects as $s){ ?>
<th><?= $s ?></th>
<?php } ?>

<th>Total</th>
<th>%</th>
</tr>
</thead>

<tbody>

<?php
$i=1;
while($s=$students->fetch_assoc()){
?>

<tr class="row-<?= $s['id'] ?>">

<td><?= $i++ ?></td>
<td><?= $s['name'] ?></td>

<?php foreach($subjects as $sub){ ?>
<td>
<input type="number"
class="mark"
name="marks[<?= $s['id'] ?>][<?= $sub ?>]"
oninput="calc(<?= $s['id'] ?>)">
</td>
<?php } ?>

<td class="total" id="total-<?= $s['id'] ?>">0</td>
<td class="perc" id="percent-<?= $s['id'] ?>">0%</td>

</tr>

<?php } ?>

</tbody>

</table>

<button class="btn btn-success w-100">
💾 Save All Marks
</button>

</div>

</form>

</div>

</body>
</html>