<?php
session_start();
include 'connect.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events | One Piece Academic Portal</title>
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
        <a href="events.php">Events</a>
        <?php if(isset($_SESSION['admin'])): ?>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php" style="color: var(--accent);">Logout</a>
        <?php elseif(isset($_SESSION['student_email'])): ?>
            <a href="student_dashboard.php">Dashboard</a>
            <a href="student_logout.php" style="color: var(--accent);">Logout</a>
        <?php else: ?>
            <a href="student_login.php">Student Login</a>
            <a href="admin_login.php">Admin</a>
        <?php endif; ?>
        
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="top-banner">
    <i class="fas fa-search"></i> Browse Events
</div>

<div class="sidebar-layout">
    <!-- Sidebar -->
    <aside class="sidebar-aside">
        <form method="GET" class="filter-sidebar-card">
            <h3>Filters</h3>
            
            <div class="form-group">
                <label>Search</label>
                <?php $search_query = $_GET['search'] ?? ''; ?>
                <div style="position: relative; display: flex; align-items: center;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; color: var(--text-muted); font-size: 0.9rem;"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search events..." style="padding-left: 2.5rem; margin-bottom: 0;">
                </div>
            </div>

            <div class="form-group">
                <label>Category</label>
                <?php $selected_cat = $_GET['category'] ?? ''; ?>
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="Workshop" <?php if($selected_cat == 'Workshop') echo 'selected'; ?>>Workshop</option>
                    <option value="Cultural" <?php if($selected_cat == 'Cultural') echo 'selected'; ?>>Cultural</option>
                    <option value="Sports" <?php if($selected_cat == 'Sports') echo 'selected'; ?>>Sports</option>
                    <option value="Tech" <?php if($selected_cat == 'Tech') echo 'selected'; ?>>Tech</option>
                    <option value="GAMING EVENT" <?php if($selected_cat == 'GAMING EVENT') echo 'selected'; ?>>GAMING EVENT</option>
                    <option value="MEDIA & CREATIVE" <?php if($selected_cat == 'MEDIA & CREATIVE') echo 'selected'; ?>>MEDIA & CREATIVE</option>
                    <option value="MANAGEMENT EVENT" <?php if($selected_cat == 'MANAGEMENT EVENT') echo 'selected'; ?>>MANAGEMENT EVENT</option>
                </select>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Apply Filters</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="main-events-content">

<?php
$today = date('Y-m-d');
$this_month_end = date('Y-m-t');
$cat_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';

$sql = "SELECT * FROM events";
$where_clauses = [];

if($cat_filter) {
    $where_clauses[] = "category = '" . mysqli_real_escape_string($conn, $cat_filter) . "'";
}

if($search_query) {
    $s = mysqli_real_escape_string($conn, $search_query);
    $where_clauses[] = "(event_name LIKE '%$s%' OR description LIKE '%$s%' OR venue LIKE '%$s%')";
}

if(!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);

$completed_events = [];
$this_month_events = [];
$upcoming_events = [];

while($row = mysqli_fetch_assoc($result)) {
    $e_date = $row['event_date'];
    if($e_date < $today) {
        $completed_events[] = $row;
    } elseif($e_date <= $this_month_end) {
        $this_month_events[] = $row;
    } else {
        $upcoming_events[] = $row;
    }
}

function render_event_cards($events, $conn, $is_completed = false) {
    if (empty($events)) return;
    
    echo "<div class='event-grid'>";
    foreach($events as $row) {
        $event_name = $row['event_name'];
        $total_seats = $row['seats'];
        
        $reg_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations WHERE event='$event_name'");
        $reg_data = mysqli_fetch_assoc($reg_query);
        $filled_count = $reg_data['count'];
        $available_seats = $total_seats - $filled_count;

        $card_class = $is_completed ? 'event-card completed' : 'event-card';

        echo "<div class='$card_class'>";
        echo "<div class='event-image-container'>";
        $clean_cat = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $row['category']));
        $cat_class = 'cat-' . $clean_cat;
        echo "<span class='category-badge-pill $cat_class'>".strtoupper($row['category'])."</span>";
        echo "<img src='uploads/".$row['image']."' alt='".$row['event_name']."'>";
        echo "</div>";
        
        echo "<div class='event-card-content'>";
        $beautified_name = str_ireplace(['Workshope', '&'], ['Workshop', ' & '], $row['event_name']);
        echo "<h4>".$beautified_name."</h4>";
        
        $e_timestamp = strtotime($row['event_date']);
        $formatted_date = date('d-m-Y, l', $e_timestamp);
        
        $e_date_obj = new DateTime($row['event_date']);
        $e_date_obj->modify('-1 day');
        $last_display = $e_date_obj->format('d-m-Y, l');
        
        echo "<div class='date'><i class='fas fa-calendar-days'></i><span>".$formatted_date."</span></div>";
        if (!$is_completed) {
            echo "<div class='last-date'><i class='fas fa-calendar-check' style='font-size: 0.85rem;'></i><span style='font-size: 0.85rem;'>Last Date: ".$last_display."</span></div>";
        }
        
        $start_time = strtolower(date('ga', strtotime($row['event_time'])));
        $full_time = $start_time;

        echo "<div class='location'><i class='fas fa-location-dot' style='color: var(--accent);'></i><span>".$row['venue']."</span></div>";
        echo "<div class='location' style='margin-top: -0.2rem; border-top: none; padding-top: 0;'><i class='fas fa-user-tie' style='color: var(--primary); font-size: 0.85rem;'></i><span style='font-size: 0.85rem; font-weight: 500;'>".$row['faculty_name']."</span></div>";
        
        echo "<div style='margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;'>";
        echo "<span style='color: var(--text-muted); font-size: 0.85rem; font-weight: 500;'>$available_seats seats left</span>";
        
        if($is_completed) {
            echo "<button class='btn-mockup' style='background: #cbd5e1; cursor: not-allowed; padding: 0.5rem 1rem;' disabled>Done</button>";
        } elseif($available_seats > 0) {
            $encoded_event = urlencode($row['event_name']);
            echo "<a href='event_details.php?event={$encoded_event}' class='btn-mockup' style='padding: 0.5rem 1.2rem;'>Details</a>";
        } else {
            echo "<button class='btn-mockup' style='background: #334155; cursor: not-allowed; padding: 0.5rem 1rem;' disabled>Full</button>";
        }
        
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}

// Sections
if (empty($this_month_events) && empty($upcoming_events) && empty($completed_events)) {
    echo "<div style='text-align: center; padding: 4rem 2rem;'>
            <i class='fas fa-info-circle fa-3x' style='color: var(--primary); margin-bottom: 1rem;'></i>
            <h3 style='color: var(--text-main);'>No events found.</h3>
          </div>";
} else {
    if(!empty($this_month_events)) {
        echo "<div class='section-header'><h2><i class='fas fa-bolt' style='color: var(--accent);'></i> This Month</h2></div>";
        render_event_cards($this_month_events, $conn);
    }
    if(!empty($upcoming_events)) {
        echo "<div class='section-header'><h2><i class='fas fa-calendar-alt' style='color: var(--primary);'></i> Other Events</h2></div>";
        render_event_cards($upcoming_events, $conn);
    }
    if(!empty($completed_events)) {
        echo "<div class='section-header'><h2><i class='fas fa-check-circle' style='color: var(--secondary);'></i> Completed Events</h2></div>";
        render_event_cards($completed_events, $conn, true);
    }
}
?>
    </main>
</div>

</body>
</html>