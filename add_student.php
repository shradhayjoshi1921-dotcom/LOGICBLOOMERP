<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];
$msg = "";

/* FETCH BRANCHES */
$branches = mysqli_query($conn, "
    SELECT * FROM branches 
    WHERE principal_id = '$principal_id'
");

/* ADD STUDENT */
if(isset($_POST['add_student'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $branch_id = $_POST['branch_id'];
    $class_id = $_POST['class_id'];

    $teacher_id = $_SESSION['teacher_id'] ?? 0;

    /* CHECK DUPLICATE */
    $check = mysqli_query($conn, "
        SELECT id FROM students 
        WHERE roll_no = '$roll_no' 
        AND branch_id = '$branch_id' 
        AND class_id = '$class_id'
    ");

    if(mysqli_num_rows($check) > 0){
        $msg = "❌ Roll number already exists in this class!";
    } else {

        $insert = mysqli_query($conn, "
            INSERT INTO students 
            (principal_id, teacher_id, branch_id, class_id, name, roll_no, password)
            VALUES 
            ('$principal_id','$teacher_id','$branch_id','$class_id','$name','$roll_no','$password')
        ");

        if($insert){
            $msg = "✅ Student added successfully!";
        } else {
            $msg = "❌ DB Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Student</title>

<link rel="stylesheet" href="style.css">

<style>

.form-box{
    display:flex;
    flex-direction:column;
    gap:15px;
    margin-top:20px;
}

.btn-group{
    display:flex;
    gap:15px;
    margin-top:10px;
    flex-wrap:wrap;
}

.btn{
    padding:12px 20px;
    border:none;
    border-radius:25px;
    text-decoration:none;
    color:white;
    font-weight:bold;
    text-align:center;
    cursor:pointer;
}

/* Add Student */
.add-btn{
    background: linear-gradient(45deg, #6a11cb, #2575fc);
}

/* Manage Students */
.manage-btn{
    background: linear-gradient(45deg, #ff9966, #ff5e62);
}

/* Hover */
.btn:hover{
    opacity:0.9;
    transform:scale(1.03);
    transition:0.3s;
}

.msg-success{
    color:green;
    font-weight:bold;
}

.msg-error{
    color:red;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>🎓 Add Student</h2>

<?php if($msg != ""){ ?>
    <p class="<?php echo (strpos($msg,'❌') !== false) ? 'msg-error' : 'msg-success'; ?>">
        <?php echo $msg; ?>
    </p>
<?php } ?>

<form method="POST" class="form-box">

<input type="text" name="name" placeholder="Student Name" required>

<input type="text" name="roll_no" placeholder="Roll Number (e.g. 101-A)" required>

<input type="text" name="password" placeholder="Password" required>

<select name="branch_id" required onchange="loadClasses(this.value)">
    <option value="">Select Branch</option>
    <?php while($b = mysqli_fetch_assoc($branches)){ ?>
        <option value="<?php echo $b['id']; ?>">
            <?php echo $b['branch_name']; ?>
        </option>
    <?php } ?>
</select>

<select name="class_id" id="classSelect" required>
    <option value="">Select Class</option>
</select>

<div class="btn-group">

    <button type="submit" name="add_student" class="btn add-btn">
        ➕ Add Student
    </button>

    <a href="manage_students.php" class="btn manage-btn">
        👨‍🎓 Manage Students
    </a>

</div>

</form>

</div>

<!-- LOAD CLASSES -->
<script>
function loadClasses(branch_id){
    fetch("get_classes.php?branch_id=" + branch_id)
    .then(res => res.text())
    .then(data => {
        document.getElementById("classSelect").innerHTML = data;
    });
}
</script>

</body>
</html>
