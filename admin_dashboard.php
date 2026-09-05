<?php
session_start();
include("db.php");

/* =========================
   FETCH SETTINGS (PRICE)
========================= */
$settings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM settings LIMIT 1"));
$price = $settings['subscription_price'] ?? 999;

/* =========================
   UPDATE PRICE
========================= */
if(isset($_POST['update_price'])){
    $new_price = floatval($_POST['price']);

    mysqli_query($conn,"
        UPDATE settings 
        SET subscription_price='$new_price' 
        WHERE id=1
    ");

    header("Location: admin_dashboard.php");
    exit();
}

/* =========================
   AUTO EXPIRE
========================= */
mysqli_query($conn,"
UPDATE universities 
SET subscription_status='Expired' 
WHERE subscription_end IS NOT NULL 
AND subscription_end < CURDATE()
");

/* =========================
   ACTIONS
========================= */

// DELETE
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM universities WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

// PAUSE
if(isset($_GET['pause'])){
    $id = intval($_GET['pause']);
    mysqli_query($conn,"UPDATE universities SET subscription_status='Paused' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

// RESUME
if(isset($_GET['resume'])){
    $id = intval($_GET['resume']);
    mysqli_query($conn,"UPDATE universities SET subscription_status='Active' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

// REMINDER
if(isset($_GET['remind'])){
    $id = intval($_GET['remind']);
    mysqli_query($conn,"
        INSERT INTO admin_notifications (university_id, message)
        VALUES ($id, 'Please renew your Smart UMS subscription.')
    ");
    header("Location: admin_dashboard.php");
    exit();
}

// PAYMENT
if(isset($_GET['pay'])){
    $id = intval($_GET['pay']);

    mysqli_query($conn,"
        INSERT INTO payments (university_id, amount, payment_date, next_due)
        VALUES ($id, '$price', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))
    ");

    mysqli_query($conn,"
        UPDATE universities 
        SET subscription_status='Active',
            subscription_end = DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        WHERE id=$id
    ");

    header("Location: admin_dashboard.php");
    exit();
}

/* =========================
   STATS
========================= */

$total_uni = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM universities
"))['total'] ?? 0;

$active = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM universities WHERE subscription_status='Active'
"))['total'] ?? 0;

$expired = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM universities WHERE subscription_status='Expired'
"))['total'] ?? 0;

$revenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total FROM payments WHERE status='Paid'
"))['total'] ?? 0;

$monthly = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total FROM payments 
WHERE MONTH(payment_date)=MONTH(CURDATE())
"))['total'] ?? 0;

$data = mysqli_query($conn,"SELECT * FROM universities ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style.css">

<style>

/* ===== STATS ===== */
.stats{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:25px;
}

.card{
    flex:1;
    min-width:180px;
    padding:20px;
    border-radius:20px;
    text-align:center;
    background:rgba(255,255,255,0.3);
    backdrop-filter:blur(20px);
}

/* ===== SETTINGS ===== */
.settings{
    padding:20px;
    border-radius:20px;
    margin-bottom:25px;
    background:rgba(255,255,255,0.3);
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-spacing:0 15px;
}

th{
    text-align:left;
    padding:10px;
}

td{
    padding:15px;
    background:rgba(255,255,255,0.25);
    border-radius:12px;
}

.actions a{
    margin-right:10px;
    text-decoration:none;
    font-size:18px;
}

/* ===== BADGES ===== */
.badge{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    color:#fff;
}

.active{ background:green; }
.expired{ background:red; }
.paused{ background:orange; }

/* ===== INPUT ===== */
input{
    padding:10px;
    border-radius:10px;
    border:none;
    margin-right:10px;
}

button{
    padding:10px 15px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    background:linear-gradient(45deg,#6a11cb,#2575fc);
    color:white;
}

</style>

</head>

<body>

<div class="glass">

<h2>🔐 Admin Dashboard</h2>

<!-- PRICE SETTINGS -->
<div class="settings">
<h3>💰 Subscription Price</h3>

<form method="POST">
<input type="number" name="price" value="<?php echo $price; ?>" required>
<button type="submit" name="update_price">Update</button>
</form>

</div>

<!-- STATS -->
<div class="stats">

<div class="card">
<h2><?php echo $total_uni; ?></h2>
<p>Total Universities</p>
</div>

<div class="card">
<h2><?php echo $active; ?></h2>
<p>Active</p>
</div>

<div class="card">
<h2><?php echo $expired; ?></h2>
<p>Expired</p>
</div>

<div class="card">
<h2>₹<?php echo $revenue; ?></h2>
<p>Total Revenue</p>
</div>

<div class="card">
<h2>₹<?php echo $monthly; ?></h2>
<p>This Month</p>
</div>

</div>

<!-- TABLE -->
<table>

<tr>
<th>University</th>
<th>Status</th>
<th>Expiry</th>
<th>Actions</th>
</tr>

<?php while($u=mysqli_fetch_assoc($data)){ ?>

<tr>

<td><?php echo $u['university_name']; ?></td>

<td>
<?php if($u['subscription_status']=='Active'){ ?>
<span class="badge active">Active</span>
<?php } elseif($u['subscription_status']=='Expired'){ ?>
<span class="badge expired">Expired</span>
<?php } else { ?>
<span class="badge paused">Paused</span>
<?php } ?>
</td>

<td><?php echo $u['subscription_end'] ?? 'N/A'; ?></td>

<td class="actions">

<a href="?pay=<?php echo $u['id']; ?>">💳</a>
<a href="?remind=<?php echo $u['id']; ?>">🔔</a>

<?php if($u['subscription_status']=='Paused'){ ?>
<a href="?resume=<?php echo $u['id']; ?>">▶️</a>
<?php } else { ?>
<a href="?pause=<?php echo $u['id']; ?>">⏸️</a>
<?php } ?>

<a href="?delete=<?php echo $u['id']; ?>" onclick="return confirm('Delete University?')">❌</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
