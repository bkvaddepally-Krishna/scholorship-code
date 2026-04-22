<?php
$conn = new mysqli("localhost","u453722092_mst2026","v!6@YptD","u453722092_mst2026");

if($conn->connect_error){
    die("DB Error");
}

// --- ADD THIS BRANDING FUNCTION ---
function ns_branding() {
    // We use a leading slash / to ensure the path works from any subfolder
    echo '
    <link rel="icon" type="image/jpeg" href="/Upload/logo.JPEG">
    <link rel="apple-touch-icon" href="/Upload/logo.JPEG">
    <meta name="author" content="Ns TECH">
    ';
}
?>