<?php
session_start();
include("db.php");

$principal_id=$_SESSION['principal_id'];
$branches=mysqli_query($conn,"SELECT * FROM branches");

$msg="";

if(isset($_POST['add'])){
$name=$_POST['class_name'];
$branch=$_POST['branch_id'];
$batch=$_POST['batch'];

mysqli_query($conn,"
INSERT INTO classes (class_name,branch_id,principal_id,batch)
VALUES('$name','$branch','$principal_id','$batch')
");

$msg="Class Added ✅";
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

<h2>Add Class</h2>

<form method="POST">

<select name="branch_id">
<?php while($b=mysqli_fetch_assoc($branches)){ ?>
<option value="<?php echo $b['id']; ?>">
<?php echo $b['branch_name']; ?>
</option>
<?php } ?>
</select>

<input type="text" name="class_name" placeholder="Class Name" required>
<input type="text" name="batch" placeholder="Batch (2022-25)" required>

<button class="btn" name="add">Add</button>

</form>

<?php echo $msg; ?>

</div>
</body>
</html>
