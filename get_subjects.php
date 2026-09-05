<?php
session_start();
include("db.php");

$class_id = $_GET['class_id'];
$teacher_id = $_SESSION['teacher_id'];

$res = mysqli_query($conn,"
SELECT id, subject_name FROM subjects 
WHERE class_id='$class_id' AND teacher_id='$teacher_id'
");

echo "<option>Select Subject</option>";
while($row=mysqli_fetch_assoc($res)){
echo "<option value='{$row['id']}'>{$row['subject_name']}</option>";
}
?>
