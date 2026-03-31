<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");

$query = "ALTER TABLE events ADD COLUMN category VARCHAR(50) AFTER event_name";

if (mysqli_query($conn, $query)) {
    echo "Success: Added category column to events table.\n";
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}
?>
