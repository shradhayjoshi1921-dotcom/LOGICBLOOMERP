<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");
include("auth_teacher.php");

$teacher_id   = $_SESSION['teacher_id'];
$principal_id = $_SESSION['principal_id'];

$message = "";
$type = "";

/* ================= ADD SUBJECT ================= */
if(isset($_POST['add_subject'])){

    $subject_name = trim($_POST['subject_name']);

    if(empty($subject_name)){
        $message = "⚠️ Subject name required!";
        $type = "error";
    } else {

        // SAFE prepared statement (no SQL injection)
        $check = $conn->prepare("
            SELECT id FROM subjects 
            WHERE subject_name=? AND teacher_id=?
        ");
        $check->bind_param("si", $subject_name, $teacher_id);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $message = "❌ Subject already exists!";
            $type = "error";
        } else {

            $stmt = $conn->prepare("
                INSERT INTO subjects (subject_name, teacher_id, principal_id) 
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sii", $subject_name, $teacher_id, $principal_id);

            if($stmt->execute()){
                $message = "✅ Subject Added Successfully!";
                $type = "success";
            } else {
                $message = "❌ Database Error!";
                $type = "error";
            }
        }
    }
}

/* ================= DELETE SUBJECT ================= */
if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("
        DELETE FROM subjects 
        WHERE id=? AND teacher_id=?
    ");
    $stmt->bind_param("ii", $id, $teacher_id);
    $stmt->execute();

    header("Location: subject.php");
    exit();
}

/* ================= FETCH SUBJECTS ================= */
$stmt = $conn->prepare("
    SELECT * FROM subjects 
    WHERE teacher_id=? 
    ORDER BY id DESC
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$subjects = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Subjects | Logic Bloom ERP</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#0a0a0f;
    color:white;
    min-height:100vh;
    padding:25px;
    overflow-x:hidden;
}

/* Glow Effects */

body::before{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:#7c3aed;
    filter:blur(220px);
    opacity:.12;
    top:-150px;
    left:-150px;
    z-index:-1;
}

body::after{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:#2563eb;
    filter:blur(220px);
    opacity:.12;
    bottom:-150px;
    right:-150px;
    z-index:-1;
}

/* Main Container */

.glass{
    max-width:1200px;
    margin:auto;
}

/* Back Button */

.back{
    color:#9ca3af;
    text-decoration:none;
    display:inline-block;
    margin-bottom:20px;
    font-size:15px;
}

.back:hover{
    color:white;
}

/* Heading */

h2{
    font-size:34px;
    margin-bottom:25px;
}

h3{
    margin-bottom:20px;
}

/* Add Subject Card */

.subject-form{
    background:#111827;
    padding:25px;
    border-radius:24px;
    margin-bottom:25px;
    border:1px solid rgba(255,255,255,.05);
}

.subject-form form{
    display:flex;
    gap:15px;
}

.subject-form input{
    flex:1;
    padding:16px;
    border:none;
    outline:none;
    border-radius:14px;
    background:#0f172a;
    color:white;
    font-size:15px;
}

.subject-form input::placeholder{
    color:#94a3b8;
}

/* Buttons */

.btn{
    border:none;
    cursor:pointer;
    text-decoration:none;
    display:inline-block;
    padding:14px 22px;
    border-radius:14px;
    color:white;
    font-weight:600;
    background:linear-gradient(
        135deg,
        #7c3aed,
        #2563eb
    );
}

.delete-btn{
    background:linear-gradient(
        135deg,
        #ef4444,
        #dc2626
    );
}

/* Message */

.message{
    margin-bottom:25px;
    padding:14px;
    border-radius:12px;
    font-weight:600;
}

.success{
    background:rgba(34,197,94,.15);
    color:#22c55e;
}

.error{
    background:rgba(239,68,68,.15);
    color:#ef4444;
}

/* Table Card */

.table-card{
    background:#111827;
    padding:25px;
    border-radius:24px;
    border:1px solid rgba(255,255,255,.05);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    text-align:left;
    padding:18px;
    color:#60a5fa;
    border-bottom:1px solid rgba(255,255,255,.08);
}

td{
    padding:18px;
    border-bottom:1px solid rgba(255,255,255,.05);
}

tr:hover{
    background:rgba(255,255,255,.02);
}

/* Mobile */

@media(max-width:768px){

    body{
        padding:15px;
    }

    h2{
        font-size:26px;
    }

    .subject-form form{
        flex-direction:column;
    }

    .btn{
        width:100%;
        text-align:center;
    }

}

</style>

</head>

<body>

<div class="glass">

    <a href="teacher_dashboard.php" class="back">
        ← Back
    </a>

    <h2>📚 Manage Subjects</h2>

    <div class="subject-form">

        <form method="POST">

            <input
                type="text"
                name="subject_name"
                placeholder="Enter Subject Name"
                required>

            <button
                type="submit"
                name="add_subject"
                class="btn">

                ➕ Add Subject

            </button>

        </form>

    </div>

    <?php if($message != ""){ ?>

        <div class="message <?= ($type=='error' ? 'error' : 'success') ?>">

            <?= $message ?>

        </div>

    <?php } ?>

    <div class="table-card">

        <h3>📚 Your Subjects</h3>

        <table>

            <tr>
                <th>ID</th>
                <th>Subject Name</th>
                <th>Action</th>
            </tr>

            <?php while($row = $subjects->fetch_assoc()){ ?>

            <tr>

                <td><?= $row['id']; ?></td>

                <td>
                    <?= htmlspecialchars($row['subject_name']); ?>
                </td>

                <td>

                    <a
                    href="?delete=<?= $row['id']; ?>"
                    onclick="return confirm('Delete this subject?')"
                    class="btn delete-btn">

                    Delete

                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>
