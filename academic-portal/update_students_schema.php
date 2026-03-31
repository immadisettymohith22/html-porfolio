<?php
include 'connect.php';

$sql = "ALTER TABLE students ADD COLUMN rollno VARCHAR(50) AFTER name";

if (mysqli_query($conn, $sql)) {
    echo "Column 'rollno' added successfully to 'students' table.";
} else {
    echo "Error adding column: " . mysqli_error($conn);
}
?>
