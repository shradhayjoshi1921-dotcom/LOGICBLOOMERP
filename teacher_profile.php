<?php
session_start();
include("db.php");
include("auth_teacher.php");

$teacher_id = $_SESSION['teacher_id'];

$message = "";
$type = "";

/* FETCH CURRENT DATA */
$query = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'");
$teacher = mysqli_fetch_assoc($query);

/* UPDATE PROFILE */
if(isset($_POST['update_profile'])){
    $name = $_POST['name'];
    $email = $_POST['email'];

    $update = mysqli_query($conn,
        "UPDATE teachers SET name='$name', email='$email' WHERE id='$teacher_id'"
    );

    if($update){
        $_SESSION['teacher_name'] = $name;
        $message = "Profile Updated Successfully ✅";
        $type = "success";
    } else {
        $message = "Update Failed ❌";
        $type = "error";
    }
}

/* CHANGE PASSWORD */
if(isset($_POST['change_password'])){
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    if(password_verify($old, $teacher['password'])){
        $new_hash = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE teachers SET password='$new_hash' WHERE id='$teacher_id'"
        );

        $message = "Password Changed Successfully 🔐";
        $type = "success";
    } else {
        $message = "Old Password Incorrect ❌";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>👤 My Profile</h2>

<!-- UPDATE PROFILE -->
<form method="POST">

    <input type="text" name="name" value="<?php echo $teacher['name']; ?>" required>

    <input type="email" name="email" value="<?php echo $teacher['email']; ?>" required>

    <button type="submit" name="update_profile" class="btn">
        Update Profile
    </button>

</form>

<hr style="margin:30px 0; opacity:0.3;">

<h3>🔐 Change Password</h3>

<!-- CHANGE PASSWORD -->
<form method="POST">

    <input type="password" name="old_password" placeholder="Enter Old Password" required>

    <input type="password" name="new_password" placeholder="Enter New Password" required>

    <button type="submit" name="change_password" class="btn">
        Change Password
    </button>

</form>

<!-- MESSAGE -->
<?php if($message != ""){ ?>
    <div class="<?php echo $type; ?>">
        <?php echo $message; ?>
    </div>
<?php } ?>

</div>

</body>
</html>
