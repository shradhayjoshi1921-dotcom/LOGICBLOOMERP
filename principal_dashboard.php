<?php
session_start();
include("db.php");
include("auth_principal.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$principal_id   = $_SESSION['principal_id'] ?? 0;
$principal_name = $_SESSION['principal_name'] ?? 'Principal';

/* =========================
   DASHBOARD INSIGHTS
========================= */

// Branches
$branch_count = 0;
$q1 = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM branches 
    WHERE principal_id='$principal_id'
");
if($q1){
    $branch_count = mysqli_fetch_assoc($q1)['total'] ?? 0;
}

// Classes
$class_count = 0;
$q2 = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM classes 
    WHERE principal_id='$principal_id'
");
if($q2){
    $class_count = mysqli_fetch_assoc($q2)['total'] ?? 0;
}

// Students (still counted, just not shown as card)
$student_count = 0;
$q3 = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM students 
    WHERE principal_id='$principal_id'
");
if($q3){
    $student_count = mysqli_fetch_assoc($q3)['total'] ?? 0;
}

// Teachers
$teacher_count = 0;
$q4 = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM teachers 
    WHERE principal_id='$principal_id'
");
if($q4){
    $teacher_count = mysqli_fetch_assoc($q4)['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Principal Dashboard</title>

<link rel="stylesheet" href="style.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    background:
    radial-gradient(circle at top left,
    rgba(124,58,237,.20),
    transparent 35%),

    radial-gradient(circle at bottom right,
    rgba(37,99,235,.15),
    transparent 35%),

    #050816;

    min-height:100vh;
    color:white;
    padding:30px;
    overflow-x:hidden;
}

/* Main Container */

.glass{
    width:100%;
    max-width:1400px;
    margin:auto;
}

/* Top Bar */

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:50px;
}

.logo{
    font-size:42px;
    font-weight:800;
    letter-spacing:-1px;
}

.logo span{
    color:#8b5cf6;
}

.nav-buttons{
    display:flex;
    gap:12px;
}

/* Buttons */

.back,
.logout{
    text-decoration:none;
    color:white;
    padding:10px 20px;
    border-radius:14px;
    font-weight:600;
    transition:.3s;
}

.back{
    background:#111827;
    border:1px solid #1e293b;
}

.logout{
    background:linear-gradient(
    135deg,
    #7c3aed,
    #5b21b6
    );

    box-shadow:
    0 0 15px rgba(124,58,237,.25);
}

.back:hover,
.logout:hover{
    transform:translateY(-3px);
}

/* Dashboard Badge */

.dashboard-badge{
    display:inline-block;

    padding:8px 14px;

    background:
    rgba(124,58,237,.12);

    border:
    1px solid rgba(124,58,237,.20);

    border-radius:999px;

    color:#c4b5fd;

    font-size:13px;

    margin-bottom:20px;
}

/* Heading */

h2{
    font-size:56px;
    font-weight:800;
    line-height:1.1;
    margin-bottom:12px;
}

.subtitle{
    color:#94a3b8;
    font-size:18px;
    margin-bottom:40px;
}

/* Section Title */

.section-title{
    font-size:28px;
    font-weight:700;
    margin-top:40px;
    margin-bottom:20px;
}

/* Overview Cards */

.insights{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;
    margin-bottom:50px;
}

.card{
    background:linear-gradient(
    145deg,
    #111827,
    #0f172a
    );

    border:1px solid
    rgba(139,92,246,.12);

    border-radius:24px;

    padding:30px;

    transition:.35s;
}

.card:hover{

    transform:translateY(-8px);

    border-color:#7c3aed;

    box-shadow:
    0 0 25px rgba(124,58,237,.25);

}

.insights .card{
    text-align:center;
}

.insights .card h1{
    font-size:72px;
    font-weight:800;
    color:white;
    margin-bottom:10px;
}

.insights .card p{
    font-size:18px;
    color:#cbd5e1;
}

/* Management */

.actions{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;
}

.actions .card h3{
    font-size:30px;
    color:white;
    margin-bottom:18px;
}

/* Action Buttons */

.btn{

    display:block;

    width:100%;

    text-align:center;

    text-decoration:none;

    padding:15px;

    margin-top:14px;

    border-radius:14px;

    font-size:15px;

    font-weight:700;

    color:white;

    background:linear-gradient(
    135deg,
    #7c3aed,
    #4f46e5
    );

    transition:.3s;

}

.btn:hover{

    transform:translateY(-3px);

    box-shadow:
    0 0 20px rgba(124,58,237,.35);

}

/* Tablet */

@media(max-width:1100px){

    .insights{
        grid-template-columns:repeat(2,1fr);
    }

    .actions{
        grid-template-columns:repeat(2,1fr);
    }

}

/* Mobile */

@media(max-width:768px){

    body{
        padding:20px;
    }

    .top-bar{
        flex-direction:column;
        align-items:flex-start;
        gap:20px;
    }

    .nav-buttons{
        width:100%;
    }

    .back,
    .logout{
        flex:1;
        text-align:center;
    }

    .logo{
        font-size:34px;
    }

    h2{
        font-size:36px;
    }

    .subtitle{
        font-size:16px;
    }

    .insights{
        grid-template-columns:1fr;
    }

    .actions{
        grid-template-columns:1fr;
    }

    .insights .card h1{
        font-size:56px;
    }

}

</style>

</head>

<body>

<div class="glass">

<div class="top-bar">

<div class="logo">
Logic<span>Bloom</span>
</div>

<div class="nav-buttons">

<a href="index.php" class="back">
← Back
</a>

<a href="logout.php" class="logout">
Logout
</a>

</div>

</div>

<h2>
Welcome,
<?php echo htmlspecialchars($principal_name); ?>
👋
</h2>

<div class="subtitle">
Principal Control Dashboard
</div>

<div class="section-title">
📊 Overview
</div>

<div class="insights">

<div class="card">
<h1><?php echo $branch_count; ?></h1>
<p>🏫 Branches</p>
</div>

<div class="card">
<h1><?php echo $class_count; ?></h1>
<p>📚 Classes</p>
</div>

<div class="card">
<h1><?php echo $teacher_count; ?></h1>
<p>👨‍🏫 Teachers</p>
</div>

</div>

<div class="section-title">
⚙️ Management
</div>

<div class="actions">

<div class="card">

<h3>🏫 Branches</h3>

<a href="add_branch.php" class="btn">
Add Branch
</a>

<a href="view_branches.php" class="btn">
View Branches
</a>

</div>

<div class="card">

<h3>📚 Classes</h3>

<a href="add_class.php" class="btn">
Add Class
</a>

<a href="view_class.php" class="btn">
View Classes
</a>

</div>

<div class="card">

<h3>👨‍🏫 Teachers</h3>

<a href="add_teacher.php" class="btn">
Add Teacher
</a>

<a href="manage_teachers.php" class="btn">
Manage Teachers
</a>

</div>

</div>

</div>

</body>
</html>

