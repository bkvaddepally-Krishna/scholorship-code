<?php
include '../../config/db.php';

$res = $conn->query("SELECT * FROM logs ORDER BY id DESC LIMIT 10");

$data=[];

while($r=$res->fetch_assoc()){
$data[]=$r;
}

echo json_encode($data);
?>

<script>
function loadLogs(){
 fetch("api/live_logs.php")
 .then(r=>r.json())
 .then(data=>{
   let html="";
   data.forEach(l=>{
     html += `<li>${l.action} - ${l.created_at}</li>`;
   });
   document.getElementById("logBox").innerHTML=html;
 });
}

setInterval(loadLogs,2000);
loadLogs();
</script>