<?php
session_start();
include("db.php");
include("auth_principal.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // DELETE BRANCH (CASCADE will auto delete classes & students)
    mysqli_query($conn, "DELETE FROM branches WHERE id='$id'");

    header("Location: view_branches.php");
}
?>
