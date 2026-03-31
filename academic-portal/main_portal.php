<?php
session_start();
include 'connect.php';

/* -------------------------
   ADMIN LOGIN
------------------------- */
if(isset($_POST['admin_login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "1234"){
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Admin Login');</script>";
    }
}

/* -------------------------
   STUDENT LOGIN
------------------------- */
if(isset($_POST['student_login'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM students 
    WHERE email='$email' AND password='$password'");

    if(mysqli_num_rows($result) > 0){

        $student = mysqli_fetch_assoc($result);
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_email'] = $student['email'];

        header("Location: events.php");
        exit();

    } else {
        echo "<script>alert('Invalid Student Login');</script>";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Portal | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<h1 class="title">Academic Event Portal</h1>

<div class="dashboard-container">

    <!-- STUDENT LOGIN -->
    <div class="glass-card auth-card">
        <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="student@example.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="student_login" class="btn" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem;">
            <a href="student_login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">New Student? Register</a>
        </p>
    </div>

    <!-- ADMIN LOGIN -->
    <div class="glass-card auth-card">
        <h2><i class="fas fa-user-shield"></i> Admin Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="admin" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="admin_login" class="btn" style="width: 100%;">Login</button>
        </form>
    </div>

</div>

</body>
</html>