<?php
include __DIR__ . '/../../config/db.php';

$id = $_GET['id'] ?? 0;

/* GET STUDENT */
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

/* GET CLASSES */
$classes = $conn->query("SELECT * FROM classes");

/* UPDATE */
if(isset($_POST['update'])){

    $ms_no = $_POST['ms_no'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $father = $_POST['father_name'];
    $mother = $_POST['mother_name'];
    $old_school = $_POST['old_school'];
    $class_id = $_POST['class_id'];

    $update = $conn->prepare("
        UPDATE students 
        SET ms_no=?, name=?, phone=?, father_name=?, mother_name=?, old_school=?, class_id=?
        WHERE id=?
    ");

    $update->bind_param(
        "ssssssii",
        $ms_no,
        $name,
        $phone,
        $father,
        $mother,
        $old_school,
        $class_id,
        $id
    );

    $update->execute();

    echo "<script>alert('Student Updated Successfully');window.location='view.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>

<style>
body{
    background:#06130a;
    color:white;
    font-family:Segoe UI;
}

form{
    width:520px;
    margin:40px auto;
    background:#0f2a1a;
    padding:20px;
    border-radius:10px;
}

input, select{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #14532d;
    background:#0b1f13;
    color:white;
}

button{
    background:#22c55e;
    border:none;
    padding:10px;
    width:100%;
    cursor:pointer;
    font-weight:bold;
}
</style>
</head>

<body>

<h2 style="text-align:center;">✏️ Edit Student</h2>

<form method="POST">

<input type="text" name="ms_no" value="<?= $data['ms_no'] ?>" placeholder="MS No">
<input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Name">
<input type="text" name="phone" value="<?= $data['phone'] ?>" placeholder="Phone">

<input type="text" name="father_name" value="<?= $data['father_name'] ?>" placeholder="Father Name">
<input type="text" name="mother_name" value="<?= $data['mother_name'] ?>" placeholder="Mother Name">
<input type="text" name="old_school" value="<?= $data['old_school'] ?>" placeholder="Old School">

<!-- CLASS DROPDOWN -->
<select name="class_id" required>
    <option value="">-- Select Class --</option>
    <?php while($c = $classes->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>"
            <?= ($c['id'] == $data['class_id']) ? 'selected' : '' ?>>
            <?= $c['class_name'] ?>
        </option>
    <?php endwhile; ?>
</select>

<button type="submit" name="update">Update Student</button>

</form>

</body>
</html>