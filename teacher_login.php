<?php
session_start();
include("db.php");

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT * FROM teachers
         WHERE email = ?
         AND is_active = 1"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $teacher = $result->fetch_assoc();

        if (password_verify($password, $teacher['password'])) {

            session_regenerate_id(true);

            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            $_SESSION['principal_id'] = $teacher['principal_id'];
            $_SESSION['role'] = "teacher";

            header("Location: teacher_dashboard.php");
            exit();

        } else {

            $message = "Invalid Email or Password";

        }

    } else {

        $message = "Teacher Account Not Found";

    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>Teacher Login | Logic Bloom ERP</title>

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

.portal{
margin-top:20px;
text-align:center;
color:#94a3b8;
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
<div class="icon">👨‍🏫</div>
Teacher Dashboard
</div>

<div class="feature">
<div class="icon">📚</div>
Student Records
</div>

<div class="feature">
<div class="icon">📝</div>
Marks Management
</div>

<div class="feature">
<div class="icon">📅</div>
Attendance Tracking
</div>

<div class="feature">
<div class="icon">📈</div>
Performance Reports
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
Sign in to access the teacher portal
</div>

<form method="POST">

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

<div class="portal">
Teacher Portal
</div>

<div class="message">
<?php echo htmlspecialchars($message); ?>
</div>

</div>

</div>

</div>

</body>
</html>
