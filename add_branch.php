<?php
session_start();
include("db.php");

$principal_id=$_SESSION['principal_id'];
$msg="";

if(isset($_POST['add'])){
$name=$_POST['branch_name'];

mysqli_query($conn,"INSERT INTO branches (branch_name,principal_id)
VALUES('$name','$principal_id')");

$msg="Branch Added ✅";
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="glass">

<a href="principal_dashboard.php">← Back</a>

<h2>Add Branch</h2>

<form method="POST">
<input type="text" name="branch_name" placeholder="Branch Name" required>
<button class="btn" name="add">Add</button>
</form>

<?php echo $msg; ?>

</div>
</body>
</html>
