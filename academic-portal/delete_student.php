<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
include 'connect.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Optional: Fetch email to delete registrations too
    $student_query = mysqli_query($conn, "SELECT email FROM students WHERE id='$id'");
    if($student_data = mysqli_fetch_assoc($student_query)){
        $email = $student_data['email'];
        // Remove registrations
        mysqli_query($conn, "DELETE FROM registrations WHERE email='$email'");
    }

    // Delete student account
    mysqli_query($conn, "DELETE FROM students WHERE id='$id'");
    
    echo "<script>alert('Student Account Deleted Successfully'); window.location.href='admin_dashboard.php?section=students';</script>";
}
?>
