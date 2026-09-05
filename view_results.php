<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

/* Fetch Results */
$query = "
SELECT 
    marks.id,
    students.name AS student_name,
    teachers.name AS teacher_name,
    subjects.subject_name,
    marks.exam_name,
    marks.marks_obtained,
    marks.total_marks
FROM marks
JOIN students ON marks.student_id = students.id
JOIN teachers ON marks.teacher_id = teachers.id
JOIN subjects ON marks.subject_id = subjects.id
WHERE marks.principal_id = '$principal_id'
ORDER BY marks.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<div style="display:flex; justify-content:space-between; align-items:center;">
    <a href="principal_dashboard.php" class="back-link">← Back</a>
    <a href="logout.php" class="btn">Logout</a>
</div>

<h2 style="margin-top:15px;">Student Results 📊</h2>

<table style="margin-top:30px;">
    <tr>
        <th>Student</th>
        <th>Teacher</th>
        <th>Subject</th>
        <th>Exam</th>
        <th>Marks</th>
        <th>Total</th>
        <th>%</th>
    </tr>

<?php
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){

        $percentage = 0;
        if($row['total_marks'] > 0){
            $percentage = ($row['marks_obtained'] / $row['total_marks']) * 100;
        }
?>

    <tr>
        <td><?php echo $row['student_name']; ?></td>
        <td><?php echo $row['teacher_name']; ?></td>
        <td><?php echo $row['subject_name']; ?></td>
        <td><?php echo $row['exam_name']; ?></td>
        <td><?php echo $row['marks_obtained']; ?></td>
        <td><?php echo $row['total_marks']; ?></td>
        <td><?php echo round($percentage,2); ?>%</td>
    </tr>

<?php
    }
} else {
    echo "<tr><td colspan='7'>No Results Found</td></tr>";
}
?>

</table>

</div>

</body>
</html>
