<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include("db.php");
include("auth_principal.php");

$principal_id = $_SESSION['principal_id'];

$message = "";
$type = "";

/* ================= ADD SALARY ================= */

if(isset($_POST['pay_salary'])){

    $teacher_id = $_POST['teacher_id'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];
    $date = $_POST['payment_date'];

    $insert = mysqli_query($conn, "
        INSERT INTO salary_payments (teacher_id,principal_id, amount, month, payment_date)
        VALUES ('$teacher_id','$principal_id','$amount','$month','$date')
    ");

    if($insert){

        /* 🔔 NOTIFICATION */
        $msg = "Salary of ₹$amount for $month has been credited";

        mysqli_query($conn, "
            INSERT INTO notifications (teacher_id, message)
            VALUES ('$teacher_id', '$msg')
        ");

        $message = "Salary Paid Successfully ✅";
        $type = "success";

    } else {
        $message = "Error in Payment!";
        $type = "error";
    }
}

/* ================= FETCH TEACHERS ================= */

$teachers = mysqli_query($conn, "
    SELECT * FROM teachers 
    WHERE principal_id='$principal_id' AND is_active=1
");

/* ================= FETCH HISTORY ================= */

$history = mysqli_query($conn, "
    SELECT sp.*, t.name 
    FROM salary_payments sp
    JOIN teachers t ON sp.teacher_id = t.id
    WHERE t.principal_id='$principal_id'
    ORDER BY sp.payment_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Salary Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<a href="principal_dashboard.php" class="back">← Back</a>

<h2>💰 Salary Management</h2>

<!-- ================= PAY SALARY ================= -->

<form method="POST">

<select name="teacher_id" required>
<option value="">Select Teacher</option>
<?php while($t = mysqli_fetch_assoc($teachers)){ ?>
<option value="<?php echo $t['id']; ?>">
    <?php echo $t['name']; ?>
</option>
<?php } ?>
</select>

<input type="number" name="amount" placeholder="Enter Amount" required>

<input type="text" name="month" placeholder="Month (e.g. March)" required>

<input type="date" name="payment_date" required>

<button type="submit" name="pay_salary" class="btn">Pay Salary</button>

</form>

<!-- MESSAGE -->
<?php if($message != ""){ ?>
<div class="<?php echo $type; ?>">
    <?php echo $message; ?>
</div>
<?php } ?>

<hr style="margin:40px 0; opacity:0.3;">

<!-- ================= HISTORY ================= -->

<h3>📜 Salary History</h3>

<table>
<tr>
<th>Teacher</th>
<th>Amount</th>
<th>Month</th>
<th>Date</th>
</tr>

<?php while($h = mysqli_fetch_assoc($history)){ ?>
<tr>
<td><?php echo $h['name']; ?></td>
<td>₹<?php echo $h['amount']; ?></td>
<td><?php echo $h['month']; ?></td>
<td><?php echo $h['payment_date']; ?></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
