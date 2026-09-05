<?php
session_start();
include("db.php");

$message = "";

/* ================= LOGIN ================= */
if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM principal WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        if(password_verify($password,$row['password'])){

            $_SESSION['principal_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = "principal";

            header("Location: principal_dashboard.php");
            exit();

        }else{
            $message = "Invalid Password";
        }

    }else{
        $message = "Account Not Found";
    }
}

/* ================= REGISTER ================= */
if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = trim($_POST['password']);

    $password = password_hash(
        $password_raw,
        PASSWORD_DEFAULT
    );

    $check = $conn->prepare(
        "SELECT id FROM principal WHERE email=?"
    );

    $check->bind_param("s",$email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){

        $message = "Email Already Exists";

    }else{

        $stmt = $conn->prepare(
        "INSERT INTO principal(name,email,password)
         VALUES(?,?,?)");

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $password
        );

        if($stmt->execute()){
            $message = "Account Created Successfully";
        }else{
            $message = "Registration Failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>Logic Bloom ERP</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#0b1120;
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:20px;
}

.container{
width:100%;
max-width:1200px;
height:720px;
background:#111827;
border:1px solid #1f2937;
border-radius:30px;
overflow:hidden;
display:flex;
box-shadow:
0 20px 60px rgba(0,0,0,.4);
}

/* LEFT */

.left{
width:55%;
background:
linear-gradient(
135deg,
#0f172a,
#111827
);

padding:70px;
display:flex;
flex-direction:column;
justify-content:center;
position:relative;
}

.logo{
font-size:38px;
font-weight:800;
color:white;
margin-bottom:15px;
}

.tagline{
font-size:18px;
color:#94a3b8;
line-height:1.8;
margin-bottom:50px;
max-width:500px;
}

.feature{
display:flex;
align-items:center;
margin-bottom:22px;
color:white;
font-size:17px;
}

.icon{
width:38px;
height:38px;
border-radius:12px;
background:#7c3aed;
display:flex;
justify-content:center;
align-items:center;
margin-right:15px;
font-size:18px;
}

/* RIGHT */

.right{
width:45%;
background:#0f172a;
display:flex;
justify-content:center;
align-items:center;
padding:50px;
}

.form-box{
width:100%;
max-width:380px;
}

.welcome{
font-size:34px;
font-weight:800;
color:white;
margin-bottom:10px;
}

.subtitle{
color:#94a3b8;
margin-bottom:35px;
}

label{
display:block;
color:#cbd5e1;
font-size:14px;
margin-bottom:8px;
margin-top:18px;
}

input{
width:100%;
height:55px;
padding:0 16px;
border-radius:14px;
border:1px solid #334155;
background:#111827;
color:white;
font-size:15px;
}

input:focus{
outline:none;
border-color:#7c3aed;
}

.btn{
width:100%;
height:55px;
margin-top:25px;
border:none;
border-radius:14px;
background:#7c3aed;
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
transition:.3s;
}

.btn:hover{
background:#6d28d9;
}

.toggle{
margin-top:20px;
text-align:center;
color:#94a3b8;
cursor:pointer;
}

.toggle:hover{
color:white;
}

.message{
margin-top:20px;
text-align:center;
color:#fbbf24;
font-weight:500;
}

.back{
display:inline-block;
margin-bottom:20px;
color:#94a3b8;
text-decoration:none;
}

.back:hover{
color:white;
}

#registerForm{
display:none;
}

@media(max-width:900px){

.container{
flex-direction:column;
height:auto;
}

.left{
width:100%;
padding:40px;
}

.right{
width:100%;
padding:40px 25px;
}
}

</style>

<script>

function toggleForm(){

let login =
document.getElementById("loginForm");

let register =
document.getElementById("registerForm");

if(login.style.display==="none"){

login.style.display="block";
register.style.display="none";

}else{

login.style.display="none";
register.style.display="block";
}
}

</script>

</head>

<body>

<div class="container">

<div class="left">

<div class="logo">
LOGIC BLOOM ERP
</div>

<div class="tagline">
Modern AI-powered institution management platform
for schools, colleges and universities.
Manage everything from one dashboard.
</div>

<div class="feature">
<div class="icon">📊</div>
Performance Analytics
</div>

<div class="feature">
<div class="icon">🎓</div>
Student Management
</div>

<div class="feature">
<div class="icon">📝</div>
Online Assessments
</div>

<div class="feature">
<div class="icon">📅</div>
Attendance Tracking
</div>

<div class="feature">
<div class="icon">🤖</div>
AI Insights & Reports
</div>

</div>

<div class="right">

<div class="form-box">

<a href="index.php" class="back">
← Back
</a>

<div class="welcome">
Welcome Back
</div>

<div class="subtitle">
Sign in to access the principal portal
</div>

<form method="POST" id="loginForm">

<label>Email Address</label>

<input
type="email"
name="email"
required>

<label>Password</label>

<input
type="password"
name="password"
required>

<button
type="submit"
name="login"
class="btn">
Sign In
</button>

</form>

<form method="POST" id="registerForm">

<label>Full Name</label>

<input
type="text"
name="name"
required>

<label>Email Address</label>

<input
type="email"
name="email"
required>

<label>Password</label>

<input
type="password"
name="password"
required>

<button
type="submit"
name="register"
class="btn">
Create Account
</button>

</form>

<div
class="toggle"
onclick="toggleForm()">

Switch Login / Create Account

</div>

<div class="message">
<?php echo $message; ?>
</div>

</div>

</div>

</div>

</body>
</html>
