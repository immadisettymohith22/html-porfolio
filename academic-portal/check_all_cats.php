<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");
$res = mysqli_query($conn, "SELECT event_name, category FROM events");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['event_name'] . " -> " . $row['category'] . "\n";
}
?>
