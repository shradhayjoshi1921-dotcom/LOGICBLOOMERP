<?php
include("db.php");

$university_id = $_GET['university_id'];

$query = mysqli_query($conn, "
    SELECT * FROM branches 
    WHERE university_id = '$university_id'
");

echo "<option value=''>Select Branch</option>";

while($row = mysqli_fetch_assoc($query)){
    echo "<option value='{$row['id']}'>{$row['branch_name']}</option>";
}
?>
