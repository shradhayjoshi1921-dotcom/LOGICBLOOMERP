<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

/* DELETE TEACHER (SAFE) */
if(isset($_GET['delete'])){
    $teacher_id = intval($_GET['delete']);

    mysqli_query($conn,"
        DELETE FROM teachers 
        WHERE id='$teacher_id' 
        AND principal_id='$principal_id'
    ");

    header("Location: manage_teachers.php");
    exit();
}

/* FETCH TEACHERS */
$query = mysqli_query($conn,"
    SELECT * FROM teachers 
    WHERE principal_id='$principal_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Teachers</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">

<style>

/* ===== HEADER ===== */
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

/* ===== GRID CARDS ===== */
.teacher-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
    margin-top:25px;
}

/* ===== CARD ===== */
.teacher-card{
    padding:20px;
    border-radius:20px;
    background:rgba(255,255,255,0.25);
    backdrop-filter:blur(20px);
    transition:0.3s;
}

.teacher-card:hover{
    transform:translateY(-5px);
}

/* ===== NAME ===== */
.teacher-name{
    font-size:18px;
    font-weight:bold;
}

/* ===== INFO ===== */
.teacher-info{
    font-size:14px;
    opacity:0.8;
    margin:5px 0;
}

/* ===== BUTTON GROUP ===== */
.btn-group{
    margin-top:15px;
    display:flex;
    gap:10px;
}

/* ===== BUTTONS ===== */
.salary-btn{
    flex:1;
    padding:10px;
    border-radius:20px;
    text-align:center;
    text-decoration:none;
    color:white;
    background:linear-gradient(135deg,#00c6ff,#0072ff);
}

.delete-btn{
    flex:1;
    padding:10px;
    border-radius:20px;
    text-align:center;
    text-decoration:none;
    color:white;
    background:linear-gradient(135deg,#ff6b6b,#ff0000);
}

.empty{
    margin-top:30px;
    text-align:center;
    opacity:0.7;
}

</style>

</head>

<body>

<div class="glass">

<!-- TOP -->
<div class="top-bar">
    <a href="principal_dashboard.php">← Back</a>
    <h2>Manage Teachers 👨‍🏫</h2>
</div>

<!-- GRID -->
<div class="teacher-grid">

<?php
if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
?>

<div class="teacher-card">

    <div class="teacher-name">
        <?php echo htmlspecialchars($row['name']); ?>
    </div>

    <div class="teacher-info">
        📧 <?php echo htmlspecialchars($row['email']); ?>
    </div>

    <div class="teacher-info">
        🎓 <?php echo htmlspecialchars($row['qualification']); ?>
    </div>

    <div class="teacher-info">
        💰 ₹<?php echo number_format($row['salary']); ?>
    </div>

    <!-- ACTIONS -->
    <div class="btn-group">

        <!-- SALARY -->
        <a href="salary.php?teacher_id=<?php echo $row['id']; ?>" 
           class="salary-btn">
           💰 Salary
        </a>

        <!-- DELETE -->
        <a href="?delete=<?php echo $row['id']; ?>" 
           onclick="return confirm('Remove this teacher?')"
           class="delete-btn">
           Remove
        </a>

    </div>

</div>

<?php
    }
} else {
    echo "<div class='empty'>No Teachers Found</div>";
}
?>

</div>

</div>

</body>
</html>
