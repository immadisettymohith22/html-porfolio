<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>505 Error - Duplicate Details | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="img2.png" alt="One Piece Logo" class="logo-img"> One Piece Academic Portal Error
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<div class="auth-container">
    <div class="glass-card auth-card" style="text-align: center; max-width: 500px;">
        <i class="fas fa-exclamation-triangle fa-4x" style="color: var(--accent); margin-bottom: 1.5rem;"></i>
        <h1 style="color: var(--text-main); font-size: 2.5rem; margin-bottom: 1rem;">505 Error</h1>
        <h3 style="color: var(--text-muted); margin-bottom: 2rem;">Duplicate Details Detected</h3>
        
        <p style="color: var(--text-main); line-height: 1.6; margin-bottom: 2rem;">
            The email address or roll number you provided is already associated with another student account. Each student must have a unique email and roll number.
        </p>
        
        <button onclick="window.history.back()" class="btn"><i class="fas fa-arrow-left"></i> Go Back</button>
    </div>
</div>

</body>
</html>
