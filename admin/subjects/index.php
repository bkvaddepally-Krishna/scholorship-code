<?php
include '../../config/db.php';

/* ================= ADD SUBJECT ================= */
if(isset($_POST['add_subject'])){
    $stmt = $conn->prepare("INSERT INTO subjects(subject_name) VALUES(?)");
    $stmt->bind_param("s", $_POST['subject_name']);
    $stmt->execute();

    header("Location: index.php?success=1");
    exit;
}

/* ================= UPDATE SUBJECT ================= */
if(isset($_POST['update_subject'])){
    $stmt = $conn->prepare("UPDATE subjects SET subject_name=? WHERE id=?");
    $stmt->bind_param("si", $_POST['subject_name'], $_POST['id']);
    $stmt->execute();

    header("Location: index.php?updated=1");
    exit;
}

/* ================= DELETE SUBJECT ================= */
if(isset($_GET['delete'])){
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();

    header("Location: index.php?deleted=1");
    exit;
}

/* ================= FETCH DATA ================= */
$subjects = $conn->query("SELECT * FROM subjects ORDER BY id DESC");

/* EDIT DATA */
$edit = null;
if(isset($_GET['edit'])){
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Subjects</title>

<style>
body{
    background:#06130a;
    color:white;
    font-family:Segoe UI;
}

.container{
    width:650px;
    margin:40px auto;
}

.card{
    background:linear-gradient(145deg,#0f2a1a,#0b1f13);
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    background:#0b1f13;
    border:1px solid #14532d;
    color:white;
    border-radius:8px;
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

table{
    width:100%;
    border-collapse:collapse;
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

a{
    color:#22c55e;
    text-decoration:none;
    margin-right:10px;
}

.msg{
    text-align:center;
    color:#22c55e;
}
</style>
</head>

<body>

<div class="container">

<!-- ================= ADD / EDIT FORM ================= -->
<div class="card">

<?php if($edit): ?>

<h2>✏️ Edit Subject</h2>

<form method="POST">

<input type="hidden" name="id" value="<?= $edit['id'] ?>">

<input type="text" name="subject_name" 
       value="<?= htmlspecialchars($edit['subject_name']) ?>" 
       required>

<button name="update_subject">Update Subject</button>

</form>

<a href="index.php">⬅ Cancel Edit</a>

<?php else: ?>

<h2>➕ Add Subject</h2>

<?php if(isset($_GET['success'])): ?>
<div class="msg">Subject Added Successfully</div>
<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>
<div class="msg">Subject Updated Successfully</div>
<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>
<div class="msg">Subject Deleted Successfully</div>
<?php endif; ?>

<form method="POST">

<input type="text" name="subject_name" placeholder="Enter Subject Name" required>

<button name="add_subject">Create Subject</button>

</form>

<?php endif; ?>

</div>

<!-- ================= SUBJECT LIST ================= -->
<div class="card">

<h2>📚 Subject List</h2>

<table>

<tr>
<th>ID</th>
<th>Subject Name</th>
<th>Actions</th>
</tr>

<?php while($s = $subjects->fetch_assoc()): ?>
<tr>
<td><?= $s['id'] ?></td>
<td><?= htmlspecialchars($s['subject_name']) ?></td>
<td>
    <a href="?edit=<?= $s['id'] ?>">Edit</a>
    <a href="?delete=<?= $s['id'] ?>" 
       onclick="return confirm('Delete this subject?')"
       style="color:red;">
       Delete
    </a>
</td>
</tr>
<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>