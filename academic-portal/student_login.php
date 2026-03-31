<?php
session_start();
include 'connect.php';

// LOGIN LOGIC
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM students 
    WHERE email='$email' AND password='$password'");

    if(mysqli_num_rows($result) == 1){
        $student = mysqli_fetch_assoc($result);
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_email'] = $student['email'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid Login Credentials!";
    }
}

// REGISTRATION LOGIC
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $rollno = $_POST['rollno'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    $password = md5($_POST['password']);

    $check_email = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0){
        $error = "Email already registered!";
    } else {
        mysqli_query($conn, "INSERT INTO students (name, rollno, email, mobile, year, department, password)
        VALUES ('$name', '$rollno', '$email', '$mobile', '$year', '$department', '$password')");        $success = "Registration Successful! Please login below.";
    }
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

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<!-- TOP INFO BANNER -->
<div class="glass-card auth-info-panel" style="width: 100%; padding: 0.4rem 2rem; border-radius: 0; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; border-left: none; border-right: none; border-top: none; margin-bottom: 0;">
    <div style="max-width: 1200px; width: 100%; display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center;">
        <!-- Instructions Column -->
        <div style="flex: 1; min-width: 300px;">
            <h3 style="color: var(--gold); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 6px; font-size: 0.85rem;">
                <i class="fas fa-info-circle"></i> Instructions
            </h3>
            <ul style="list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 0.2rem 1rem;">
                <li style="color: var(--text-main); display: flex; gap: 6px; font-size: 0.75rem;">
                    <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 3px; font-size: 0.65rem;"></i>
                    <span>Enter registered email</span>
                </li>
                <li style="color: var(--text-main); display: flex; gap: 6px; font-size: 0.75rem;">
                    <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 3px; font-size: 0.65rem;"></i>
                    <span>Case-sensitive password</span>
                </li>
                <li style="color: var(--text-main); display: flex; gap: 6px; font-size: 0.75rem;">
                    <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 3px; font-size: 0.65rem;"></i>
                    <span>Click “Login to Portal”</span>
                </li>
                <li style="color: var(--text-main); display: flex; gap: 6px; font-size: 0.75rem;">
                    <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 3px; font-size: 0.65rem;"></i>
                    <span>New? “Register Here”</span>
                </li>
            </ul>
        </div>

        <!-- Notes Column -->
        <div style="flex: 0 1 350px;">
            <h3 style="color: var(--accent); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 6px; font-size: 0.85rem;">
                <i class="fas fa-exclamation-triangle"></i> Security & Support
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.2rem 1rem;">
                <div style="color: var(--text-muted); display: flex; gap: 8px; font-size: 0.75rem;">
                    <i class="fas fa-shield-halved" style="color: var(--accent); margin-top: 2px; font-size: 0.7rem;"></i>
                    <span>Secure credentials</span>
                </div>
                <div style="color: var(--text-muted); display: flex; gap: 8px; font-size: 0.75rem;">
                    <i class="fas fa-wifi" style="color: var(--accent); margin-top: 2px; font-size: 0.7rem;"></i>
                    <span>Stable connection</span>
                </div>
                <div style="color: var(--text-muted); display: flex; gap: 8px; font-size: 0.75rem;">
                    <i class="fas fa-envelope-circle-check" style="color: var(--accent); margin-top: 2px; font-size: 0.7rem;"></i>
                    <span>Registered email only</span>
                </div>
                <div style="color: var(--text-muted); display: flex; gap: 8px; font-size: 0.75rem;">
                    <i class="fas fa-headset" style="color: var(--accent); margin-top: 2px; font-size: 0.7rem;"></i>
                    <span>Admin for issues</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="auth-container" style="display: flex; flex-direction: column; align-items: center; gap: 0; padding: 1.5rem 1.5rem; max-width: 1200px; margin: 0 auto; min-height: auto;">
 
    <!-- AUTH CARD (RIGHT SIDE) -->
    <div class="glass-card auth-card" style="flex: 0 1 500px; min-height: auto; position: relative; overflow: visible; padding: 3rem 2.5rem; border-radius: 20px; margin: 0 auto; border-top: 3px solid var(--glass-border);">
        
        <!-- LOGIN SECTION -->
        <div id="login-section">
            <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 15px; font-size: 2.2rem;">
                <i class="fas fa-sign-in-alt"></i> Student Login
            </h2>
            
            <?php if(isset($error)) { ?>
                <div style="background: rgba(244, 63, 94, 0.1); border: 1px solid var(--accent); color: var(--accent); padding: 0.8rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php } ?>

            <?php if(isset($success)) { ?>
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--cat-sports); color: var(--cat-sports); padding: 0.8rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem;">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="student@example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••" required>
                </div>
                <button type="submit" name="login" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">Login to Portal</button>
            </form>

            <div style="text-align: center; margin-top: 2rem;">
                <p style="color: var(--text-muted); font-size: 0.95rem;">New Student? <a href="javascript:void(0)" onclick="toggleAuth('register')" style="color: var(--primary); text-decoration: none; font-weight: 700;">Register Here</a></p>
                <a href="index.php" style="display: inline-block; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.9rem;"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>

        <!-- REGISTER SECTION (HIDDEN BY DEFAULT) -->
        <div id="register-section" style="display: none;">
            <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 15px; font-size: 2.2rem;">
                <i class="fas fa-user-plus"></i> Create Account
            </h2>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Roll Number</label>
                    <input type="text" name="rollno" placeholder="e.g. 21CS001" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="student@example.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Department</label>
                    <select name="department" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 0.8rem; font-family: inherit;">
                        <option value="" style="background: var(--bg-card);">-- Select Department --</option>
                        <option value="CSE" style="background: var(--bg-card);">CSE</option>
                        <option value="ECE" style="background: var(--bg-card);">ECE</option>
                        <option value="EEE" style="background: var(--bg-card);">EEE</option>
                        <option value="Bio Medical" style="background: var(--bg-card);">Bio Medical</option>
                        <option value="Other" style="background: var(--bg-card);">Other</option>
                    </select>
                </div>
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label><i class="fas fa-phone"></i> Mobile</label>
                        <input type="tel" name="mobile" value="+91 " placeholder="Number" required pattern="\+91\s?[0-9]{10}" maxlength="14">
                    </div>
                    <div>
                        <label><i class="fas fa-graduation-cap"></i> Year</label>
                        <select name="year" required style="background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 100%; padding: 0.8rem; font-family: inherit;">
                            <option value="" style="background: var(--bg-card);">Year</option>
                            <option value="1" style="background: var(--bg-card);">1st</option>
                            <option value="2" style="background: var(--bg-card);">2nd</option>
                            <option value="3" style="background: var(--bg-card);">3rd</option>
                            <option value="4" style="background: var(--bg-card);">4th</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="••••" required>
                </div>
                <button type="submit" name="register" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">Create Account</button>
            </form>
            <div style="text-align: center; margin-top: 2rem;">
                <p style="color: var(--text-muted); font-size: 0.95rem;">Already have account? <a href="javascript:void(0)" onclick="toggleAuth('login')" style="color: var(--primary); text-decoration: none; font-weight: 700;">Login Here</a></p>
            </div>
        </div>

    </div>

</div>

<script>
function toggleAuth(type) {
    const loginSec = document.getElementById('login-section');
    const registerSec = document.getElementById('register-section');
    
    if(type === 'register') {
        loginSec.style.display = 'none';
        registerSec.style.display = 'block';
    } else {
        loginSec.style.display = 'block';
        registerSec.style.display = 'none';
    }
}
</script>

</body>
</html>