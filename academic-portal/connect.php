<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// Auto-migration: Check for rollno in students table
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM students LIKE 'rollno'");
if(mysqli_num_rows($check_col) == 0){
    mysqli_query($conn, "ALTER TABLE students ADD COLUMN rollno VARCHAR(50) AFTER name");
}

// Auto-migration: Check for department in students table
$check_dept = mysqli_query($conn, "SHOW COLUMNS FROM students LIKE 'department'");
if(mysqli_num_rows($check_dept) == 0){
    mysqli_query($conn, "ALTER TABLE students ADD COLUMN department VARCHAR(100) NOT NULL DEFAULT '' AFTER year");
}
?>