<?php
include("db.php");

$res=mysqli_query($conn,"
SELECT c.*,b.branch_name 
FROM classes c
JOIN branches b ON c.branch_id=b.id
");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="glass">

<h2>Classes</h2>

<table>
<tr><th>Branch</th><th>Class</th><th>Batch</th></tr>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<tr>
<td><?php echo $row['branch_name']; ?></td>
<td>
<a href="class_students.php?id=<?php echo $row['id']; ?>">
<?php echo $row['class_name']; ?>
</a>
</td>
<td><?php echo $row['batch']; ?></td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>
