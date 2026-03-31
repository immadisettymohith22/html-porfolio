<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}

$email = $_SESSION['student_email'];
$query = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
$student = mysqli_fetch_assoc($query);

$success = "";
$error = "";

if(isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    $update = mysqli_query($conn, "UPDATE students SET 
        name='$name', 
        mobile='$mobile', 
        department='$department', 
        year='$year' 
        WHERE email='$email'");

    if($update){
        $_SESSION['student_name'] = $name; // Update session name
        $success = "Profile updated successfully!";
        // Refresh student data
        $query = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
        $student = mysqli_fetch_assoc($query);
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | One Piece Academic Portal</title>
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
            <a href="student_dashboard.php" class="sidebar-item">
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
            <a href="student_profile.php" class="sidebar-item active">
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
        <header class="dashboard-top">
            <div class="page-title">
                <h2 style="color: var(--text-main); margin: 0;"><i class="fas fa-user-cog" style="color: var(--primary); margin-right: 10px;"></i> Profile Settings</h2>
            </div>
            
            <div class="user-profile-nav">
                <button id="theme-toggle" class="theme-toggle-dashboard" title="Toggle Theme">
                    <i class="fas fa-sun"></i>
                </button>
                <div class="user-profile-badge">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['name']); ?>&background=818cf8&color=fff" alt="User Avatar" class="user-avatar">
                    <div class="user-info-text">
                        <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                        <p><?php echo htmlspecialchars($student['email']); ?></p>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-grid">
            <div class="main-content-area">
                <div class="glass-card" style="max-width: 600px; margin: 0 auto; padding: 2.5rem;">
                    <h3 style="margin-bottom: 2rem; color: var(--text-main); border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        Update Your Information
                    </h3>

                    <?php if($success): ?>
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="profile-form">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required 
                                   style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-main);">
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Email (Read-only)</label>
                            <input type="email" value="<?php echo htmlspecialchars($student['email']); ?>" readonly 
                                   style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-muted); cursor: not-allowed;">
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Roll Number</label>
                            <input type="text" value="<?php echo htmlspecialchars($student['rollno']); ?>" readonly 
                                   style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-muted); cursor: not-allowed;">
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Mobile Number</label>
                                <input type="tel" name="mobile" value="<?php echo htmlspecialchars($student['mobile']); ?>" required 
                                       style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-main);">
                            </div>
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Year</label>
                                <select name="year" required style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-main);">
                                    <option value="1" <?php if($student['year'] == '1') echo 'selected'; ?>>1st Year</option>
                                    <option value="2" <?php if($student['year'] == '2') echo 'selected'; ?>>2nd Year</option>
                                    <option value="3" <?php if($student['year'] == '3') echo 'selected'; ?>>3rd Year</option>
                                    <option value="4" <?php if($student['year'] == '4') echo 'selected'; ?>>4th Year</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Department</label>
                            <select name="department" required style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-main);">
                                <option value="CSE" <?php if($student['department'] == 'CSE') echo 'selected'; ?>>CSE</option>
                                <option value="ECE" <?php if($student['department'] == 'ECE') echo 'selected'; ?>>ECE</option>
                                <option value="EEE" <?php if($student['department'] == 'EEE') echo 'selected'; ?>>EEE</option>
                                <option value="Bio Medical" <?php if($student['department'] == 'Bio Medical') echo 'selected'; ?>>Bio Medical</option>
                                <option value="Other" <?php if(!in_array($student['department'], ['CSE','ECE','EEE','Bio Medical'])) echo 'selected'; ?>>Other</option>
                            </select>
                        </div>

                        <button type="submit" name="update_profile" class="btn" style="width: 100%; padding: 1rem; font-weight: 600;">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
