<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "academic_portal";

// Connect to MySQL
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
if (mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db_name")) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "\n";
}

// Select the database
mysqli_select_db($conn, $db_name);

// Read the SQL file
$sql = file_get_contents('database.sql');

// Execute multi-query
if (mysqli_multi_query($conn, $sql)) {
    do {
        // Store first result set
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    echo "Tables and data imported successfully from database.sql\n";
} else {
    echo "Error importing SQL: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>
