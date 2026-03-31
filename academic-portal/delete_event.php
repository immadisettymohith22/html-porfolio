<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'connect.php';

// Check if ID exists
if(!isset($_GET['id'])){
    header("Location: manage_events.php");
    exit();
}

$id = intval($_GET['id']); // secure id

mysqli_query($conn, "DELETE FROM events WHERE id=$id");

header("Location: manage_events.php");
exit();
?>