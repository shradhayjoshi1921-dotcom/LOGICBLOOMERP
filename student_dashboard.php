<?php
session_start();
include("db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: student_login.php");
    exit();
}

$student_id = intval($_SESSION['student_id']);
$student_name = $_SESSION['student_name'];

$student = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM students
WHERE id='$student_id'
"));

$marks_query = mysqli_query($conn,"
SELECT m.*, s.subject_name
FROM marks m
LEFT JOIN subjects s
ON m.subject_id=s.id
WHERE m.student_id='$student_id'
");

$total_obt = 0;
$total_marks = 0;
$subjects = [];

while($m = mysqli_fetch_assoc($marks_query))
{
    $percent = ($m['total_marks'] > 0)
        ? round(($m['marks_obtained']/$m['total_marks'])*100)
        : 0;

    $total_obt += $m['marks_obtained'];
    $total_marks += $m['total_marks'];

    $subjects[] = [
        'subject_name' => $m['subject_name'],
        'marks_obtained' => $m['marks_obtained'],
        'total_marks' => $m['total_marks'],
        'percent' => $percent
    ];
}

$performance = ($total_marks > 0)
    ? round(($total_obt/$total_marks)*100)
    : 0;

if($performance >= 90)
    $grade = "A+";
elseif($performance >= 80)
    $grade = "A";
elseif($performance >= 70)
    $grade = "B+";
elseif($performance >= 60)
    $grade = "B";
elseif($performance >= 50)
    $grade = "C";
else
    $grade = "F";

$total_days = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM attendance
WHERE student_id='$student_id'
"));

$present_days = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM attendance
WHERE student_id='$student_id'
AND status='Present'
"))['total'];

$attendance_percent = ($total_days > 0)
    ? round(($present_days/$total_days)*100)
    : 0;

$absent_days = $total_days - $present_days;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LogicBloom SMART UMS</title>

<style>

:root{
--purple:#8b5cf6;
--purple2:#a855f7;
--dark:#050816;
--card:#111827;
--text:#ffffff;
--muted:#94a3b8;
}

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:
radial-gradient(circle at top left,
rgba(139,92,246,.18),
transparent 35%),

radial-gradient(circle at bottom right,
rgba(168,85,247,.12),
transparent 40%),

#050816;

min-height:100vh;
padding:25px;
color:white;
}

.container{
max-width:1300px;
margin:auto;
}

.glass{
background:rgba(17,24,39,.75);
backdrop-filter:blur(25px);
border:1px solid rgba(139,92,246,.15);
box-shadow:0 0 40px rgba(139,92,246,.15);
padding:30px;
border-radius:30px;
}

.logo{
font-size:13px;
letter-spacing:4px;
color:#a855f7;
font-weight:700;
margin-bottom:20px;
}

.topbar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.btn{
text-decoration:none;
color:white;
padding:12px 18px;
border-radius:12px;
background:#1f2937;
border:1px solid rgba(255,255,255,.08);
}

.hero{
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
gap:20px;
}

.hero h1{
font-size:40px;
}

.hero p{
margin-top:10px;
color:var(--muted);
}

.grade-box{
width:90px;
height:90px;
display:flex;
align-items:center;
justify-content:center;
border-radius:25px;
font-size:34px;
font-weight:bold;
background:linear-gradient(
135deg,
#7c3aed,
#9333ea,
#c084fc
);
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-top:30px;
}

.card{
background:#111827;
border-radius:20px;
padding:25px;
text-align:center;
border:1px solid rgba(139,92,246,.15);
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
border-color:#8b5cf6;
}

.card h2{
font-size:40px;
margin-bottom:10px;
}

.card p{
color:#94a3b8;
}

.section{
margin-top:35px;
}

.section h2{
margin-bottom:20px;
}

.subject-card{
background:#111827;
padding:20px;
border-radius:18px;
margin-bottom:15px;
border:1px solid rgba(255,255,255,.05);
}

.subject-row{
display:flex;
justify-content:space-between;
margin-bottom:10px;
}

.progress{
height:10px;
background:#374151;
border-radius:20px;
overflow:hidden;
}

.bar{
height:100%;
background:linear-gradient(
90deg,
#7c3aed,
#9333ea,
#c084fc
);
box-shadow:0 0 15px #8b5cf6;
}

table{
width:100%;
border-collapse:collapse;
background:#111827;
border-radius:20px;
overflow:hidden;
}

th{
background:#1f2937;
padding:15px;
}

