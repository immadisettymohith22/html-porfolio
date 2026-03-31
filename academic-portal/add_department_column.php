<?php
include 'connect.php';

$sql = "ALTER TABLE students ADD COLUMN IF NOT EXISTS department VARCHAR(100) DEFAULT '' AFTER year";
if(mysqli_query($conn, $sql)){
    echo "<h2 style='color:green;font-family:sans-serif;'>✅ Done! 'department' column added to students table successfully.</h2>";
} else {
    echo "<h2 style='color:red;font-family:sans-serif;'>❌ Error: " . mysqli_error($conn) . "</h2>";
}
?>
