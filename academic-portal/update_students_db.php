<?php
$conn = mysqli_connect("localhost", "root", "", "academic_portal");
if(!$conn) die("Connection Failed");

$queries = [
    "ALTER TABLE students ADD COLUMN mobile VARCHAR(15) AFTER email",
    "ALTER TABLE students ADD COLUMN year VARCHAR(10) AFTER mobile"
];

foreach ($queries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "Success: $query\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}
?>
