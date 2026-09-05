<?php
session_start();
include("db.php");
include("auth_principal.php");

$id = $_GET['id'];

// FETCH OLD DATA
$data = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM branches WHERE id='$id'"
));

if(isset($_POST['update'])){
    $name = $_POST['branch_name'];

    mysqli_query($conn,
        "UPDATE branches SET branch_name='$name' WHERE id='$id'"
    );

    header("Location: view_branches.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Branch</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<h2>Edit Branch</h2>

<form method="POST">

<input type="text" name="branch_name"
value="<?php echo $data['branch_name']; ?>" required>

<br><br>

<button type="submit" name="update" class="btn">Update</button>

</form>

</div>

</body>
</html>
