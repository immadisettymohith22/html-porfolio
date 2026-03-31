<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<div class="navbar">
    <div class="logo">
        <i class="fas fa-user-shield"></i> Admin Dashboard
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            Welcome, Admin 👋
        </span>
    </div>
    <div class="nav-links">
        <a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<h1 class="title" style="margin-top: 2rem;">Registered Students</h1>

<div class="table-container">

<table border="1" width="80%" align="center" cellpadding="10">
    <tr style="background-color: #4e73df; color: white;">
        <th>ID</th>
        <th>Name</th>
        <th>Roll No</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Department</th>
        <th>Event</th>
        <th>Action</th>
    </tr>

<?php
$sql = "SELECT * FROM registrations";
$result = mysqli_query($conn, $sql);
$serial_no = 1;

while($row = mysqli_fetch_assoc($result)){
    echo "<tr>";
    echo "<td>".$serial_no++."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['rollno']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>".$row['mobile']."</td>";
    echo "<td>".$row['department']."</td>";
    echo "<td>".$row['event']."</td>";
    echo "<td>
            <a href='delete.php?id=".$row['id']."'>
                <button style='background:red;'>Delete</button>
            </a>
          </td>";
    echo "</tr>";
}
?>

</table>

</body>
</html>