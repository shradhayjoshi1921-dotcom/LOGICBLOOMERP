<?php
session_start();
include("db.php");

$teacher_id = $_SESSION['teacher_id'];
$university_id = $_SESSION['university_id'];

$date = $_POST['date'];
$class_id = $_POST['class_id'];
$subject_id = $_POST['subject_id'];

foreach($_POST['status'] as $student_id => $status){

    $check = mysqli_query($conn,"
    SELECT id FROM attendance 
    WHERE student_id='$student_id' 
    AND subject_id='$subject_id'
    AND attendance_date='$date'
    ");

    if(mysqli_num_rows($check)==0){

        mysqli_query($conn,"
        INSERT INTO attendance 
        (student_id, teacher_id, class_id, subject_id, university_id, status, attendance_date)
        VALUES
        ('$student_id','$teacher_id','$class_id','$subject_id','$university_id','$status','$date')
        ");
    }
}

header("Location: attendance.php");
