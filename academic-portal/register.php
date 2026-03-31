<?php
session_start();
include 'connect.php';

// Allow only logged-in students
if (!isset($_SESSION['student_email'])) {
    header("Location: student_login.php");
    exit();
}

$sess_email = $_SESSION['student_email'];
$student_info_query = mysqli_query($conn, "SELECT * FROM students WHERE email='$sess_email'");
$student_info = mysqli_fetch_assoc($student_info_query);

if (isset($_POST['submit'])) {

    $name = $_SESSION['student_name']; // from login
    $email = $_SESSION['student_email']; // from login
    $rollno = $_POST['rollno'];
    $department = $_POST['department'];
    if ($department == "Other") {
        $department = $_POST['other_department'];
    }
    $event = $_POST['event'];

    // Check 1: Is the student already registered for THIS event?
    $check_dup = mysqli_query($conn, "SELECT * FROM registrations WHERE rollno='$rollno' AND event='$event'");
    if (mysqli_num_rows($check_dup) > 0) {
        echo "<script>alert('Error: You are already registered for this event!'); window.history.back();</script>";
        exit();
    }

    // Check 2: Has the student already registered for 3 events?
    $check_limit = mysqli_query($conn, "SELECT * FROM registrations WHERE email='$email'");
    if (mysqli_num_rows($check_limit) >= 3) {
        echo "<script>alert('Error: You can only register for a maximum of 3 events!'); window.history.back();</script>";
        exit();
    }

    // Check 3: Is this roll number already linked to another email?
    $check_rollno = mysqli_query($conn, "SELECT * FROM registrations WHERE rollno='$rollno' AND email != '$email'");
    if (mysqli_num_rows($check_rollno) > 0) {
        header("Location: 505.php");
        exit();
    }

    $year = $_POST['year'];
    $mobile = $_POST['mobile'];

    mysqli_query($conn, "INSERT INTO registrations 
    (name, rollno, email, mobile, department, year, event)
    VALUES ('$name','$rollno','$email','$mobile','$department','$year','$event')");

    echo "<script>alert('Registration Successful!'); window.location.href='student_dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            Welcome, <?php echo $_SESSION['student_name']; ?> 👋
        </span>
    </div>
    <div class="nav-links">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="student_logout.php" style="color: var(--accent);">Logout</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="auth-container">
    <div class="glass-card auth-card" style="width: 100%; max-width: 500px;">
        <h2><i class="fas fa-pen-to-square"></i> Register for Event</h2>
        <?php
$sess_email = $_SESSION['student_email'];
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations WHERE email='$sess_email'");
$count_data = mysqli_fetch_assoc($count_query);
if ($count_data['total'] >= 3):
?>
            <div style="text-align: center; padding: 2rem 0;">
                <i class="fas fa-exclamation-triangle fa-3x" style="color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-main); margin-bottom: 1rem;">Registration Limit Crossed</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">You have already registered for the maximum of 3 events allowed per student.</p>
                <a href="student_dashboard.php" class="btn" style="margin-top: 2rem;">Go to Dashboard</a>
            </div>
        <?php
else: ?>
        <form method="POST">
            <div class="form-group">
                <label>Roll No</label>
                <input type="text" name="rollno" value="<?php echo $student_info['rollno'] ?? ''; ?>" placeholder="Enter your roll number" required>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department" id="deptSelect" onchange="toggleOtherDept()" required>
                    <option value="">-- Select Department --</option>
                    <option value="CSE" <?php if(($student_info['department'] ?? '') == 'CSE') echo 'selected'; ?>>CSE</option>
                    <option value="ECE" <?php if(($student_info['department'] ?? '') == 'ECE') echo 'selected'; ?>>ECE</option>
                    <option value="EEE" <?php if(($student_info['department'] ?? '') == 'EEE') echo 'selected'; ?>>EEE</option>
                    <option value="Bio Medical" <?php if(($student_info['department'] ?? '') == 'Bio Medical') echo 'selected'; ?>>Bio Medical</option>
                    <option value="Other" <?php if(!empty($student_info['department']) && !in_array($student_info['department'], ['CSE','ECE','EEE','Bio Medical'])) echo 'selected'; ?>>Other (Please specify)</option>
                </select>
            </div>
            <div class="form-group" id="otherDeptGroup" style="display: none;">
                <label>Specify Department</label>
                <input type="text" name="other_department" placeholder="Enter your department">
            </div>
            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" value="<?php echo $student_info['mobile'] ?? ''; ?>" placeholder="Enter your mobile number" required pattern="\+91\s?[0-9]{10}" maxlength="14" style="width: 100%;">
                </div>
                <div>
                    <label>Year of Student</label>
                    <select name="year" required style="width: 100%;">
                        <option value="">-- Select Year --</option>
                        <option value="1" <?php if($student_info['year'] == '1') echo 'selected'; ?>>1st Year</option>
                        <option value="2" <?php if($student_info['year'] == '2') echo 'selected'; ?>>2nd Year</option>
                        <option value="3" <?php if($student_info['year'] == '3') echo 'selected'; ?>>3rd Year</option>
                        <option value="4" <?php if($student_info['year'] == '4') echo 'selected'; ?>>4th Year</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label>Select Event</label>
                <select name="event" required>
                    <option value="">-- Choose an Event --</option>
                    <?php
    $selected_event = isset($_GET['event']) ? urldecode($_GET['event']) : '';
    $events_res = mysqli_query($conn, "SELECT * FROM events ORDER BY category ASC, event_date ASC");
    $current_date = date('Y-m-d');
    
    $events_by_cat = [];
    while($e = mysqli_fetch_assoc($events_res)){
        $cat = $e['category'] ?: 'Uncategorized';
        $events_by_cat[$cat][] = $e;
    }

    foreach($events_by_cat as $category => $category_events) {
        echo "<optgroup label='{$category}'>";
        foreach ($category_events as $e) {
            $e_date = new DateTime($e['event_date']);
            $e_date->modify('-1 day');
            $last_date = $e_date->format('Y-m-d');

            $is_closed = ($current_date > $last_date);
            $disabled = $is_closed ? "disabled" : "";
            $start_time = strtolower(date('ga', strtotime($e['event_time'])));
            $venue_time = " | {$e['venue']} ({$start_time})";
            $label = $is_closed ? "{$e['event_name']} (Closed)" : $e['event_name'] . $venue_time;

            $selected = (!$is_closed && $e['event_name'] === $selected_event) ? 'selected' : '';
            echo "<option value='{$e['event_name']}' {$selected} {$disabled}>{$label}</option>";
        }
        echo "</optgroup>";
    }
?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn">Confirm Registration</button>
        </form>
        <?php
endif; ?>
    </div>
</div>

<script>
function toggleOtherDept() {
    var select = document.getElementById('deptSelect');
    var otherGroup = document.getElementById('otherDeptGroup');
    if(select.value === 'Other') {
        otherGroup.style.display = 'block';
        otherGroup.querySelector('input').setAttribute('required', 'required');
    } else {
        otherGroup.style.display = 'none';
        otherGroup.querySelector('input').removeAttribute('required');
    }
}
</script>
</body>
</html>