<?php
include("db.php");

$class_id = $_GET['class_id'];

$res = mysqli_query($conn,"
SELECT id, name FROM students 
WHERE class_id='$class_id'
");

while($s=mysqli_fetch_assoc($res)){
?>

<div class="student-card">
<span><?php echo $s['name']; ?></span>

<div>
<button type="button" class="present" onclick="toggle(this)">Present</button>
<input type="hidden" name="status[<?php echo $s['id']; ?>]" value="Present">
</div>

</div>

<?php } ?>
