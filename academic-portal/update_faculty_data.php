<?php
include 'connect.php';

// 1. Add faculty_name column if it doesn't exist
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM events LIKE 'faculty_name'");
if(mysqli_num_rows($check_col) == 0){
    $alter_query = "ALTER TABLE events ADD COLUMN faculty_name VARCHAR(255) DEFAULT 'Not Assigned' AFTER event_name";
    if(mysqli_query($conn, $alter_query)){
        echo "Column 'faculty_name' added successfully.<br>";
    } else {
        echo "Error adding column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Column 'faculty_name' already exists.<br>";
}

// 2. Predefined list of random faculty names
$faculties = [
    "Dr. S. K. Sharma", "Prof. Amit Verma", "Dr. Meena Iyer", 
    "Dr. Rajesh Khanna", "Prof. Sunita Reddy", "Dr. Vikram Singh",
    "Prof. Anjali Gupta", "Dr. Rahul Deshmukh", "Prof. Priya Nair",
    "Dr. Manoj Kumar"
];

// 3. Update existing events with random names
$result = mysqli_query($conn, "SELECT id FROM events");
while($row = mysqli_fetch_assoc($result)){
    $random_faculty = $faculties[array_rand($faculties)];
    $id = $row['id'];
    mysqli_query($conn, "UPDATE events SET faculty_name = '$random_faculty' WHERE id = $id");
}

echo "All existing events updated with random faculty names.<br>";
?>
