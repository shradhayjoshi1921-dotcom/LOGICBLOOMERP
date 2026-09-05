<?php
include("db.php");

$id=$_GET['id'];

$res=mysqli_query($conn,"
SELECT * FROM students WHERE class_id='$id'
");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="glass">

<h2>Students</h2>

<table>
<tr><th>Name</th><th>Roll No</th></tr>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['roll_no']; ?></td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>
