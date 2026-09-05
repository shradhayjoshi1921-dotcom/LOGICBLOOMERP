<?php
session_start();
include("db.php");

$students = 0;
$teachers = 0;
$subjects = 0;
$attendance_percent = 0;
$total_salary = 0;

$q = mysqli_query($conn,"SELECT COUNT(*) AS total FROM students");
if($q){
    $students = mysqli_fetch_assoc($q)['total'];
}

$q = mysqli_query($conn,"SELECT COUNT(*) AS total FROM teachers");
if($q){
    $teachers = mysqli_fetch_assoc($q)['total'];
}

$q = mysqli_query($conn,"SELECT COUNT(*) AS total FROM subjects");
if($q){
    $subjects = mysqli_fetch_assoc($q)['total'];
}

$total_q = mysqli_query($conn,"SELECT COUNT(*) AS total FROM attendance");
$present_q = mysqli_query($conn,"SELECT COUNT(*) AS total FROM attendance WHERE status='Present'");

if($total_q && $present_q){
    $total = mysqli_fetch_assoc($total_q)['total'];
    $present = mysqli_fetch_assoc($present_q)['total'];

    if($total > 0){
        $attendance_percent = round(($present/$total)*100);
    }
}

$q = mysqli_query($conn,"SELECT SUM(amount) AS total FROM salary_payments");
if($q){
    $row = mysqli_fetch_assoc($q);
    $total_salary = $row['total'] ?: 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart UMS</title>
<link rel="stylesheet" href="index_style.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">🎓 SMART UMS</div>
</nav>

<section class="hero">
    <h1>AI Powered University Management System</h1>
    <p>
        Manage Students, Faculty, Attendance,
        Results and Academic Operations
        from a single intelligent platform.
    </p>

    <div class="hero-buttons">
        <a href="principal_login.php" class="btn">Principal Portal</a>
        <a href="teacher_login.php" class="btn">Teacher Portal</a>
        <a href="student_login.php" class="btn">Student Portal</a>
    </div>
</section>

<section class="stats">

    <div class="stat-card">
        <h2><?php echo $students; ?></h2>
        <p>Students</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $teachers; ?></h2>
        <p>Teachers</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $subjects; ?></h2>
        <p>Subjects</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $attendance_percent; ?>%</h2>
        <p>Attendance</p>
    </div>

</section>

<section class="ai-box">

    <h2>🤖 AI Academic Insights</h2>

    <div class="insight">
        👨‍🎓 Total Students : <?php echo $students; ?>
    </div>

    <div class="insight">
        👩‍🏫 Total Teachers : <?php echo $teachers; ?>
    </div>

    <div class="insight">
        📚 Total Subjects : <?php echo $subjects; ?>
    </div>

    <div class="insight">
        📈 Attendance : <?php echo $attendance_percent; ?>%
    </div>

</section>

<section class="preview">

    <h2>Dashboard Preview</h2>

    <div class="preview-card">

        <div class="preview-item">
            👨‍🎓 Students
            <span><?php echo $students; ?></span>
        </div>

        <div class="preview-item">
            👩‍🏫 Teachers
            <span><?php echo $teachers; ?></span>
        </div>

        <div class="preview-item">
            📚 Subjects
            <span><?php echo $subjects; ?></span>
        </div>

        <div class="preview-item">
            📈 Attendance
            <span><?php echo $attendance_percent; ?>%</span>
        </div>

        <div class="preview-item">
            💰 Salary Paid
            <span>₹<?php echo number_format($total_salary); ?></span>
        </div>

    </div>

</section>

<footer>
    <h3>SMART UMS</h3>
    <p>AI Powered University Management System</p>
    <p>Developed by Shradhay</p>
</footer>

</body>
</html>
