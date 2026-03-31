<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'connect.php';

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $timing = mysqli_real_escape_string($conn, $_POST['timing']);
    $seats = (int)$_POST['seats'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $faculty = mysqli_real_escape_string($conn, $_POST['faculty_name']);

    // Image Upload
    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    $upload_folder = "uploads/" . $image_name;

    move_uploaded_file($tmp_name, $upload_folder);

    mysqli_query($conn, "INSERT INTO events 
    (event_name, faculty_name, category, event_date, description, venue, event_time, image, seats, status)
    VALUES 
    ('$name', '$faculty', '$category', '$date', '$desc', '$venue', '$timing', '$image_name', '$seats', '$status')");

    header("Location: manage_events.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event | Admin</title>
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
            <i class="fas fa-calendar-plus"></i> Add New Event
        </h2>
        <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group" style="grid-column: span 2;">
                <label><i class="fas fa-heading"></i> Event Name</label>
                <input type="text" name="event_name" placeholder="e.g., Tech Symposium 2026" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-day"></i> Event Date</label>
                <input type="date" name="event_date" onclick="this.showPicker()" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-tags"></i> Category</label>
                <select name="category" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 0.8rem; font-family: inherit;">
                    <option value="" style="background: var(--bg-card);">-- Select Category --</option>
                    <option value="Workshop" style="background: var(--bg-card);">Workshop</option>
                    <option value="Cultural" style="background: var(--bg-card);">Cultural</option>
                    <option value="Sports" style="background: var(--bg-card);">Sports</option>
                    <option value="Tech" style="background: var(--bg-card);">Tech</option>
                    <option value="GAMING EVENT" style="background: var(--bg-card);">GAMING EVENT</option>
                    <option value="MEDIA & CREATIVE" style="background: var(--bg-card);">MEDIA & CREATIVE</option>
                    <option value="MANAGEMENT EVENT" style="background: var(--bg-card);">MANAGEMENT EVENT</option>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" placeholder="Brief event description..." rows="4" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 1rem; font-family: inherit; resize: vertical;"></textarea>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Venue Hall</label>
                <input type="text" name="venue" placeholder="e.g., Seminar Hall A" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> Event Start Time</label>
                <input type="text" name="timing" placeholder="e.g., 10:00 AM" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-chalkboard-teacher"></i> Faculty In-charge</label>
                <input type="text" name="faculty_name" placeholder="e.g., Dr. S. K. Sharma" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-image"></i> Event Image</label>
                <input type="file" name="image" required style="padding: 0.6rem;">
            </div>
            <div class="form-group">
                <label><i class="fas fa-users"></i> Seats Available</label>
                <input type="number" name="seats" placeholder="100" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-info-circle"></i> Status</label>
                <select name="status" style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 0.8rem; font-family: inherit;">
                    <option value="Open" style="background: var(--bg-card);">Open</option>
                    <option value="Closed" style="background: var(--bg-card);">Closed</option>
                </select>
            </div>
            <div style="grid-column: span 2; margin-top: 1rem;">
                <button type="submit" name="submit" class="btn" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;">Create Event</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>