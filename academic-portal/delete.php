<?php
include 'connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM registrations WHERE id=$id";

if(mysqli_query($conn, $sql)){
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin_dashboard.php?section=registrations';
    header("Location: " . $referer);
} else {
    echo "Error deleting record";
}
?>