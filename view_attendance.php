<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

/* Fetch Attendance Records */
$query = "
SELECT 
    attendance.id,
    students.name AS student_name,
    teachers.name AS teacher_name,
    subjects.subject_name,
    attendance.attendance_date,
    attendance.status
FROM attendance
JOIN students ON attendance.student_id = students.id
JOIN teachers ON attendance.teacher_id = teachers.id
JOIN subjects ON attendance.subject_id = subjects.id
WHERE attendance.principal_id = '$principal_id'
ORDER BY attendance.attendance_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<div style="display:flex; justify-content:space-between; align-items:center;">
    <a href="principal_dashboard.php" class="back-link">← Back</a>
    <a href="logout.php" class="btn">Logout</a>
</div>

<h2 style="margin-top:15px;">Attendance Records 📋</h2>

<table style="margin-top:30px;">
    <tr>
        <th>Student</th>
        <th>Teacher</th>
        <th>Subject</th>
        <th>Date</th>
        <th>Status</th>
    </tr>

    <?php
    if($result && mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
    ?>
        <tr>
            <td><?php echo $row['student_name']; ?></td>
            <td><?php echo $row['teacher_name']; ?></td>
            <td><?php echo $row['subject_name']; ?></td>
            <td><?php echo $row['attendance_date']; ?></td>
            <td>
                <?php
                    if($row['status'] == "Present"){
                        echo "<span style='color:green;'>Present</span>";
                    } else {
                        echo "<span style='color:red;'>Absent</span>";
                    }
                ?>
            </td>
        </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='5'>No Attendance Records Found</td></tr>";
    }
    ?>
</table>

</div>

</body>
</html>
