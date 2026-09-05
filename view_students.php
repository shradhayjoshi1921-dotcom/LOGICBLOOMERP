<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

/* Fetch all students under this principal */
$query = mysqli_query($conn,
    "SELECT s.id, s.name, s.roll_no, t.name AS teacher_name
     FROM students s
     JOIN teachers t ON s.teacher_id = t.id
     WHERE s.principal_id='$principal_id'
     ORDER BY s.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Students</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>All Students</h2>

<?php if(mysqli_num_rows($query) > 0){ ?>

<table>
<tr>
    <th>ID</th>
    <th>Student Name</th>
    <th>Roll No</th>
    <th>Added By Teacher</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['roll_no']; ?></td>
    <td><?php echo $row['teacher_name']; ?></td>
</tr>
<?php } ?>

</table>

<?php } else { ?>

<p>No students found.</p>

<?php } ?>

</div>

</body>
</html>
