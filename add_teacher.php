<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("auth_principal.php");
include("db.php");

$principal_id = $_SESSION['principal_id'];
$message = "";

/* FETCH BRANCHES */
$branches = mysqli_query($conn, "
SELECT * FROM branches 
WHERE principal_id='$principal_id'
");

/* ADD TEACHER */
if(isset($_POST['add_teacher'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $password = password_hash($password_raw, PASSWORD_DEFAULT);
    $qualification = trim($_POST['qualification']);
    $address = trim($_POST['address']);
    $identity = trim($_POST['identity']);
    $salary = trim($_POST['salary']);
    $branch_id = $_POST['branch_id'];

    if(empty($branch_id)){
        $message = "⚠️ Please select branch!";
    }
    else {

        /* CHECK EMAIL */
        $check = $conn->prepare("SELECT id FROM teachers WHERE email=?");
        $check->bind_param("s",$email);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $message = "❌ Email already exists!";
        } 
        else {

            $stmt = $conn->prepare("
            INSERT INTO teachers 
            (name,email,password,qualification,address,identity_number,salary,principal_id,branch_id,is_active) 
            VALUES (?,?,?,?,?,?,?,?,?,1)
            ");

            $stmt->bind_param(
                "ssssssdii",
                $name,
                $email,
                $password,
                $qualification,
                $address,
                $identity,
                $salary,
                $principal_id,
                $branch_id
            );

            if($stmt->execute()){
                $message = "✅ Teacher added successfully!";
            } else {
                $message = "❌ Error adding teacher!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Teacher</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">

<style>
.form-card{
    width:95%;
    max-width:500px;
}
textarea{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border-radius:25px;
    border:none;
    outline:none;
    background:rgba(255,255,255,0.85);
    resize:none;
}
.message{
    margin-top:15px;
    font-size:14px;
}
</style>
</head>

<body>

<div class="glass form-card">

<a href="principal_dashboard.php" class="back-btn">← Back</a>

<h2>Add Teacher</h2>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<input type="text" name="qualification" placeholder="Qualification" required>

<textarea name="address" placeholder="Address" required></textarea>

<input type="text" name="identity" placeholder="Unique Identity (PAN/Aadhar)" required>

<input type="number" name="salary" placeholder="Monthly Salary" required>

<!-- 🔥 NEW: BRANCH SELECT -->
<select name="branch_id" required>
<option value="">Select Branch</option>

<?php while($b = mysqli_fetch_assoc($branches)){ ?>
<option value="<?= $b['id'] ?>">
<?= $b['branch_name'] ?>
</option>
<?php } ?>

</select>

<button type="submit" name="add_teacher" class="btn">Add Teacher</button>

</form>

<div class="message" style="color:<?= strpos($message,'❌')!==false?'red':'green' ?>">
<?= $message ?>
</div>

</div>

</body>
</html>
