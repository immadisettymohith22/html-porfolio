<?php
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$db1 = "academic-portal";
$db2 = "academic_portal";

if (mysqli_query($conn, "DROP DATABASE IF EXISTS `$db1`")) {
    echo "Database $db1 dropped successfully\n";
} else {
    echo "Error dropping $db1: " . mysqli_error($conn) . "\n";
}

if (mysqli_query($conn, "DROP DATABASE IF EXISTS `$db2`")) {
    echo "Database $db2 dropped successfully\n";
} else {
    echo "Error dropping $db2: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>
