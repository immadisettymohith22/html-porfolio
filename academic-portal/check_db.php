<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");
$res = mysqli_query($conn, "DESCRIBE students");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
