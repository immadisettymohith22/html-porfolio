<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");

$query = "ALTER TABLE events ADD COLUMN event_end_time VARCHAR(100) AFTER event_time";

if (mysqli_query($conn, $query)) {
    echo "Success: Added event_end_time column.\n";
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}
?>
