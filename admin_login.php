<?php
session_start();

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "admin123"){
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid login!";
    }
}
?>

<form method="POST">
<h2>Admin Login</h2>

<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

<?php if(isset($error)) echo "<p>$error</p>"; ?>
</form>
