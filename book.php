<?php
session_start();
include 'db.php';
include 'includes/header.php'; 

// 🔐 CHECK LOGIN FIRST
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$customer_name = $_SESSION['name'];

$success = "";
$notif = "";

/* BOOK APPOINTMENT */
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

        $success = "Appointment booked successfully!";
    } else {
        $notif = "All fields are required!";
    }
}
?>