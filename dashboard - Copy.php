<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

// 📅 Today's date
$today = date("Y-m-d");

// 👥 Total customers today (FIXED: exclude Cancelled)
$result1 = $conn->query("
    SELECT COUNT(*) AS total_customers 
    FROM appointments 
    WHERE appointment_date = '$today'
    AND status != 'Cancelled'
");

$row1 = $result1->fetch_assoc();
$customers = $row1['total_customers'] ?? 0;

// 💰 Total revenue today (FIXED: only Approved)
$result2 = $conn->query("
    SELECT SUM(services.price) AS total_revenue
    FROM appointments
    JOIN services ON appointments.service_id = services.id
    WHERE appointment_date = '$today'
    AND appointments.status = 'Approved'
");

$row2 = $result2->fetch_assoc();
$revenue = $row2['total_revenue'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/salon_system/assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="d-flex">

    <!-- ✅ SIDEBAR -->
    <div class="bg-dark text-white p-3" style="width:250px; height:100vh; position:fixed;">
        <h4 class="text-center mb-4">💇 Salon system</h4>

        <a href="dashboard.php" class="btn btn-secondary w-100 text-start mb-2">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="appointments.php" class="btn btn-dark w-100 text-start mb-2">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>

        <a href="services.php" class="btn btn-dark w-100 text-start mb-2">
            <i class="bi bi-scissors"></i> Services
        </a>

        <a href="customers.php" class="btn btn-dark w-100 text-start mb-2">
            <i class="bi bi-people"></i> Customers
        </a>

        <a href="reports.php" class="btn btn-dark w-100 text-start mb-2">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
         <a href="stylists.php" class="btn btn-dark w-100 text-start mb-2">
            <i class="bi bi-bar-chart"></i> Stylists
        </a>
         <a href="walk_in_booking.php" class="btn btn-dark w-100 mt-2">
          🚶 Walk-in Booking
          </a>
        <a href="../logout.php" class="btn btn-danger w-100 mt-4">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <!-- ✅ MAIN CONTENT -->
    <div style="margin-left:250px; width:100%;">
        <div class="container mt-4">

            <h3 class="mb-4">📊 Today's Overview</h3>

            <div class="row">

                <!-- Customers -->
                <div class="col-md-6">
                    <div class="card shadow text-center p-4">
                        <h5><i class="bi bi-people-fill"></i> Customers Today</h5>
                        <h1 class="text-primary"><?= $customers ?></h1>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="col-md-6">
                    <div class="card shadow text-center p-4">
                        <h5><i class="bi bi-cash-stack"></i> Revenue Today</h5>
                        <h1 class="text-success">₱<?= $revenue ?></h1>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

</body>
</html> 