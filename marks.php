<?php
session_start();
include("db.php");
include("auth_teacher.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$teacher_id   = $_SESSION['teacher_id'];
$principal_id = $_SESSION['principal_id'];

$message = "";

/* =========================
   FETCH BRANCHES
========================= */
$branches = mysqli_query($conn,
"SELECT id, branch_name FROM branches 
 WHERE principal_id='$principal_id'");

/* =========================
   SAVE MARKS
========================= */
if(isset($_POST['save_marks'])){

    $branch_id   = intval($_POST['branch_id']);
    $class_id    = intval($_POST['class_id']);
    $subject_id  = intval($_POST['subject_id']);
    $exam_name   = trim($_POST['exam_name']);
    $total_marks = intval($_POST['total_marks']);

    if(!$branch_id || !$class_id || !$subject_id){
        $message = "⚠️ Select all dropdowns!";
    }
    elseif(empty($exam_name) || $total_marks <= 0){
        $message = "⚠️ Enter valid exam name & total marks!";
    }
    else {

        foreach($_POST['marks'] as $student_id => $marks){

            if($marks === "") continue;

            $student_id = intval($student_id);
            $marks      = intval($marks);

            /* CHECK EXISTING */
            $check = mysqli_query($conn,"
            SELECT id FROM marks
            WHERE student_id='$student_id'
            AND subject_id='$subject_id'
            AND exam_name='$exam_name'
            AND principal_id='$principal_id'
            ");

            if(mysqli_num_rows($check) > 0){

                /* UPDATE */
                mysqli_query($conn,"
                UPDATE marks SET
                marks_obtained='$marks',
                total_marks='$total_marks'
                WHERE student_id='$student_id'
                AND subject_id='$subject_id'
                AND exam_name='$exam_name'
                AND principal_id='$principal_id'
                ");

            } else {

                /* INSERT */
                mysqli_query($conn,"
                INSERT INTO marks
                (student_id, teacher_id, principal_id, subject_id, exam_name, marks_obtained, total_marks)
                VALUES
                ('$student_id','$teacher_id','$principal_id','$subject_id','$exam_name','$marks','$total_marks')
                ");
            }
        }

        $message = "✅ Marks Saved Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Marks Entry</title>
<link rel="stylesheet" href="style.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#0a0a0f;
    min-height:100vh;
    padding:30px;
    color:white;
    overflow-x:hidden;
}

/* Background Glow */

body::before{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:#7c3aed;
    filter:blur(220px);
    opacity:.15;
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
    opacity:.15;
    bottom:-150px;
    right:-150px;
    z-index:-1;
}

/* Main Container */

.glass{
    max-width:1000px;
    margin:40px auto;
    padding:40px;
    background:rgba(15,23,42,0.85);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:30px;
}

/* Header */

.page-header{
    margin-bottom:30px;
}

.back{
    color:#ffffff;
    text-decoration:none;
    font-size:16px;
    font-weight:500;
    display:inline-block;
    margin-bottom:20px;
    opacity:.9;
}

.back:hover{
    opacity:1;
}

h2{
    font-size:38px;
    font-weight:700;
    margin-bottom:10px;

    background:linear-gradient(
        135deg,
        #ffffff,
        #60a5fa
    );

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* Message */

.message{
    padding:14px 18px;
    border-radius:14px;
    background:rgba(34,197,94,.15);
    border:1px solid rgba(34,197,94,.25);
    color:#4ade80;
    margin-bottom:20px;
    font-weight:600;
}

/* Form Card */

.form-card{
    margin-top:20px;
}

/* Inputs */

select,
input{
    width:100%;
    height:55px;
    padding:12px 18px;
    margin-bottom:15px;

    border-radius:16px;
    border:1px solid rgba(255,255,255,.05);

    background:#111827;
    color:#ffffff;

    font-size:15px;
    outline:none;

    transition:.3s;
}

select:focus,
input:focus{
    border-color:#7c3aed;
    box-shadow:0 0 15px rgba(124,58,237,.25);
}

select option{
    background:#111827;
    color:white;
}

input::placeholder{
    color:#94a3b8;
}

/* Divider */

hr{
    border:none;
    height:1px;
    background:rgba(255,255,255,.1);
    margin:25px 0;
}

/* Table */

.table-card{
    overflow-x:auto;
    margin-top:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111827;
    color:#60a5fa;
    padding:16px;
    text-align:left;
    font-size:15px;
}

td{
    background:#0f172a;
    color:#ffffff;
    padding:16px;
    border-top:1px solid rgba(255,255,255,.05);
}

tr:hover td{
    background:#131f36;
}

td input{
    margin:0;
}

/* Save Button */

.save-btn,
button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:16px;

    background:linear-gradient(
        135deg,
        #7c3aed,
        #2563eb
    );

    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;

    transition:.3s;
}

.save-btn:hover,
button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(124,58,237,.35);
}

/* No Data */

.no-data{
    text-align:center;
    padding:25px;
    border-radius:18px;
    background:#111827;
    color:#94a3b8;
    margin-top:20px;
}

/* Mobile */

@media(max-width:768px){

    body{
        padding:15px;
    }

    .glass{
        padding:25px;
    }

    h2{
        font-size:30px;
    }

    table{
        min-width:600px;
    }

}

</style>
</head>

<body>

<div class="glass">

    <a href="teacher_dashboard.php" class="back">
        ← Back
    </a>

    <h2>📝 Marks Entry</h2>

    <?php if($message){ ?>
        <div class="message">
            <?= $message ?>
        </div>
    <?php } ?>

    <div class="form-card">

        <form method="POST">

            <!-- Branch -->

            <select
                name="branch_id"
                required
                onchange="this.form.submit()">

                <option value="">
                    Select Branch
                </option>

                <?php while($b=mysqli_fetch_assoc($branches)){ ?>

                <option
                    value="<?= $b['id'] ?>"
                    <?= (isset($_POST['branch_id']) && $_POST['branch_id']==$b['id']) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($b['branch_name']) ?>

                </option>

                <?php } ?>

            </select>

            <!-- Class -->

            <select
                name="class_id"
                required
                onchange="this.form.submit()">

                <option value="">
                    Select Class
                </option>

                <?php

                if(isset($_POST['branch_id'])){

                    $class = mysqli_query(
                        $conn,
                        "SELECT id,class_name,batch
                         FROM classes
                         WHERE branch_id='".intval($_POST['branch_id'])."'"
                    );

                    while($c=mysqli_fetch_assoc($class)){

                ?>

                <option
                    value="<?= $c['id'] ?>"
                    <?= (isset($_POST['class_id']) && $_POST['class_id']==$c['id']) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($c['class_name']) ?>
                    (<?= htmlspecialchars($c['batch']) ?>)

                </option>

                <?php
                    }
                }
                ?>

            </select>

            <!-- Subject -->

            <select name="subject_id" required>

                <option value="">
                    Select Subject
                </option>

                <?php

                $subjects = mysqli_query(
                    $conn,
                    "SELECT * FROM subjects
                     WHERE teacher_id='$teacher_id'"
                );

                while($s=mysqli_fetch_assoc($subjects)){

                ?>

                <option value="<?= $s['id'] ?>">

                    <?= htmlspecialchars($s['subject_name']) ?>

                </option>

                <?php } ?>

            </select>

            <!-- Exam -->

            <input
                type="text"
                name="exam_name"
                placeholder="Exam Name (Mid Term)"
                required>

            <!-- Total Marks -->

            <input
                type="number"
                name="total_marks"
                placeholder="Total Marks"
                required>

            <hr>

            <?php

            if(isset($_POST['class_id'])){

                $students = mysqli_query(
                    $conn,
                    "SELECT id,name,roll_no
                     FROM students
                     WHERE class_id='".intval($_POST['class_id'])."'"
                );

                if(mysqli_num_rows($students) > 0){

            ?>

            <div class="table-card">

                <table>

                    <tr>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Marks</th>
                    </tr>

                    <?php while($stu=mysqli_fetch_assoc($students)){ ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($stu['name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($stu['roll_no']) ?>
                        </td>

                        <td>

                            <input
                                type="number"
                                name="marks[<?= $stu['id'] ?>]"
                                placeholder="Enter Marks">

                        </td>

                    </tr>

                    <?php } ?>

                </table>

            </div>

            <br>

            <button
                type="submit"
                name="save_marks"
                class="save-btn">

                💾 Save Marks

            </button>

            <?php

                }else{

            ?>

            <div class="no-data">
                No Students Found
            </div>

            <?php
                }
            }
            ?>

        </form>

    </div>

</div>

</body>
</html>
