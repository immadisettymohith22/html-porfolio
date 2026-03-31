<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");
$res = mysqli_query($conn, "DESCRIBE events");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
