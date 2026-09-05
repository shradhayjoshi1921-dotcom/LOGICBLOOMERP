<?php
session_start();
include("db.php");

/* 🔐 Ensure login */
if(!isset($_SESSION['principal_id'])){
    die("Session expired. Please login again.");
}

$principal_id = (int)$_SESSION['principal_id'];

$message = "";

/* 🔴 DELETE STUDENT */
if(isset($_POST['delete_student'])){
    $student_id = (int)$_POST['student_id'];

    $del = mysqli_query($conn,"
    DELETE FROM students 
    WHERE id='$student_id' AND principal_id='$principal_id'
    ");

    if($del){
        $message = "🗑️ Student deleted successfully";
    } else {
        $message = "❌ Delete failed";
    }
}

/* 🔄 Keep selections */
$branch_id = $_POST['branch_id'] ?? '';
$class_id  = $_POST['class_id'] ?? '';

/* 📦 Fetch branches */
$branches = mysqli_query($conn,"
SELECT * FROM branches 
WHERE principal_id='$principal_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Students</title>
<link rel="stylesheet" href="style.css">

<style>
select{
    width:100%;
    padding:14px;
    border-radius:25px;
    margin-bottom:15px;
    border:none;
    background:rgba(255,255,255,0.6);
}

table{
    width:100%;
    border-spacing:0 12px;
}

td,th{
    padding:15px;
}

td{
    background:rgba(255,255,255,0.3);
    border-radius:12px;
}

.delete-btn{
    padding:8px 15px;
    border:none;
    border-radius:20px;
    background:linear-gradient(135deg,#ff416c,#ff4b2b);
    color:white;
    cursor:pointer;
}
</style>

<script>
function confirmDelete(){
    return confirm("Are you sure you want to delete this student?");
}
</script>

</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php">← Back</a>

<h2>👨‍🎓 Manage Students</h2>

<?php if($message){ ?>
<p style="color:green;font-weight:bold;"><?= $message ?></p>
<?php } ?>

<form method="POST">

<!-- 🔹 BRANCH -->
<select name="branch_id" onchange="this.form.submit()" required>
<option value="">Select Branch</option>

<?php while($b=mysqli_fetch_assoc($branches)){ ?>
<option value="<?= $b['id'] ?>"
<?= ($branch_id==$b['id'])?'selected':'' ?>>
<?= $b['branch_name'] ?>
</option>
<?php } ?>
</select>

<!-- 🔹 CLASS -->
<select name="class_id" onchange="this.form.submit()" required>
<option value="">Select Class</option>

<?php
if($branch_id){

$class = mysqli_query($conn,"
SELECT * FROM classes WHERE branch_id='".intval($branch_id)."'
");

while($c=mysqli_fetch_assoc($class)){
?>
<option value="<?= $c['id'] ?>"
<?= ($class_id==$c['id'])?'selected':'' ?>>
<?= $c['class_name'] ?> (<?= $c['batch'] ?>)
</option>
<?php } } ?>
</select>

</form>

<hr>

<?php
/* 🔹 SHOW STUDENTS */
if($class_id){

$students = mysqli_query($conn,"
SELECT * FROM students 
WHERE class_id='".intval($class_id)."'
AND principal_id='$principal_id'
");

if(mysqli_num_rows($students)>0){
?>

<table>
<tr>
<th>Name</th>
<th>Roll No</th>
<th>Action</th>
</tr>

<?php while($s=mysqli_fetch_assoc($students)){ ?>
<tr>
<td><?= $s['name'] ?></td>
<td><?= $s['roll_no'] ?></td>
<td>

<form method="POST" onsubmit="return confirmDelete()">

<input type="hidden" name="student_id" value="<?= $s['id'] ?>">
<input type="hidden" name="branch_id" value="<?= $branch_id ?>">
<input type="hidden" name="class_id" value="<?= $class_id ?>">

<button name="delete_student" class="delete-btn">Delete</button>

</form>

</td>
</tr>
<?php } ?>

</table>

<?php } else { ?>
<p>No students found</p>
<?php } } ?>

</div>

</body>
</html>
