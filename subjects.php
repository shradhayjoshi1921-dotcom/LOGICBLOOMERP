<?php
session_start();
include("db.php");
include("auth_teacher.php");

$teacher_id   = $_SESSION['teacher_id'];
$principal_id = $_SESSION['principal_id'];

$message = "";
$type = "";

/* ================= FETCH BRANCHES ================= */
$branches = mysqli_query($conn,
"SELECT * FROM branches WHERE principal_id='$principal_id'"
);

/* ================= ADD SUBJECT ================= */
if(isset($_POST['add_subject'])){

    $subject_name = trim($_POST['subject_name']);
    $class_id     = $_POST['class_id'];

    if(empty($subject_name) || empty($class_id)){
        $message = "⚠️ Fill all fields!";
        $type = "error";
    } else {

        // ❗ Prevent duplicate per class
        $check = mysqli_query($conn,"
        SELECT id FROM subjects 
        WHERE subject_name='$subject_name'
        AND teacher_id='$teacher_id'
        AND class_id='$class_id'
        ");

        if(mysqli_num_rows($check) > 0){
            $message = "Subject already exists in this class!";
            $type = "error";
        } else {

            mysqli_query($conn,"
            INSERT INTO subjects 
            (subject_name, teacher_id, principal_id, class_id)
            VALUES 
            ('$subject_name','$teacher_id','$principal_id','$class_id')
            ");

            $message = "✅ Subject Added!";
            $type = "success";
        }
    }
}

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    mysqli_query($conn,"
    DELETE FROM subjects 
    WHERE id='$id' AND teacher_id='$teacher_id'
    ");

    header("Location: subject.php");
    exit();
}

/* ================= FETCH SUBJECTS ================= */
$subjects = mysqli_query($conn,"
SELECT s.*, c.class_name 
FROM subjects s
LEFT JOIN classes c ON s.class_id = c.id
WHERE s.teacher_id='$teacher_id'
ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Subjects</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<a href="teacher_dashboard.php" class="back">← Back</a>

<h2>📚 Manage Subjects</h2>

<form method="POST">

<!-- BRANCH -->
<select name="branch_id" required onchange="this.form.submit()">
<option value="">Select Branch</option>

<?php while($b=mysqli_fetch_assoc($branches)){ ?>
<option value="<?= $b['id'] ?>"
<?= (isset($_POST['branch_id']) && $_POST['branch_id']==$b['id'])?'selected':'' ?>>
<?= $b['branch_name'] ?>
</option>
<?php } ?>
</select>

<!-- CLASS -->
<select name="class_id" required>
<option value="">Select Class</option>

<?php
if(isset($_POST['branch_id'])){
$class = mysqli_query($conn,"
SELECT * FROM classes WHERE branch_id='".$_POST['branch_id']."'
");

while($c=mysqli_fetch_assoc($class)){
?>
<option value="<?= $c['id'] ?>">
<?= $c['class_name'] ?> (<?= $c['batch'] ?>)
</option>
<?php } } ?>
</select>

<input type="text" name="subject_name" placeholder="Enter Subject Name" required>

<button type="submit" name="add_subject" class="btn">➕ Add Subject</button>

</form>

<?php if($message){ ?>
<p style="color:<?= $type=='error'?'red':'green' ?>;font-weight:bold;">
<?= $message ?>
</p>
<?php } ?>

<hr style="margin:30px 0;">

<h3>Your Subjects</h3>

<table>
<tr>
<th>ID</th>
<th>Subject</th>
<th>Class</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($subjects)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['subject_name'] ?></td>
<td><?= $row['class_name'] ?? 'N/A' ?></td>
<td>
<a href="?delete=<?= $row['id'] ?>"
onclick="return confirm('Delete subject?')"
class="btn"
style="background:linear-gradient(135deg,#ff6b6b,#ff4e4e);">
Delete
</a>
</td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
