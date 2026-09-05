<?php
include("db.php");

$data = mysqli_query($conn,"
SELECT p.*, u.university_name 
FROM payments p
JOIN universities u ON p.university_id=u.id
ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Payments</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="glass">

<h2>💳 Payment History</h2>

<table>

<tr>
<th>University</th>
<th>Amount</th>
<th>Date</th>
<th>Next Due</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?php echo $row['university_name']; ?></td>
<td>₹<?php echo $row['amount']; ?></td>
<td><?php echo $row['payment_date']; ?></td>
<td><?php echo $row['next_due']; ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>
