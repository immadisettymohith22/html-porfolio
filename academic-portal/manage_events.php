<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events | Admin Panel</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <i class="fas fa-user-shield"></i> Admin Dashboard
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            Welcome, Admin 👋
        </span>
    </div>
    <div class="nav-links">
        <a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <a href="add_event.php" class="btn"><i class="fas fa-plus"></i> Add Event</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<h1 class="title" style="margin-top: 2rem;">Manage Academic Events</h1>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Event Name</th>
                <th>Category</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Timing</th>
                <th>Seats (Filled/Total)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $today = date('Y-m-d');
    $this_month_end = date('Y-m-t');
    
    $result = mysqli_query($conn, "SELECT DISTINCT category FROM events ORDER BY category ASC");
    $categories = [];
    while($cat_row = mysqli_fetch_assoc($result)) {
        $categories[] = $cat_row['category'] ?: 'Uncategorized';
    }

    function render_admin_table_rows($events, $category_label, $badge_class, $icon, $is_completed = false) {
        global $conn;
        if (empty($events)) return;
        
        $cat_class = 'cat-' . strtolower($category_label);
        echo "<tr style='background: var(--glass-bg);'><td colspan='10' style='text-align: left; padding: 1rem 2rem;'><strong><i class='$icon'></i> $category_label</strong> <span class='badge $badge_class $cat_class' style='margin-left: 10px;'>Category</span></td></tr>";
@
        
        $serial_no = 1;
        foreach($events as $row) {
            echo "<tr>";
            echo "<td>".$serial_no++."</td>";
            $img_path = "uploads/" . $row['image'];
            if (!empty($row['image'])) {
                echo "<td><img src='$img_path' width='80' style='border-radius: 8px; border: 1px solid var(--glass-border);'></td>";
            } else {
                echo "<td><i class='fas fa-image fa-2x' style='color: var(--text-muted);'></i></td>";
            }
            echo "<td style='font-weight: 600;'>".$row['event_name']."</td>";
            echo "<td style='color: var(--secondary); font-weight: 500;'>".$row['category']."</td>";
            $formatted_date = date('d-m-Y, l', strtotime($row['event_date']));
            $start_time = strtolower(date('ga', strtotime($row['event_time'])));
            
            $event_name = $row['event_name'];
            $total_seats = $row['seats'];
            $reg_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations WHERE event='$event_name'");
            $reg_data = mysqli_fetch_assoc($reg_query);
            $filled_seats = $reg_data['count'];
            $available_seats = $total_seats - $filled_seats;

            echo "<td><i class='fas fa-calendar-day' style='color: var(--secondary); margin-right: 5px;'></i> ".$formatted_date."</td>";
            echo "<td>".$row['venue']."</td>";
            echo "<td>".$start_time."</td>";
            echo "<td><span style='color: var(--primary); font-weight: 600;'>$filled_seats</span> / $total_seats <br> <small style='color: #64748b;'>$available_seats left</small></td>";
            
            $status_color = $is_completed ? 'var(--text-muted)' : ($row['status'] == 'Open' ? 'var(--secondary)' : 'var(--accent)');
            $status_text = $is_completed ? 'Completed' : $row['status'];
            echo "<td><span style='color: $status_color;'>$status_text</span></td>";
            
            echo "<td>
                    <a href='edit_event.php?id=".$row['id']."' style='color: var(--primary); text-decoration: none; margin-right: 15px;' title='Edit'><i class='fas fa-edit'></i></a>
                    <a href='delete_event.php?id=".$row['id']."' style='color: var(--accent); text-decoration: none;' title='Delete' onclick=\"return confirm('Delete this event?')\"><i class='fas fa-trash'></i></a>
                  </td>";
            echo "</tr>";
        }
    }

    foreach($categories as $category) {
        $safe_cat = mysqli_real_escape_string($conn, $category);
        $cat_events_res = mysqli_query($conn, "SELECT * FROM events WHERE (category='$safe_cat' OR (category='' AND '$category'='Uncategorized')) ORDER BY event_date DESC");
        $cat_events = [];
        while($ce = mysqli_fetch_assoc($cat_events_res)) $cat_events[] = $ce;
        
        $icon = 'fas fa-tags';
        if(stripos($category, 'tech') !== false) $icon = 'fas fa-code';
        if(stripos($category, 'work') !== false) $icon = 'fas fa-chalkboard-teacher';
        if(stripos($category, 'sport') !== false) $icon = 'fas fa-running';

        render_admin_table_rows($cat_events, strtoupper($category), 'badge-month', $icon);
    }
    ?>
        </tbody>
    </table>
</div>

</body>
</html>