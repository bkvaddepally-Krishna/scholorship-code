<?php
include '../../config/db.php';

$message = "";

/* ================= INSERT SUBJECT ================= */
if(isset($_POST['add_subject'])){

    $stmt = $conn->prepare("INSERT INTO subjects(subject_name) VALUES(?)");
    $stmt->bind_param("s", $_POST['subject_name']);

    if($stmt->execute()){
        /* redirect after create (PREVENT DUPLICATE SUBMIT) */
        header("Location: add.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Subject</title>

<style>
body{
    background:#06130a;
    color:white;
    font-family:Segoe UI;
}

.card{
    width:400px;
    margin:50px auto;
    background:linear-gradient(145deg,#0f2a1a,#0b1f13);
    padding:20px;
    border-radius:15px;
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

.success{
    text-align:center;
    color:#22c55e;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="card">

<h2>📚 Add Subject</h2>

<?php if(isset($_GET['success'])): ?>
<div class="success">Subject Created Successfully ✅</div>
<?php endif; ?>

<form method="POST">

<input type="text" name="subject_name" placeholder="Enter Subject Name" required>

<button name="add_subject">Create Subject</button>

</form>

</div>

</body>
</html>