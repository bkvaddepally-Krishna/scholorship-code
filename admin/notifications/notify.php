<?php
include '../../config/db.php';

$conn->query("INSERT INTO logs(action) VALUES('Results Published & Notification Sent')");
echo "Notification Logged";
?>