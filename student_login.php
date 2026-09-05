<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

$message = "";

/* FETCH UNIVERSITIES */
$universities = mysqli_query($conn, "SELECT id, institute_name FROM principal");

/* LOGIN */
if(isset($_POST['login'])){

    $principal_id = $_POST['principal_id'];
    $branch_id    = $_POST['branch_id'];
    $class_id     = $_POST['class_id'];
    $roll         = trim($_POST['roll_no']);
    $password     = $_POST['password'];

    if(empty($principal_id) || empty($branch_id) || empty($class_id) || empty($roll) || empty($password)){
        $message = "⚠️ Please fill all fields!";
    } 
    else {

        /* SECURE QUERY */
        $stmt = $conn->prepare("
        SELECT * FROM students 
        WHERE roll_no=? 
        AND principal_id=? 
        AND branch_id=? 
        AND class_id=?
        ");

        $stmt->bind_param("siii", $roll, $principal_id, $branch_id, $class_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $student = $result->fetch_assoc();

            /* ✅ HANDLE BOTH HASHED + OLD PASSWORDS */
            if(
                password_verify($password, $student['password']) || 
                $password === $student['password']
            ){

                /* AUTO HASH IF OLD PASSWORD */
                if($password === $student['password']){
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_query($conn, "
                    UPDATE students SET password='$newHash' WHERE id=".$student['id']
                    );
                }

                /* SESSION */
                $_SESSION['student_id']   = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['principal_id'] = $student['principal_id'];
                $_SESSION['branch_id']    = $student['branch_id'];
                $_SESSION['class_id']     = $student['class_id'];

                header("Location: student_dashboard.php");
                exit();

            } else {
                $message = "❌ Wrong Password!";
            }

        } else {
            $message = "❌ Student not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Login | Logic Bloom</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
    background:
    radial-gradient(circle at top left,#7c3aed33,transparent 40%),
    radial-gradient(circle at bottom right,#2563eb33,transparent 40%),
    #0a0a0f;
    overflow-x:hidden;
    position:relative;
}

/* Glow Effects */
body::before{
    content:'';
    position:absolute;
    width:600px;
    height:600px;
    background:#7c3aed;
    filter:blur(250px);
    opacity:.15;
    top:-200px;
    left:-200px;
}

body::after{
    content:'';
    position:absolute;
    width:600px;
    height:600px;
    background:#2563eb;
    filter:blur(250px);
    opacity:.15;
    bottom:-200px;
    right:-200px;
}

/* Main Card */
.login-card{
    width:100%;
    max-width:760px;
    background:rgba(15,15,25,.88);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,.08);
    border-radius:32px;
    padding:40px 55px;
    box-shadow:0 30px 80px rgba(0,0,0,.45);
    position:relative;
    z-index:10;
}

/* Inner Alignment Container */
.content-center{
    max-width:650px;
    margin:auto;
}

/* Back Button */
.back-btn{
    text-decoration:none;
    color:#bdbdbd;
    font-size:15px;
    display:inline-block;
    margin-bottom:30px;
}

.back-btn:hover{
    color:white;
}


/* Logo */
.logo{
    width:90px;
    height:90px;
    margin:0 auto 25px;
    border-radius:24px;
    background:linear-gradient(135deg,#7c3aed,#2563eb);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
    box-shadow:0 0 40px rgba(124,58,237,.45);
}

/* Heading */
h1{
    text-align:center;
    color:white;
    font-size:48px;
    font-weight:700;
    margin-bottom:8px;
    line-height:1.1;
}

.subtitle{
    text-align:center;
    color:#9ca3af;
    font-size:15px;
    margin-bottom:40px;
}

/* Error Message */
.message{
    background:rgba(239,68,68,.12);
    border:1px solid rgba(239,68,68,.2);
    color:#ff9a9a;
    padding:14px;
    border-radius:14px;
    margin-bottom:20px;
    text-align:center;
}

/* Inputs */
.input-group{
    margin-bottom:18px;
}

select,
input{
    width:100%;
    padding:18px 20px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,.08);
    background:#101827;
    color:white;
    font-size:15px;
    outline:none;
    transition:.3s;
}

select:focus,
input:focus{
    border-color:#7c3aed;
    box-shadow:0 0 20px rgba(124,58,237,.25);
}

/* Login Button */
.btn-login{
    width:100%;
    padding:18px;
    margin-top:8px;
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,#7c3aed,#2563eb);
    color:white;
    font-size:18px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
    box-shadow:0 15px 35px rgba(124,58,237,.25);
}

.btn-login:hover{
    transform:translateY(-2px);
}

/* Footer */
.footer{
    text-align:center;
    margin-top:25px;
    color:#777;
    font-size:13px;
}

/* Tablet */
@media(max-width:768px){

    .login-card{
        padding:30px;
        max-width:95%;
    }

    h1{
        font-size:36px;
    }

    .logo{
        width:75px;
        height:75px;
        font-size:30px;
    }

}

</style>
</head>

<body>

<div class="login-card">

<a href="index.php" class="back-btn">← Back</a>

<div class="logo">
🎓
</div>

<h1>Student Portal</h1>
<p class="subtitle">Logic Bloom Education ERP</p>

<?php if($message){ ?>
<div class="message">
<?= htmlspecialchars($message) ?>
</div>
<?php } ?>

<form method="POST">

<div class="input-group">
<select name="principal_id" required onchange="this.form.submit()">
<option value="">🏛 Select University</option>

<?php mysqli_data_seek($universities,0); ?>
<?php while($u=mysqli_fetch_assoc($universities)){ ?>
<option value="<?= $u['id'] ?>"
<?= (isset($_POST['principal_id']) && $_POST['principal_id']==$u['id'])?'selected':'' ?>>
<?= htmlspecialchars($u['institute_name']) ?>
</option>
<?php } ?>
</select>
</div>

<div class="input-group">
<select name="branch_id" required onchange="this.form.submit()">
<option value="">📚 Select Branch</option>

<?php
if(isset($_POST['principal_id'])){
$stmt=$conn->prepare("SELECT * FROM branches WHERE principal_id=?");
$stmt->bind_param("i",$_POST['principal_id']);
$stmt->execute();
$res=$stmt->get_result();

while($b=$res->fetch_assoc()){
?>
<option value="<?= $b['id'] ?>"
<?= (isset($_POST['branch_id']) && $_POST['branch_id']==$b['id'])?'selected':'' ?>>
<?= htmlspecialchars($b['branch_name']) ?>
</option>
<?php }} ?>
</select>
</div>

<div class="input-group">
<select name="class_id" required>
<option value="">🎯 Select Semester</option>

<?php
if(isset($_POST['branch_id'])){
$stmt=$conn->prepare("SELECT * FROM classes WHERE branch_id=?");
$stmt->bind_param("i",$_POST['branch_id']);
$stmt->execute();
$res=$stmt->get_result();

while($c=$res->fetch_assoc()){
?>
<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['class_name']) ?> (<?= htmlspecialchars($c['batch']) ?>)
</option>
<?php }} ?>
</select>
</div>

<div class="input-group">
<input type="text" name="roll_no" placeholder="👤 Roll Number" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="🔒 Password" required>
</div>

<button name="login" class="btn-login">
🚀 Login to Dashboard
</button>

</form>

<div class="footer">
Logic Bloom ERP System © 2026
</div>

</div>

</body>
</html>

