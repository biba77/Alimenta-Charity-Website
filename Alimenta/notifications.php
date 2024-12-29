<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require('mysqli_connect.php'); // Connect to the database
$user_id = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$q = "SELECT notification_text, created_at FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC";
$r = @mysqli_query($dbc, $q);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Alimenta</title>
    <link rel="stylesheet" href="notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="logo-container">
        <div class="logo">
            <img src="Alimenta Logo.png" alt="Alimenta Logo">
        </div>
        <span class="brand-name">Alimenta</span>
    </div>
    <nav>
        <a href="homepage.php">Home</a>
        <a href="notifications.php">Notifications</a>
        <a href="volunteer.php">Volunteer</a>
        <a href="inquiry.php">Contact Us</a>
        <button class="donate-button" onclick="window.location.href='donations.php'">Donate Now ></button>
        <div class="profile-container">
            <button class="profile">
                <i class="fas fa-user-circle"></i> Profile
            </button>
            <div class="dropdown-menu">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="account.php" style="width: 100%; text-align: center;">Account</a>
            <a href="logout.php" style="width: 100%; text-align: center;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="width: 100%; text-align: center;">Login</a>
            <a href="signup.php" style="width: 100%; text-align: center;">Signup</a>
        <?php endif; ?>
		    </div>
        </div>
    </nav>
</header> 

<main>
    <div class="notification-container">
        <h1>Notifications</h1>
        <?php
        if ($r && mysqli_num_rows($r) > 0) {
            // Display each notification
            while ($row = mysqli_fetch_assoc($r)) {
                echo '<div class="notification-card">';
                echo '<h2>New Notification</h2>';
                echo '<p>' . htmlspecialchars($row['notification_text']) . '</p>';
                echo '<small>' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</small>';
                echo '</div>';
            }
        } else {
            echo '<p>No notifications to display.</p>';
        }
        mysqli_close($dbc); // Close the database connection
        ?>
    </div>
</main>

</body>
</html>

</body>
</html>
