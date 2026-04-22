<?php
include '../../config/db.php';

/* ================= ADD CLASS ================= */
if(isset($_POST['add_class'])){
    $stmt = $conn->prepare("INSERT INTO classes(class_name) VALUES(?)");
    $stmt->bind_param("s", $_POST['class_name']);
    $stmt->execute();
}

/* ================= RENAME CLASS ================= */
if(isset($_POST['rename'])){
    $stmt = $conn->prepare("UPDATE classes SET class_name=? WHERE id=?");
    $stmt->bind_param("si", $_POST['class_name'], $_POST['id']);
    $stmt->execute();
}

/* ================= FETCH CLASSES ================= */
$classes = $conn->query("SELECT * FROM classes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Classes</title>

<style>
body{
    background:#06130a;
    color:white;
    font-family:Segoe UI;
}

.container{
    width:700px;
    margin:30px auto;
}

.card{
    background:linear-gradient(145deg,#0f2a1a,#0b1f13);
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:10px;
    margin:5px 0;
    background:#0b1f13;
    border:1px solid #14532d;
    color:white;
    border-radius:8px;
}

button{
    padding:10px;
    background:#22c55e;
    border:none;
    cursor:pointer;
    width:100%;
    font-weight:bold;
    border-radius:8px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    padding:10px;
    border:1px solid #14532d;
}

th{
    background:#14532d;
}

tr:hover{
    background:#14532d;
}
</style>
</head>

<body>

<div class="container">

<!-- ================= ADD CLASS ================= -->
<div class="card">
<h2>➕ Add Class</h2>

<form method="POST">
    <input name="class_name" placeholder="Enter Class Name (e.g. 10th)" required>
    <button name="add_class">Add Class</button>
</form>
</div>

<!-- ================= LIST + RENAME ================= -->
<div class="card">
<h2>🏫 Manage Classes</h2>

<table>
<tr>
<th>ID</th>
<th>Class Name</th>
<th>Action</th>
</tr>

<?php while($c = $classes->fetch_assoc()): ?>
<tr>

<form method="POST">
<td><?= $c['id'] ?></td>

<td>
<input type="text" name="class_name" value="<?= htmlspecialchars($c['class_name']) ?>">
</td>

<td>
<input type="hidden" name="id" value="<?= $c['id'] ?>">
<button name="rename">Update</button>
</td>

</form>

</tr>
<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>