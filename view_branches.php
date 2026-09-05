<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

/* FETCH BRANCHES */
$branches = mysqli_query($conn,
    "SELECT * FROM branches WHERE principal_id='$principal_id'"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Branches</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<!-- TOP BAR -->
<div style="display:flex; justify-content:space-between; align-items:center;">
    <a href="principal_dashboard.php" class="back-link">← Back</a>
    <a href="logout.php" class="btn">Logout</a>
</div>

<h2 style="margin-top:10px;">🏫 All Branches</h2>

<hr style="margin:20px 0; opacity:0.3;">

<div class="card-container">

<?php while($row = mysqli_fetch_assoc($branches)) {

    $branch_id = $row['id'];

    /* COUNT STUDENTS IN THIS BRANCH */
    $count = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS total FROM students WHERE branch_id='$branch_id'"
    ))['total'];
?>

    <div class="card">

        <h3><?php echo $row['branch_name']; ?></h3>

        <p>👨‍🎓 Students: <?php echo $count; ?></p>

        <br>

        <!-- ACTION BUTTONS -->
        <div style="display:flex; gap:10px; justify-content:center;">

            <a href="edit_branch.php?id=<?php echo $branch_id; ?>" class="btn">
                ✏️ Edit
            </a>

            <button onclick="deleteBranch(<?php echo $branch_id; ?>)"
            class="btn" style="background:#ff4d4d;">
                🗑️ Delete
            </button>

        </div>

    </div>

<?php } ?>

</div>

</div>

<!-- DOUBLE CONFIRM DELETE -->
<script>
function deleteBranch(id) {
    if(confirm("⚠️ Are you sure you want to delete this branch?")) {
        if(confirm("🚨 This will delete ALL related classes & students!\nAre you absolutely sure?")) {
            window.location.href = "delete_branch.php?id=" + id;
        }
    }
}
</script>

</body>
</html>
