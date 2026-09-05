<?php
include("db.php");

$sem = $_GET['sem'];

$res = mysqli_query($conn,"
SELECT id, class_name FROM classes 
WHERE semester='$sem'
");

echo "<option value=''>Select Class</option>";

while($row=mysqli_fetch_assoc($res)){
    echo "<option value='".$row['id']."'>".$row['class_name']."</option>";
}
