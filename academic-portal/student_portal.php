<?php
session_start();
include 'connect.php';

/* -------------------------
   STUDENT REGISTER
------------------------- */
if(isset($_POST['student_register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    $password = md5($_POST['password']);

    // Check for duplicate email
    $check_email = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0){
        header("Location: 505.php");
        exit();
    }

    mysqli_query($conn, "INSERT INTO students (name, email, mobile, year, department, password)
    VALUES ('$name', '$email', '$mobile', '$year', '$department', '$password')");

    echo "<script>alert('Registration Successful! Please Login.');</script>";
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
        echo "<script>alert('Invalid Login');</script>";
    }
}

/* -------------------------
   EVENT REGISTRATION
------------------------- */
if(isset($_POST['event_register']) && isset($_SESSION['student_email'])){

    $name = $_SESSION['student_name'];
    $email = $_SESSION['student_email'];
    $rollno = $_POST['rollno'];
    $department = $_POST['department'];
    if($department == "Other") {
        $department = $_POST['other_department'];
    }
    $event = $_POST['event'];

    // Check 1: Is the student already registered for THIS event?
    $check_dup = mysqli_query($conn, "SELECT * FROM registrations WHERE rollno='$rollno' AND event='$event'");
    if(mysqli_num_rows($check_dup) > 0) {
        echo "<script>alert('Error: You are already registered for this event!'); window.history.back();</script>";
        exit();
    }

    // Check 2: Has the student already registered for 3 events?
    $check_limit = mysqli_query($conn, "SELECT * FROM registrations WHERE email='$email'");
    if(mysqli_num_rows($check_limit) >= 3) {
        echo "<script>alert('Error: You can only register for a maximum of 3 events!'); window.history.back();</script>";
        exit();
    }

    // Check 3: Is this roll number already linked to another email?
    $check_rollno = mysqli_query($conn, "SELECT * FROM registrations WHERE rollno='$rollno' AND email != '$email'");
    if(mysqli_num_rows($check_rollno) > 0) {
        header("Location: 505.php");
        exit();
    }

    // Fetch mobile and year from student account
    $stu_query = mysqli_query($conn, "SELECT mobile, year FROM students WHERE email='$email'");
    $stu_data = mysqli_fetch_assoc($stu_query);
    $mobile = $stu_data['mobile'];
    $year = $stu_data['year'];

    mysqli_query($conn, "INSERT INTO registrations
    (name,rollno,email,department,year,mobile,event)
    VALUES ('$name','$rollno','$email','$department','$year','$mobile','$event')");

    echo "<script>alert('Event Registered Successfully!'); window.location.href='student_dashboard.php';</script>";
    exit();
}

/* -------------------------
   LOGOUT
------------------------- */
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: portal.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<h2 style="text-align:center;">One Piece Academic Portal</h2>

<?php if(!isset($_SESSION['student_email'])){ ?>

<div class="auth-container">
    <div class="glass-card auth-card">
        
        <!-- Toggle Options -->
        <div style="display: flex; gap: 10px; margin-bottom: 2rem; padding: 5px; background: rgba(255,255,255,0.05); border-radius: 12px;">
            <button id="toggle-login" class="btn" style="flex: 1; padding: 0.8rem; margin: 0; border-radius: 8px;">Login</button>
            <button id="toggle-register" class="btn" style="flex: 1; padding: 0.8rem; margin: 0; border-radius: 8px; background: transparent; color: var(--text-main); box-shadow: none;">Register</button>
        </div>

        <!-- LOGIN FORM -->
        <div id="form-login">
            <h2><i class="fas fa-sign-in-alt"></i> Student Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="student@example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="student_login" class="btn">Login to Portal</button>
            </form>
        </div>

        <!-- REGISTER FORM (Hidden by Default) -->
        <div id="form-register" style="display: none;">
            <h2><i class="fas fa-user-plus"></i> Create Account</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="student@example.com" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" value="+91 " placeholder="Enter mobile number" required pattern="\+91\s?[0-9]{10}" maxlength="14">
                </div>
                <div class="form-group">
                    <label>Year of Study</label>
                    <select name="year" required>
                        <option value="">-- Select Year --</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" required>
                        <option value="">-- Select Department --</option>
                        <option value="CSE">CSE</option>
                        <option value="ECE">ECE</option>
                        <option value="EEE">EEE</option>
                        <option value="Bio Medical">Bio Medical</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="student_register" class="btn">Create Account</button>
            </form>
        </div>
        
        <p style="text-align: center; margin-top: 2rem;">
            <a href="index.php" style="color: var(--text-muted); text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </p>
    </div>
</div>

<script>
    const btnLogin = document.getElementById('toggle-login');
    const btnRegister = document.getElementById('toggle-register');
    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');

    btnLogin.addEventListener('click', () => {
        formLogin.style.display = 'block';
        formRegister.style.display = 'none';
        btnLogin.style.background = 'var(--primary)';
        btnLogin.style.boxShadow = '';
        btnRegister.style.background = 'transparent';
        btnRegister.style.boxShadow = 'none';
    });

    btnRegister.addEventListener('click', () => {
        formRegister.style.display = 'block';
        formLogin.style.display = 'none';
        btnRegister.style.background = 'var(--primary)';
        btnRegister.style.boxShadow = '';
        btnLogin.style.background = 'transparent';
        btnLogin.style.boxShadow = 'none';
    });
</script>

<?php } else { ?>

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            Welcome, <?php echo $_SESSION['student_name']; ?> 👋
        </span>
    </div>
    <div class="nav-links">
        <a href="events.php">View Events</a>
        <a href="?logout=true" style="color: var(--accent);"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="auth-container">
    <div class="glass-card auth-card" style="width: 100%; max-width: 500px;">
        <h2><i class="fas fa-file-signature"></i> Register for Event</h2>
        <?php
        $sess_email = $_SESSION['student_email'];
        $count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations WHERE email='$sess_email'");
        $count_data = mysqli_fetch_assoc($count_query);
        if($count_data['total'] >= 3):
        ?>
            <div style="text-align: center; padding: 2rem 0;">
                <i class="fas fa-exclamation-triangle fa-3x" style="color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-main); margin-bottom: 1rem;">Registration Limit Crossed</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">You have already registered for the maximum of 3 events allowed per student.</p>
                <a href="student_dashboard.php" class="btn" style="margin-top: 2rem;">Go to Dashboard</a>
            </div>
        <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label>Roll Number</label>
                <input type="text" name="rollno" placeholder="Enter roll no" required>
            </div>
            <div class="form-group">
                <label>Department</label>
                <?php
                $sess_email = $_SESSION['student_email'];
                $stu_info_res = mysqli_query($conn, "SELECT department FROM students WHERE email='$sess_email'");
                $stu_info_row = mysqli_fetch_assoc($stu_info_res);
                $saved_dept = $stu_info_row['department'] ?? '';
                ?>
                <select name="department" id="deptSelectPortal" onchange="toggleOtherDeptPortal()" required>
                    <option value="">-- Select Department --</option>
                    <option value="CSE" <?php if($saved_dept == 'CSE') echo 'selected'; ?>>CSE</option>
                    <option value="ECE" <?php if($saved_dept == 'ECE') echo 'selected'; ?>>ECE</option>
                    <option value="EEE" <?php if($saved_dept == 'EEE') echo 'selected'; ?>>EEE</option>
                    <option value="Bio Medical" <?php if($saved_dept == 'Bio Medical') echo 'selected'; ?>>Bio Medical</option>
                    <option value="Other" <?php if(!empty($saved_dept) && !in_array($saved_dept, ['CSE','ECE','EEE','Bio Medical'])) echo 'selected'; ?>>Other (Please specify)</option>
                </select>
            </div>
            <div class="form-group" id="otherDeptGroupPortal" style="display: none;">
                <label>Specify Department</label>
                <input type="text" name="other_department" placeholder="Enter department">
            </div>

            <script>
            function toggleOtherDeptPortal() {
                var select = document.getElementById('deptSelectPortal');
                var otherGroup = document.getElementById('otherDeptGroupPortal');
                if(select.value === 'Other') {
                    otherGroup.style.display = 'block';
                    otherGroup.querySelector('input').setAttribute('required', 'required');
                } else {
                    otherGroup.style.display = 'none';
                    otherGroup.querySelector('input').removeAttribute('required');
                }
            }
            </script>
            <div class="form-group">
                <label>Select Event</label>
                <select name="event" required>
                    <option value="">-- Choose an Event --</option>
                    <?php
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
                            $venue_time = " | {$e['venue']} ({$e['event_time']})";
                            $label = $is_closed ? "{$e['event_name']} (Closed)" : $e['event_name'] . $venue_time;
                            
                            echo "<option value='{$e['event_name']}' {$disabled}>{$label}</option>";
                        }
                        echo "</optgroup>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="event_register" class="btn">Register Event</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php } ?>

</body>
</html>