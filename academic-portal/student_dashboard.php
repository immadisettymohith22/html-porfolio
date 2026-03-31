<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="img2.png" alt="One Piece Logo" class="logo-img"> 
            <span style="font-weight: 700; color: var(--primary);">Academic Portal</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="student_dashboard.php" class="sidebar-item active">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="events.php" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="register.php" class="sidebar-item">
                <i class="fas fa-edit"></i>
                <span>Registration</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-clipboard-check"></i>
                <span>My Registered Events</span>
            </a>
            <a href="student_profile.php" class="sidebar-item">
                <i class="fas fa-user-cog"></i>
                <span>Profile Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="student_logout.php" class="sidebar-item" style="color: var(--accent);">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="dashboard-top">
            <form action="events.php" method="GET" class="dashboard-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" id="dashboard-search-input" placeholder="Search events or notices..." oninput="filterDashboardContent()" required>
            </form>
            
            <div class="user-profile-nav">
                <button id="theme-toggle" class="theme-toggle-dashboard" title="Toggle Theme">
                    <i class="fas fa-sun"></i>
                </button>
                <div class="user-profile-badge">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['student_name']); ?>&background=818cf8&color=fff" alt="User Avatar" class="user-avatar">
                    <div class="user-info-text">
                        <h4><?php echo $_SESSION['student_name']; ?></h4>
                        <p>Student Portal User</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <p style="margin-bottom: 0.5rem; opacity: 0.8; font-weight: 500; font-size: 0.95rem;">
                        <?php echo date('F j, Y'); ?>
                    </p>
                    <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h1>
                    <p>Always stay updated in your student portal.</p>
                </div>
                <img src="img2.png" alt="Portal Character" class="luffy-illustration">
            </div>

            <!-- Stats Grid -->
            <?php
            $email = $_SESSION['student_email'];
            $reg_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations WHERE email='$email'");
            $reg_result = mysqli_fetch_assoc($reg_query);
            $reg_count = $reg_result['total'];

            $available_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM events WHERE event_date >= CURDATE()");
            $available_result = mysqli_fetch_assoc($available_query);
            $available_count = $available_result['total'];

            $total_events_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM events");
            $total_events_result = mysqli_fetch_assoc($total_events_query);
            $total_count = $total_events_result['total'];
            ?>
            <section class="stats-grid">
                <a href="#registered-events" class="stat-card-mini">
                    <div class="stat-icon-box" style="background: #4f46e5;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-value"><?php echo $reg_count; ?><small style="font-size: 1.2rem; opacity: 0.7;">/3</small></div>
                    <div class="stat-label">MY REGISTRATIONS</div>
                </a>

                <a href="events.php" class="stat-card-mini">
                    <div class="stat-icon-box" style="background: #0891b2;">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="stat-value"><?php echo $available_count; ?></div>
                    <div class="stat-label">AVAILABLE EVENTS</div>
                </a>

                <a href="events.php" class="stat-card-mini">
                    <div class="stat-icon-box" style="background: #db2777;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_count; ?></div>
                    <div class="stat-label">TOTAL EVENTS</div>
                </a>
            </section>

        <!-- Main Grid (Tables & Side Info) -->
        <div class="dashboard-grid">
            <div class="main-content-area" id="registered-events">
                <div class="section-title">
                    <h3>My Registered Events</h3>
                    <a href="events.php" class="section-link">View All</a>
                </div>

                <div class="table-container" style="margin: 0;">
                    <table>
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Date & Timing</th>
                                <th>Venue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($conn, "SELECT r.*, e.event_date, e.venue, e.event_time 
                                                         FROM registrations r 
                                                         JOIN events e ON r.event = e.event_name 
                                                         WHERE r.email='$email' 
                                                         ORDER BY e.event_date DESC LIMIT 5");
                            
                            while($row = mysqli_fetch_assoc($result)){
                                $today = date('Y-m-d');
                                $e_date = $row['event_date'];
                                if ($e_date < $today) {
                                    $status_html = "<span class='badge badge-completed'>Past</span>";
                                } elseif ($e_date == $today) {
                                    $status_html = "<span class='badge badge-live'>Today</span>";
                                } else {
                                    $status_html = "<span class='badge badge-upcoming'>Upcoming</span>";
                                }

                                $formatted_date = date('d M, Y', strtotime($row['event_date']));
                                $start_time = strtolower(date('ga', strtotime($row['event_time'])));
                                
                                echo "<tr>";
                                echo "<td style='font-weight: 600; text-align: left;'>" . $row['event'] . "</td>";
                                echo "<td>" . $formatted_date . " | " . $start_time . "</td>";
                                echo "<td>" . $row['venue'] . "</td>";
                                echo "<td>$status_html</td>";
                                echo "</tr>";
                            }
                            
                            if(mysqli_num_rows($result) == 0){
                                echo "<tr><td colspan='4' style='text-align: center; color: var(--text-muted); padding: 3rem;'>No active registrations found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="dashboard-section">
                <div class="notice-panel">
                    <div class="section-title">
                        <h3>Daily Notice</h3>
                        <a href="#" class="section-link">See all</a>
                    </div>
                    
                    <div class="notice-list">
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--glass-border); text-align: center;">
                            <p style="font-size: 0.85rem; color: var(--accent); font-weight: 700;">
                                <i class="fas fa-info-circle"></i> Every student can only register for 3 events only.
                            </p>
                        </div>
                        <div class="notice-card-item">
                            <h5>Spring Festival Event</h5>
                            <p>Final event for Spring semester will start from next Monday.</p>
                            <a href="#" class="notice-link">View details</a>
                        </div>
                        <div class="notice-card-item">
                            <h5>Library Event Maintenance</h5>
                            <p>The central event library will be closed this Sunday for system updates.</p>
                            <a href="#" class="notice-link">View details</a>
                        </div>
                    </div>

                    <div class="section-title" style="margin-top: 2rem;">
                        <h3>Event Organizers</h3>
                    </div>
                    <div class="instructor-avatars">
                        <img src="https://ui-avatars.com/api/?name=Luffy&background=ef4444&color=fff" title="Monkey D. Luffy" class="instructor-img">
                        <img src="https://ui-avatars.com/api/?name=Zoro&background=10b981&color=fff" title="Roronoa Zoro" class="instructor-img">
                        <img src="https://ui-avatars.com/api/?name=Nami&background=f59e0b&color=fff" title="Nami" class="instructor-img">
                        <img src="https://ui-avatars.com/api/?name=Sanji&background=facc15&color=fff" title="Vinsmoke Sanji" class="instructor-img">
                    </div>
                </div>
            </aside>
        </div>
    </main>
</div>

<script>
function filterDashboardContent() {
    const query = document.getElementById('dashboard-search-input').value.toLowerCase();
    
    // Filter Registered Events Table
    const tableRows = document.querySelectorAll('.table-container tbody tr');
    tableRows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });

    // Filter Daily Notices
    const noticeItems = document.querySelectorAll('.notice-card-item');
    noticeItems.forEach(item => {
        const text = item.innerText.toLowerCase();
        item.style.display = text.includes(query) ? '' : 'none';
    });
}
</script>
</body>
</html>