td{
padding:15px;
text-align:center;
border-bottom:1px solid rgba(255,255,255,.05);
}

.good{
color:#22c55e;
font-weight:bold;
}

.avg{
color:#facc15;
font-weight:bold;
}

.bad{
color:#ef4444;
font-weight:bold;
}

.badges{
display:flex;
gap:15px;
flex-wrap:wrap;
margin-top:15px;
}

.badge{
padding:12px 18px;
border-radius:50px;
background:rgba(139,92,246,.15);
border:1px solid rgba(139,92,246,.35);
}

.footer{
margin-top:40px;
text-align:center;
color:#94a3b8;
}

@media(max-width:768px){

.hero h1{
font-size:28px;
}

.grade-box{
width:70px;
height:70px;
font-size:26px;
}

}
</style>
</head>

<body>

<div class="container">

<div class="glass">

<div class="logo">
LOGICBLOOM • SMART UMS
</div>

<div class="topbar">
<a href="index.php" class="btn">← Back</a>
<a href="logout.php" class="btn">Logout</a>
</div>

<div class="hero">

<div>
<h1>Welcome Back, <?php echo $student_name; ?> 👋</h1>

<p>Student Dashboard</p>

<p>
Roll No:
<strong><?php echo $student['roll_no']; ?></strong>
</p>
</div>

<div class="grade-box">
<?php echo $grade; ?>
</div>

</div>

<div class="cards">

<div class="card">
<h2><?php echo $performance; ?>%</h2>
<p>Performance</p>
</div>

<div class="card">
<h2><?php echo $attendance_percent; ?>%</h2>
<p>Attendance</p>
</div>

<div class="card">
<h2><?php echo $grade; ?></h2>
<p>Grade</p>
</div>

<div class="card">
<h2><?php echo count($subjects); ?></h2>
<p>Subjects</p>
</div>

</div>

<div class="section">

<h2>📚 Subject Performance</h2>

<?php foreach($subjects as $sub){ ?>

<div class="subject-card">

<div class="subject-row">
<span><?php echo $sub['subject_name']; ?></span>
<span><?php echo $sub['percent']; ?>%</span>
</div>

<div class="progress">
<div class="bar"
style="width:<?php echo $sub['percent']; ?>%">
</div>
</div>

</div>

<?php } ?>

</div>

<div class="section">

<h2>📊 Academic Report</h2>

<table>

<tr>
<th>Subject</th>
<th>Marks</th>
<th>Percentage</th>
<th>Status</th>
</tr>

<?php foreach($subjects as $sub){ ?>

<tr>

<td><?php echo $sub['subject_name']; ?></td>

<td>
<?php echo $sub['marks_obtained']; ?>
/
<?php echo $sub['total_marks']; ?>
</td>

<td>
<?php echo $sub['percent']; ?>%
</td>

<td>

<?php
if($sub['percent'] >= 75){
echo "<span class='good'>Excellent</span>";
}
elseif($sub['percent'] >= 50){
echo "<span class='avg'>Good</span>";
}
else{
echo "<span class='bad'>Needs Improvement</span>";
}
?>

</td>

</tr>

<?php } ?>

</table>

</div>

<div class="section">

<h2>📅 Attendance Analytics</h2>

<div class="cards">

<div class="card">
<h2><?php echo $total_days; ?></h2>
<p>Total Classes</p>
</div>

<div class="card">
<h2><?php echo $present_days; ?></h2>
<p>Present</p>
</div>

<div class="card">
<h2><?php echo $absent_days; ?></h2>
<p>Absent</p>
</div>

<div class="card">
<h2><?php echo $attendance_percent; ?>%</h2>
<p>Attendance Rate</p>
</div>

</div>

</div>

<div class="section">

<h2>🏆 Achievements</h2>

<div class="badges">

<?php if($attendance_percent >= 75){ ?>
<div class="badge">✅ Attendance Star</div>
<?php } ?>

<?php if($performance >= 80){ ?>
<div class="badge">🔥 Top Performer</div>
<?php } ?>

<?php if($grade=="A+" || $grade=="A"){ ?>
<div class="badge">⭐ Excellent Grade</div>
<?php } ?>

<div class="badge">
📚 Subjects Completed:
<?php echo count($subjects); ?>
</div>

</div>

</div>

<div class="footer">
SMART UMS v2.0 • Built with ❤️ by LogicBloom
</div>

</div>

</div>

</body>
</html>
