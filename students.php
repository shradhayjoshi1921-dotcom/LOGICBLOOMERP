<?php
session_start();
include("db.php");
include("auth_teacher.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$teacher_id   = $_SESSION['teacher_id'];
$principal_id = $_SESSION['principal_id'];

$message = "";

/* =========================
   GET TEACHER BRANCH
========================= */
$stmt = $conn->prepare("SELECT branch_id FROM teachers WHERE id=?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$res = $stmt->get_result();
$teacher = $res->fetch_assoc();

$branch_id = $teacher['branch_id'] ?? 0;

/* =========================
   FETCH CLASSES (ONLY THIS BRANCH)
========================= */
$class_stmt = $conn->prepare("SELECT id, class_name, batch FROM classes WHERE branch_id=?");
$class_stmt->bind_param("i", $branch_id);
$class_stmt->execute();
$classes = $class_stmt->get_result();

/* =========================
   ADD STUDENT
========================= */
if(isset($_POST['add_student'])){

    $name     = trim($_POST['name']);
    $roll     = trim($_POST['roll_no']);
    $password = $_POST['password'];
    $class_id = intval($_POST['class_id']);

    if(empty($name) || empty($roll) || empty($password) || !$class_id){
        $message = "⚠️ All fields are required!";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        /* CHECK DUPLICATE ROLL IN SAME CLASS */
        $check = $conn->prepare("
            SELECT id FROM students 
            WHERE roll_no=? AND class_id=? AND principal_id=?
        ");
        $check->bind_param("sii", $roll, $class_id, $principal_id);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $message = "❌ Roll number already exists in this class!";
        } else {

            /* INSERT STUDENT */
            $insert = $conn->prepare("
                INSERT INTO students
                (name, roll_no, password, teacher_id, principal_id, branch_id, class_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $insert->bind_param(
                "sssiiii",
                $name,
                $roll,
                $hashed_password,
                $teacher_id,
                $principal_id,
                $branch_id,
                $class_id
            );

            if($insert->execute()){
                $message = "✅ Student Added Successfully!";
            } else {
                $message = "❌ Error adding student!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Student</title>
<link rel="stylesheet" href="style.css">

<style>
input, select {
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border-radius:25px;
    border:none;
    background:rgba(255,255,255,0.6);
}

.btn-group{
    display:flex;
    gap:15px;
    margin-top:10px;
}

.primary-btn{
    padding:14px 25px;
    border-radius:30px;
    border:none;
    background:linear-gradient(135deg,#6a11cb,#2575fc);
    color:white;
    cursor:pointer;
}

.manage-btn{
    padding:14px 25px;
    border-radius:30px;
    background:linear-gradient(135deg,#ff7e5f,#feb47b);
    color:white;
    text-decoration:none;
}
</style>

</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>🎓 Add Student</h2>

<?php if($message){ ?>
<p style="color:green;font-weight:bold;"><?= $message ?></p>
<?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Student Name" required>

<input type="text" name="roll_no" placeholder="Roll Number (e.g. 101-A)" required>

<input type="password" name="password" placeholder="Password" required>

<!-- 🔥 CLASS ONLY (NO BRANCH SELECT) -->
<select name="class_id" required>
<option value="">Select Class</option>

<?php while($c = $classes->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>">
<?= $c['class_name'] ?> (<?= $c['batch'] ?? '' ?>)
</option>
<?php } ?>

</select>

<div class="btn-group">

<button type="submit" name="add_student" class="primary-btn">
➕ Add Student
</button>

<a href="manage_students.php" class="manage-btn">
👨‍🎓 Manage Students
</a>

</div>

</form>

</div>

</body>
</html>
