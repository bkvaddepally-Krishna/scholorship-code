<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

// Handle Delete
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM system_notices WHERE id=$id");
    header("Location: add_notice.php");
}

// Handle Add
if($_POST){
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $conn->query("INSERT INTO system_notices (category, message) VALUES ('$cat', '$msg')");
}

$notices = $conn->query("SELECT * FROM system_notices ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Notices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm p-4">
            <h4 class="mb-4">Add System Notice</h4>
            <form method="POST">
                <select name="category" class="form-select mb-3">
                    <option>MERIT</option>
                    <option>EXAM</option>
                    <option>URGENT</option>
                    <option>STAFF</option>
                </select>
                <textarea name="message" class="form-control mb-3" placeholder="Enter notice message..." required></textarea>
                <button class="btn btn-success w-100">POST NOTICE</button>
            </form>
        </div>

        <div class="mt-5">
            <h5>Active Notices</h5>
            <?php while($row = $notices->fetch_assoc()): ?>
                <div class="alert alert-white border shadow-sm d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-dark me-2"><?= $row['category'] ?></span>
                        <?= $row['message'] ?>
                    </div>
                    <a href="?del=<?= $row['id'] ?>" class="btn btn-sm btn-danger">X</a>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="index.php" class="btn btn-link mt-3 text-dark">← Back to Dashboard</a>
    </div>
</body>
</html>