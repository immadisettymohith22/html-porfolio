<?php
session_start();
include 'connect.php';

if(!isset($_GET['event'])) {
    header("Location: events.php");
    exit();
}

$event_name = mysqli_real_escape_string($conn, $_GET['event']);
$result = mysqli_query($conn, "SELECT * FROM events WHERE event_name = '$event_name'");
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header("Location: events.php");
    exit();
}

// Stats
$reg_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations WHERE event='$event_name'");
$reg_data = mysqli_fetch_assoc($reg_query);
$available_seats = $row['seats'] - $reg_data['count'];

// Deadline
$e_date_obj = new DateTime($row['event_date']);
$e_date_obj->modify('-1 day');
$last_display = $e_date_obj->format('d-m-Y, l');

$formatted_date = date('d-m-Y, l', strtotime($row['event_date']));

$start_time = strtolower(date('ga', strtotime($row['event_time'])));
$full_time = $start_time;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['event_name']; ?> | Details</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
    <style>
        .details-container {
            max-width: 380px;
            margin: 1.5rem auto;
            padding: 0 0.8rem;
        }
        .details-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            color: #333;
        }
        .details-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        .details-content {
            padding: 1.2rem;
        }
        .details-header {
            margin-bottom: 0.8rem;
        }
        .details-title {
            font-size: 1.3rem;
            color: #1e3a8a;
            margin-bottom: 0.3rem;
            line-height: 1.2;
        }
        .details-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 6px;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
            color: #64748b;
        }
        .meta-item i {
            color: #2563eb;
            font-size: 0.9rem;
            width: 12px;
            text-align: center;
        }
        .details-description {
            font-size: 0.85rem;
            line-height: 1.5;
            color: #475569;
            margin-bottom: 1.5rem;
            white-space: pre-line;
        }
        .details-footer {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            align-items: center;
            padding-top: 1.2rem;
            border-top: 1px solid #e2e8f0;
        }
        .btn-register {
            background: #1e3a8a;
            color: #fff;
            padding: 0.7rem 1.5rem;
            width: 100%;
            text-align: center;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background: #1e40af;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(30, 58, 138, 0.3);
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
            gap: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            transition: var(--transition);
        }
        .back-btn:hover {
            color: var(--primary) !important;
            transform: translateX(-5px);
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal
    </div>
    <div class="nav-links">
        <a href="portal.php">Home</a>
        <a href="events.php">Events</a>
        <button id="theme-toggle" class="theme-toggle">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="details-container">
    <a href="events.php" class="back-btn" style="color: var(--text-muted);"><i class="fas fa-arrow-left"></i> Back to Events</a>
    
    <div class="event-card" style="height: auto !important; flex-direction: column !important; max-width: 420px; margin: 0 auto; background: var(--bg-card) !important; color: var(--text-main) !important;">
        <div style="position: relative; height: 240px; overflow: hidden;">
            <?php 
                $clean_cat = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $row['category']));
                $cat_class = 'cat-' . $clean_cat;
            ?>
            <span class="category-badge-pill <?php echo $cat_class; ?>" style="top: 20px; right: 20px;"><?php echo strtoupper($row['category']); ?></span>
            <img src="uploads/<?php echo $row['image']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Event Banner">
        </div>
        
        <div class="event-card-content" style="padding: 1.2rem;">
            <div class="details-header">
                <h1 style="font-size: 1.6rem; color: var(--text-main) !important; margin-bottom: 0.4rem; line-height: 1.2; text-shadow: none;"><?php echo $row['event_name']; ?></h1>
                <div class="date" style="margin-bottom: 0.6rem;"><i class="fas fa-calendar-days"></i> <span><?php echo $formatted_date; ?></span></div>
            </div>

                <div class="meta-item" style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-user-tie" style="color: var(--primary); font-size: 1.1rem; width: 24px; text-align: center;"></i>
                    <span style="color: var(--text-main); font-size: 0.95rem;"><strong>Faculty:</strong> <?php echo $row['faculty_name']; ?></span>
                </div>
                <div class="meta-item" style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-location-dot" style="color: var(--accent); font-size: 1.1rem; width: 24px; text-align: center;"></i>
                    <span style="color: var(--text-main); font-size: 0.95rem;"><strong>Venue:</strong> <?php echo $row['venue']; ?></span>
                </div>
                <div class="meta-item" style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-clock" style="color: var(--secondary); font-size: 1.1rem; width: 24px; text-align: center;"></i>
                    <span style="color: var(--text-main); font-size: 0.95rem;"><strong>Duration:</strong> <?php echo $full_time; ?></span>
                </div>
                <div class="meta-item" style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-calendar-check" style="color: var(--gold); font-size: 1.1rem; width: 24px; text-align: center;"></i>
                    <span style="color: var(--text-main); font-size: 0.95rem;"><strong>Last Date:</strong> <?php echo $last_display; ?></span>
                </div>
                <div class="meta-item" style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-users" style="color: var(--violet); font-size: 1.1rem; width: 24px; text-align: center;"></i>
                    <span style="color: var(--text-main); font-size: 0.95rem;"><strong>Seats Left:</strong> <?php echo $available_seats; ?> / <?php echo $row['seats']; ?></span>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.03); border-radius: 12px; padding: 1.2rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem; white-space: pre-line; font-size: 0.9rem; border: 1px solid var(--glass-border);">
                <?php echo $row['description']; ?>
            </div>

            <div style="border-top: 1px solid var(--glass-border); padding-top: 1.2rem; text-align: center;">
                <?php if($available_seats > 0 && date('Y-m-d') < $row['event_date']): ?>
                    <a href="register.php?event=<?php echo urlencode($row['event_name']); ?>" class="btn" style="padding: 1rem 3rem;">Register for Event</a>
                <?php else: ?>
                    <button class="btn" style="background: #334155; cursor: not-allowed; opacity: 0.6;" disabled>Registration Closed</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
