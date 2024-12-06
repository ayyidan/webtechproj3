<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "web_technology_class";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available time slots
$sql = "SELECT slot_id, date_time, available_seats FROM time_slots WHERE available_seats > 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slotId = $row['slot_id'];
        $dateTime = $row['date_time'];
        $seats = $row['available_seats'];
        echo "<option value='$slotId'>$dateTime ($seats seats remaining)</option>";
    }
} else {
    echo "<option value='' disabled>No available time slots</option>";
}

$conn->close();
?>
