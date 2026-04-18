<?php
include 'db.php';

$service_id = $_GET['service_id'];

$result = $conn->query("
    SELECT * FROM stylists 
    WHERE service_id='$service_id'
");

echo "<option value=''>Select Stylist</option>";

while($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>