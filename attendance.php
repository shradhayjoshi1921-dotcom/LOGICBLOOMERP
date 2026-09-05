<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");
include("auth_teacher.php");

$teacher_id   = $_SESSION['teacher_id'];
$principal_id = $_SESSION['principal_id'];

$message = "";

/* ================= FETCH BRANCHES ================= */
$branches = mysqli_query($conn,
"SELECT * FROM branches WHERE principal_id='$principal_id'"
);

/* ================= SAVE ATTENDANCE ================= */
if(isset($_POST['save_attendance'])){

    $branch_id  = $_POST['branch_id'];
    $class_id   = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $date       = $_POST['attendance_date'];

    if(empty($date)){
        $message = "⚠️ Select date!";
    } else {

        foreach($_POST['attendance'] as $student_id => $status){

            // ✅ CHECK EXISTING RECORD
            $check = mysqli_query($conn,"
                SELECT id FROM attendance
                WHERE student_id='$student_id'
                AND subject_id='$subject_id'
                AND attendance_date='$date'
                AND principal_id='$principal_id'
            ");

            if(mysqli_num_rows($check) > 0){

                // 🔄 UPDATE
                mysqli_query($conn,"
                    UPDATE attendance SET
                    status='$status',
                    teacher_id='$teacher_id'
                    WHERE student_id='$student_id'
                    AND subject_id='$subject_id'
                    AND attendance_date='$date'
                ");

            } else {

                // ➕ INSERT
                mysqli_query($conn,"
                    INSERT INTO attendance
                    (student_id, teacher_id, principal_id, subject_id, attendance_date, status)
                    VALUES
                    ('$student_id','$teacher_id','$principal_id','$subject_id','$date','$status')
                ");
            }
        }

        $message = "✅ Attendance Saved Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>📅 Attendance</h2>

<?php if($message){ ?>
<p style="color:green;font-weight:bold;"><?= $message ?></p>
<?php } ?>

<form method="POST">

<!-- DATE -->
<input type="date" name="attendance_date" 
value="<?= $_POST['attendance_date'] ?? date('Y-m-d') ?>" required>

<!-- ================= BRANCH ================= -->
<select name="branch_id" required onchange="this.form.submit()">
<option value="">Select Branch</option>

<?php while($b=mysqli_fetch_assoc($branches)){ ?>
<option value="<?= $b['id'] ?>"
<?= (isset($_POST['branch_id']) && $_POST['branch_id']==$b['id'])?'selected':'' ?>>
<?= $b['branch_name'] ?>
</option>
<?php } ?>
</select>

<!-- ================= CLASS ================= -->
<select name="class_id" required onchange="this.form.submit()">
<option value="">Select Class</option>

<?php
if(isset($_POST['branch_id'])){
$class = mysqli_query($conn,"
SELECT * FROM classes WHERE branch_id='".$_POST['branch_id']."'
");

while($c=mysqli_fetch_assoc($class)){
?>
<option value="<?= $c['id'] ?>"
<?= (isset($_POST['class_id']) && $_POST['class_id']==$c['id'])?'selected':'' ?>>
<?= $c['class_name'] ?> (<?= $c['batch'] ?>)
</option>
<?php } } ?>
</select>

<!-- ================= SUBJECT ================= -->
<select name="subject_id" required>

<option value="">Select Subject</option>

<?php

$teacher_id = intval($_SESSION['teacher_id']);

$subjects = mysqli_query($conn,"
SELECT * FROM subjects
WHERE teacher_id='$teacher_id'
");

while($s = mysqli_fetch_assoc($subjects)){

?>

<option value="<?= $s['id'] ?>">

<?= htmlspecialchars($s['subject_name']) ?>

</option>

<?php } ?>

</select>
<hr>

<?php
/* ================= FETCH STUDENTS ================= */
if(isset($_POST['class_id'])){

$students = mysqli_query($conn,"
SELECT * FROM students 
WHERE class_id='".$_POST['class_id']."'
");

if(mysqli_num_rows($students) > 0){
?>

<table>
<tr>
<th>Name</th>
<th>Roll No</th>
<th>Status</th>
</tr>

<?php while($stu=mysqli_fetch_assoc($students)){ ?>
<tr>
<td><?= htmlspecialchars($stu['name']) ?></td>
<td><?= htmlspecialchars($stu['roll_no']) ?></td>
<td>
<select name="attendance[<?= $stu['id'] ?>]">
<option value="Present">Present</option>
<option value="Absent">Absent</option>
</select>
</td>
</tr>
<?php } ?>

</table>

<br>

<button type="submit" name="save_attendance">💾 Save Attendance</button>

<?php } else { ?>
<p>No Students Found</p>
<?php } } ?>

</form>

</div>

</body>
</html>
