<?php include '../../config/auth.php'; ?>
<h2>Analytics</h2>
<canvas id="a1"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('a1'),{
 type:'bar',
 data:{
 labels:['A','B','C'],
 datasets:[{label:'Performance',data:[80,60,90]}]
 }
});
</script>
