<?php
include '../../config/db.php';

$res=$conn->query("SELECT * FROM subjects");
?>

<h2>Subjects</h2>

<table class="table table-bordered">
<tr><th>ID</th><th>Subject</th></tr>

<?php while($r=$res->fetch_assoc()){ ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['subject_name'] ?></td>
</tr>
<?php } ?>

</table>