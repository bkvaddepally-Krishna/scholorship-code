<?php
include '../../config/db.php';

$res=$conn->query("SELECT * FROM classes");
?>

<h2>Class List</h2>

<table class="table table-bordered">
<tr><th>ID</th><th>Class</th></tr>

<?php while($r=$res->fetch_assoc()){ ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['class_name'] ?></td>
</tr>
<?php } ?>

</table>