<?php
include 'db.php';

$stylist_id = $_GET['stylist_id'];

/* Example schedule (you can edit this later from DB) */
$schedules = [
    "8:00 AM - 9:00 AM",
    "11:00 AM - 12:00 PM",
    "2:00 PM - 3:00 PM"
];

foreach ($schedules as $time) {
    echo "<button type='button' class='time-btn' value='$time'>$time</button>";
}
?>