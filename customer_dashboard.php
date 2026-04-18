<?php 
session_start();
include 'db.php';  

// 🔐 SESSION CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$customer_name = $_SESSION['name'];

$success = "";
$notif = "";

/* =========================
   🔔 NOTIFICATION SYSTEM (FIXED)
========================= */
$checkNotif = $conn->query("
    SELECT * FROM appointments 
    WHERE user_id='$user_id'
    AND status='Approved'
    AND notif_seen=0
    ORDER BY id DESC
    LIMIT 1
");

if ($checkNotif && $checkNotif->num_rows > 0) {
    $rowNotif = $checkNotif->fetch_assoc();

    $notif = "🎉 Your appointment on " . $rowNotif['appointment_date'] . " has been APPROVED!";

    $conn->query("UPDATE appointments SET notif_seen=1 WHERE id=".$rowNotif['id']);
}

/* =========================
   📌 BOOK APPOINTMENT (FIXED REFRESH)
========================= */
if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $service = $_POST['service'];
    $stylist = $_POST['stylist'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (!empty($name) && !empty($service) && !empty($stylist) && !empty($date) && !empty($time)) {

        $conn->query("INSERT INTO appointments 
        (user_id, customer_name, service_id, stylist_id, appointment_date, appointment_time, status)
        VALUES 
        ('$user_id','$name','$service','$stylist','$date','$time','Pending')");

        header("Location: customer_dashboard.php?msg=booked");
        exit();

    } else {
        $notif = "All fields are required!";
    }
}

/* =========================
   ❌ CANCEL BOOKING
========================= */
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];

    $conn->query("UPDATE appointments SET status='Cancelled' WHERE id='$id'");

    header("Location: customer_dashboard.php?msg=cancel");
    exit();
}

/* =========================
   MESSAGES
========================= */
if (isset($_GET['msg'])) {

    if ($_GET['msg'] == "booked") {
        $success = "Appointment booked successfully!";
    }

    if ($_GET['msg'] == "cancel") {
        $notif = "Booking cancelled successfully!";
    }
}
?>

<!-- 💇 SALON STYLE ONLY -->
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #ffe6f0, #fff0f5, #e6f7ff);
    background-size: 400% 400%;
    animation: bgMove 10s ease infinite;
}

@keyframes bgMove {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

.container {
    width: 92%;
    margin: auto;
}

.row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    padding: 22px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.col-md-5 { flex: 1; min-width: 320px; }
.col-md-7 { flex: 2; min-width: 320px; }

input, select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 10px;
    border: 1px solid #ddd;
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #ff4d6d;
    box-shadow: 0 0 10px rgba(255,77,109,0.3);
}

button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(45deg, #ff4d6d, #ff8fa3);
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.03);
    box-shadow: 0 5px 15px rgba(255,77,109,0.4);
}

.alert {
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
}

.success {
    background: #d4edda;
    color: #155724;
}

.error {
    background: #f8d7da;
    color: #721c24;
}

.title {
    text-align: center;
    font-size: 28px;
    margin: 20px 0;
    font-weight: bold;
    color: #333;
}

.logout {
    text-align: right;
    margin: 10px 0;
}

.logout a {
    background: linear-gradient(45deg, #ff4d6d, #ff6b6b);
    color: white;
    padding: 10px 18px;
    text-decoration: none;
    border-radius: 10px;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: #ff4d6d;
    color: white;
    padding: 10px;
}

td {
    background: white;
    padding: 10px;
    text-align: center;
}

tr:hover td {
    background: #fff0f5;
}

#serviceName {
    color: #ff4d6d;
    font-weight: bold;
}

#servicePrice {
    color: #28a745;
    font-weight: bold;
}
</style>

<!-- LOGOUT -->
<div class="logout">
    <a href="logout.php">Logout</a>
</div>

<div class="title">💇 Customer Dashboard</div>

<?php if (!empty($notif)): ?>
    <div class="alert error"><?= $notif ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert success"><?= $success ?></div>
<?php endif; ?>

<div class="container">
<div class="row">

<!-- BOOKING -->
<div class="col-md-5">
<div class="card">

<h3>Book Appointment</h3>

<form method="POST">

<label>Full Name</label>
<input type="text" name="name" value="<?= $customer_name ?>" required>

<label>Service</label>
<select name="service" required>
    <option value="">Select Service</option>
    <?php
    $services = $conn->query("SELECT * FROM services");
    while($s = $services->fetch_assoc()):
    ?>
        <option value="<?= $s['id'] ?>">
            <?= $s['service_name'] ?>
        </option>
    <?php endwhile; ?>
</select>

<label>Stylist</label>
<select name="stylist" required>
    <option value="">Select Stylist</option>
    <?php
    $stylists = $conn->query("SELECT * FROM stylists");
    while($st = $stylists->fetch_assoc()):
    ?>
        <option value="<?= $st['id'] ?>">
            <?= $st['name'] ?>
        </option>
    <?php endwhile; ?>
</select>

<label>Date</label>
<input type="date" name="date" required>

<label>Time</label>
<input type="time" name="time" required>

<button name="submit">Book Now</button>

</form>

</div>
</div>

<!-- APPOINTMENTS -->
<div class="col-md-7">
<div class="card">

<h3>My Appointments</h3>

<table>
<tr>
<th>Service</th>
<th>Stylist</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$appointments = $conn->query("
SELECT a.*, s.service_name, s.price, st.name AS stylist_name
FROM appointments a
JOIN services s ON a.service_id = s.id
JOIN stylists st ON a.stylist_id = st.id
WHERE a.user_id='$user_id'
ORDER BY a.id DESC
");

while($row = $appointments->fetch_assoc()):
?>

<tr>
<td><?= $row['service_name'] ?></td>
<td><?= $row['stylist_name'] ?></td>
<td><?= $row['appointment_date'] ?></td>
<td><?= $row['appointment_time'] ?></td>

<td>
<span style="padding:6px;border-radius:8px;color:white;
background:
<?= $row['status']=='Pending'?'orange':($row['status']=='Approved'?'green':'red') ?>">
<?= $row['status'] ?>
</span>
</td>

<td>
<?php if ($row['status'] != 'Cancelled'): ?>
<a href="?cancel=<?= $row['id'] ?>" style="color:red;">Cancel</a>
<?php else: ?>
—
<?php endif; ?>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>
</div>

</div>
</div>