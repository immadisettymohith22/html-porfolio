<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Event Portal</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal
        <?php if(isset($_SESSION['student_name'])): ?>
            <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
                Welcome, <?php echo $_SESSION['student_name']; ?> 👋
            </span>
        <?php elseif(isset($_SESSION['admin'])): ?>
            <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
                Welcome, Admin 👋
            </span>
        <?php endif; ?>
    </div>
    <div class="nav-links">
        <a href="portal.php">Home</a>
        <a href="student_portal.php">Student</a>
        <a href="admin_login.php">Admin</a>
        <a href="events.php">Events</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div style="text-align:center; padding: 2rem;">

    <p class="welcome-text">Welcome!</p>
    <h1 class="title" style="font-size: 2.2rem; margin-top: 2rem;">Academic Event Management</h1>
    <p style="color: var(--text-muted); margin-bottom: 3rem;">Please select your access portal below to continue.</p>

    <div class="dashboard-container">

        <div class="dashboard-card">
            <img src="student_portal_bg.png" class="card-bg" alt="Student Portal Background">
            <i class="fas fa-user-graduate fa-2x" style="color: var(--primary); flex-shrink: 0;"></i>
            <h3>Student Portal</h3>
            <p>Register and participate in all upcoming campus events.</p>
            <a href="student_portal.php" class="btn" style="width: 100%; background: var(--primary);">Enter Student Portal</a>
        </div>

        <div class="dashboard-card">
            <img src="admin_portal_bg.png" class="card-bg" alt="Admin Portal Background">
            <i class="fas fa-user-shield fa-2x" style="color: var(--accent); flex-shrink: 0;"></i>
            <h3>Admin Portal</h3>
            <p>Full control over event management and registrations.</p>
            <a href="admin_login.php" class="btn" style="width: 100%; background: var(--accent);">Enter Admin Portal</a>
        </div>

        <div class="dashboard-card">
            <img src="events_portal_bg.png" class="card-bg" alt="Events Portal Background">
            <i class="fas fa-calendar-day fa-2x" style="color: var(--secondary); flex-shrink: 0;"></i>
            <h3>View Events</h3>
            <p>Browse the catalog of active and upcoming events.</p>
            <a href="events.php" class="btn" style="width: 100%; background: var(--secondary);">Browse Events</a>
        </div>

    </div>

</div>

</body>
</html>