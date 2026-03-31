<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'connect.php';

// Check if ID is set
if(!isset($_GET['id'])){
    header("Location: manage_events.php");
    exit();
}

$id = intval($_GET['id']); // secure id

$result = mysqli_query($conn, "SELECT * FROM events WHERE id=$id");
$row = mysqli_fetch_assoc($result);

// If event not found
if(!$row){
    header("Location: manage_events.php");
    exit();
}

if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $timing = mysqli_real_escape_string($conn, $_POST['timing']);
    $seats = (int)$_POST['seats'];
    $faculty = mysqli_real_escape_string($conn, $_POST['faculty_name']);

    // Check if a new image is uploaded
    if(!empty($_FILES['image']['name'])){
        $image_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $upload_folder = "uploads/" . $image_name;
        move_uploaded_file($tmp_name, $upload_folder);
        
        // Update with new image
        mysqli_query($conn, "UPDATE events SET 
            event_name='$name',
            faculty_name='$faculty',
            category='$category',
            event_date='$date',
            description='$desc',
            venue='$venue',
            event_time='$timing',
            image='$image_name',
            seats='$seats'
            WHERE id=$id");
    } else {
        // Update without changing image
        mysqli_query($conn, "UPDATE events SET 
            event_name='$name',
            faculty_name='$faculty',
            category='$category',
            event_date='$date',
            description='$desc',
            venue='$venue',
            event_time='$timing',
            seats='$seats'
            WHERE id=$id");
    }

    header("Location: manage_events.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | Admin</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <i class="fas fa-user-shield" style="color: var(--gold);"></i> <span class="welcome-text">Admin Control Panel</span>
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            One Piece Academic Portal
        </span>
    </div>
    <div class="nav-links">
        <a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="auth-container" style="padding: 2rem 1.5rem;">
    <div class="glass-card auth-card" style="max-width: 800px; padding: 3rem; border-radius: 20px;">
        <h2 style="margin-bottom: 2.5rem; display: flex; align-items: center; gap: 15px; font-size: 2.2rem; color: var(--gold);">
            <i class="fas fa-calendar-check"></i> Edit Event
        </h2>
        <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group" style="grid-column: span 2;">
                <label><i class="fas fa-heading"></i> Event Name</label>
                <input type="text" name="event_name" value="<?php echo $row['event_name']; ?>" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-day"></i> Event Date</label>
                <input type="date" name="event_date" value="<?php echo $row['event_date']; ?>" onclick="this.showPicker()" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-tags"></i> Category</label>
                <select name="category" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 0.8rem; font-family: inherit;">
                    <option value="Workshop" <?php if($row['category'] == 'Workshop') echo 'selected'; ?> style="background: var(--bg-card);">Workshop</option>
                    <option value="Cultural" <?php if($row['category'] == 'Cultural') echo 'selected'; ?> style="background: var(--bg-card);">Cultural</option>
                    <option value="Sports" <?php if($row['category'] == 'Sports') echo 'selected'; ?> style="background: var(--bg-card);">Sports</option>
                    <option value="Tech" <?php if($row['category'] == 'Tech') echo 'selected'; ?> style="background: var(--bg-card);">Tech</option>
                    <option value="GAMING EVENT" <?php if($row['category'] == 'GAMING EVENT') echo 'selected'; ?> style="background: var(--bg-card);">GAMING EVENT</option>
                    <option value="MEDIA & CREATIVE" <?php if($row['category'] == 'MEDIA & CREATIVE') echo 'selected'; ?> style="background: var(--bg-card);">MEDIA & CREATIVE</option>
                    <option value="MANAGEMENT EVENT" <?php if($row['category'] == 'MANAGEMENT EVENT') echo 'selected'; ?> style="background: var(--bg-card);">MANAGEMENT EVENT</option>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" rows="4" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 1rem; font-family: inherit; resize: vertical;"><?php echo $row['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Venue Hall</label>
                <input type="text" name="venue" value="<?php echo $row['venue']; ?>" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> Event Start Time</label>
                <input type="text" name="timing" value="<?php echo $row['event_time']; ?>" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-chalkboard-teacher"></i> Faculty In-charge</label>
                <input type="text" name="faculty_name" value="<?php echo $row['faculty_name']; ?>" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-image"></i> Event Image (Leave blank to keep)</label>
                <input type="file" name="image" style="padding: 0.6rem;">
                <?php if(!empty($row['image'])): ?>
                    <p style="font-size: 0.8rem; color: var(--gold); margin-top: 0.5rem;"><i class="fas fa-file-image"></i> Current: <?php echo $row['image']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label><i class="fas fa-users"></i> Seats Available</label>
                <input type="number" name="seats" value="<?php echo $row['seats']; ?>" required>
            </div>
            <div style="grid-column: span 2; margin-top: 1rem;">
                <button type="submit" name="update" class="btn" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;">Update Event Details</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>