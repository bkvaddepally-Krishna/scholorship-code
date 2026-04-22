<?php
include '../../config/db.php';

/* ================= GET DATA ================= */
$classes = $conn->query("SELECT * FROM classes");
$subjects = $conn->query("SELECT * FROM subjects");

/* selected class */
$selected_class = $_GET['class_id'] ?? 0;

/* ================= SAVE / UPDATE ================= */
if(isset($_POST['save'])){

    $class_id = $_POST['class_id'];

    /* remove old */
    $stmt = $conn->prepare("DELETE FROM class_subjects WHERE class_id=?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();

    /* insert new */
    if(!empty($_POST['subjects'])){
        $ins = $conn->prepare("INSERT INTO class_subjects(class_id, subject_id) VALUES(?,?)");

        foreach($_POST['subjects'] as $sub){
            $ins->bind_param("ii", $class_id, $sub);
            $ins->execute();
        }
    }

    echo "<script>alert('Updated Successfully');window.location='assign_subjects.php?class_id=$class_id';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Class Subject Assignment</title>

<style>
body{
    background:#06130a;
    color:white;
    font-family:Segoe UI;
}

.container{
    width:800px;
    margin:30px auto;
}

.card{
    background:linear-gradient(145deg,#0f2a1a,#0b1f13);
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

select{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    background:#0b1f13;
    color:white;
    border:1px solid #14532d;
    border-radius:8px;
}

label{
    display:block;
    padding:6px;
}

button{
    width:100%;
    padding:10px;
    background:#22c55e;
    border:none;
    font-weight:bold;
    cursor:pointer;
    border-radius:8px;
}

/* BOX STYLE */
.subject-box{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:5px;
    padding:10px;
    border:1px solid #14532d;
    border-radius:10px;
    background:#0b1f13;
}

/* CLASS CARD */
.class-box{
    margin-top:20px;
    padding:15px;
    border-radius:12px;
    background:#0f2a1a;
    border:1px solid #14532d;
}
</style>
</head>

<body>

<div class="container">

<!-- ================= FORM ================= -->
<div class="card">

<h2>🔗 Assign Subjects (Edit Mode Enabled)</h2>

<form method="GET">
<select name="class_id" onchange="this.form.submit()">
    <option value="">Select Class</option>
    <?php while($c=$classes->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" 
            <?= ($selected_class==$c['id'])?'selected':'' ?>>
            <?= $c['class_name'] ?>
        </option>
    <?php endwhile; ?>
</select>
</form>

<?php if($selected_class): ?>

<?php
/* GET EXISTING SUBJECTS */
$assigned = [];
$res = $conn->query("SELECT subject_id FROM class_subjects WHERE class_id=$selected_class");

while($r=$res->fetch_assoc()){
    $assigned[] = $r['subject_id'];
}
?>

<form method="POST">

<input type="hidden" name="class_id" value="<?= $selected_class ?>">

<div class="subject-box">

<?php
$subjects = $conn->query("SELECT * FROM subjects");

while($s=$subjects->fetch_assoc()):
?>

<label>
<input type="checkbox" name="subjects[]" value="<?= $s['id'] ?>"
<?= in_array($s['id'],$assigned)?'checked':'' ?>>
<?= $s['subject_name'] ?>
</label>

<?php endwhile; ?>

</div>

<br>

<button name="save">💾 Save / Update Subjects</button>

</form>

<?php endif; ?>

</div>

<!-- ================= CLASS WISE BOX LIST ================= -->
<div class="card">

<h2>📚 Class Wise Subjects</h2>

<?php
$result = $conn->query("
SELECT c.id, c.class_name,
GROUP_CONCAT(s.subject_name SEPARATOR ', ') AS subjects
FROM class_subjects cs
JOIN classes c ON cs.class_id=c.id
JOIN subjects s ON cs.subject_id=s.id
GROUP BY cs.class_id
");
?>

<?php while($row=$result->fetch_assoc()): ?>
<div class="class-box">
    <h3><?= $row['class_name'] ?></h3>
    <p><?= $row['subjects'] ?></p>
</div>
<?php endwhile; ?>

</div>

</div>

</body>
</html>