<?php
include '../../config/db.php';
if(isset($_POST['action'])){
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    $conn->query("INSERT INTO logs (action) VALUES ('$action')");
}
?>