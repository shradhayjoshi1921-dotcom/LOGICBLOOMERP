<?php
session_start();
include("db.php");
include("auth_teacher.php");

$teacher_id   = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$principal_id = $_SESSION['principal_id'];

/* ================= STATS ================= */

/* Total Students (under this teacher) */
$student_count = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM students WHERE teacher_id='$teacher_id'"
))['total'];

/* Total Subjects */
$subject_count = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM subjects WHERE teacher_id='$teacher_id'"
))['total'];

/* Today's Attendance */
$today = date("Y-m-d");
$attendance_today = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM attendance 
WHERE teacher_id='$teacher_id' AND attendance_date='$today'
"))['total'];

/* Latest Salary */
$salary_data = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM salary_payments 
WHERE teacher_id='$teacher_id' 
ORDER BY id DESC LIMIT 1"
));

/* Latest Notification */
$note = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM notifications 
WHERE teacher_id='$teacher_id' 
ORDER BY id DESC LIMIT 1"
));
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard</title>
<link rel="stylesheet" href="style.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#0a0a0f;
    min-height:100vh;
    padding:30px;
    color:white;
    overflow-x:hidden;
}

/* Background Glow */
body::before{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:#7c3aed;
    filter:blur(220px);
    opacity:.15;
    top:-150px;
    left:-150px;
    z-index:-1;
}

body::after{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:#2563eb;
    filter:blur(220px);
    opacity:.15;
    bottom:-150px;
    right:-150px;
    z-index:-1;
}

/* Main Container */
.glass{
    max-width:1400px;
    margin:auto;
}

/* Headings */
h1,h2,h3,h4,h5,h6{
    color:#ffffff;
}

/* Top Bar */
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#111827;
    padding:20px 25px;
    border-radius:20px;
    margin-bottom:30px;
    border:1px solid rgba(255,255,255,.05);
}

.back{
    color:white;
    text-decoration:none;
    font-weight:500;
}

.logout{
    text-decoration:none;
    color:white;
    background:#ef4444;
    padding:10px 18px;
    border-radius:12px;
    transition:.3s;
}

.logout:hover{
    transform:translateY(-2px);
}

/* Welcome Card */
.welcome-card{
    background:linear-gradient(135deg,#7c3aed,#2563eb);
    padding:35px;
    border-radius:25px;
    margin-bottom:30px;
    box-shadow:0 15px 40px rgba(124,58,237,.25);
}

.welcome-card h2{
    font-size:36px;
    margin-bottom:8px;
    color:white;
}

.welcome-card p{
    color:white;
    opacity:.9;
}

/* Stats */
.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:#111827;
    padding:30px;
    border-radius:24px;
    text-align:center;
    border:1px solid rgba(255,255,255,.05);
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-card h2{
    font-size:42px;
    margin-bottom:10px;
    background:linear-gradient(135deg,#8b5cf6,#60a5fa);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.stat-card p{
    color:#ffffff;
    font-size:16px;
}

/* Actions */
.actions{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.action-card{
    background:#111827;
    padding:30px;
    border-radius:24px;
    text-align:center;
    border:1px solid rgba(255,255,255,.05);
    transition:.3s;
}

.action-card:hover{
    transform:translateY(-5px);
}

.action-card h3{
    margin-bottom:20px;
    color:#ffffff;
}

/* Buttons */
.btn{
    display:inline-block;
    text-decoration:none;
    padding:12px 22px;
    border-radius:12px;
    color:white;
    background:linear-gradient(135deg,#7c3aed,#2563eb);
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(124,58,237,.35);
}

.small-btn{
    padding:10px 16px;
    font-size:14px;
}

/* Widgets */
.widgets{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.widget{
    background:#111827;
    padding:25px;
    border-radius:24px;
    border:1px solid rgba(255,255,255,.05);
}

.widget h3{
    margin-bottom:15px;
    color:white;
}

.widget p{
    color:white;
    margin-bottom:5px;
}

.widget small{
    color:#d1d5db;
}

/* Mobile */
@media(max-width:768px){

    body{
        padding:15px;
    }

    .top-bar{
        flex-direction:column;
        gap:15px;
    }

    .welcome-card{
        padding:25px;
    }

    .welcome-card h2{
        font-size:28px;
    }

    .widgets{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<div class="glass">

    <!-- TOP BAR -->
    <div class="top-bar">
        <a href="index.php" class="back">← Back</a>

        <div style="display:flex; gap:10px;">
            <a href="teacher_profile.php" class="btn small-btn">👤 Profile</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- WELCOME -->
    <h2>Welcome, <?php echo $teacher_name; ?> 👋</h2>

    <!-- STATS -->
    <div class="stats">

        <div class="stat-card">
            <h2><?php echo $student_count; ?></h2>
            <p>Students</p>
        </div>

        <div class="stat-card">
            <h2><?php echo $subject_count; ?></h2>
            <p>Subjects</p>
        </div>

        <div class="stat-card">
            <h2><?php echo $attendance_today; ?></h2>
            <p>Today Attendance</p>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="actions">

        <div class="action-card">
            <h3>📚 Subjects</h3>
            <a href="subject.php" class="btn">Open</a>
        </div>

        <div class="action-card">
            <h3>📝 Marks</h3>
            <a href="marks.php" class="btn">Open</a>
        </div>

        <div class="action-card">
            <h3>📅 Attendance</h3>
            <a href="attendance.php" class="btn">Open</a>
        </div>

        <div class="action-card">
            <h3>🎓 Students</h3>
            <a href="students.php" class="btn">Manage</a>
        </div>

    </div>

    <!-- WIDGETS -->
    <div class="widgets">

        <!-- Salary -->
        <div class="widget">
            <h3>💰 Salary</h3>

            <?php if($salary_data){ ?>
                <p><b><?php echo $salary_data['month']; ?></b></p>
                <p>₹<?php echo $salary_data['amount']; ?></p>
                <small><?php echo $salary_data['payment_date']; ?></small>
            <?php } else { ?>
                <p>No salary data</p>
            <?php } ?>

        </div>

        <!-- Notification -->
        <div class="widget">
            <h3>🔔 Notification</h3>

            <?php if($note){ ?>
                <p><?php echo $note['message']; ?></p>
                <small><?php echo $note['created_at']; ?></small>
            <?php } else { ?>
                <p>No notifications</p>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>

