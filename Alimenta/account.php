<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header('Location: login.php');
    exit();
}

// Connect to the database
require('mysqli_connect.php');

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name, $email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function enableEdit() {
            // Make the input fields editable
            document.getElementById("first-name").disabled = false;
            document.getElementById("last-name").disabled = false;
            document.getElementById("email").disabled = false;

            // Show Save Changes and Cancel buttons, hide Edit Info button
            document.getElementById("edit-info").style.display = "none";
            document.getElementById("save-changes").style.display = "inline-block";
            document.getElementById("cancel-edit").style.display = "inline-block";

            // Hide other buttons (if needed)
            document.getElementById("delete-account").style.display = "none";
            document.getElementById("change-password").style.display = "inline-block";  // Show the Change Password button
        }

        function cancelEdit() {
            // Reload the page to reset fields
            location.reload();
        }
    </script>
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

    <main class="account-info">
        <div class="details-card">
            <h2>Personal Details</h2>
            <form action="update_account.php" method="POST">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" disabled>

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" disabled>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>

                <label for="password">Password</label>
                <input type="password" id="password" value="********" disabled>

                <div class="buttons">
                    <button type="button" id="edit-info" class="button" onclick="enableEdit()">Edit Info</button>
                    <button type="button" id="delete-account" class="button"><a href="delete_account.php">Delete Account</a></button>

                    <!-- These buttons will appear after clicking Edit Info -->
                    <button type="button" id="change-password" class="button" style="display: none;"><a href="password.php">Change Password</a></button>
                    <button type="submit" id="save-changes" class="button" style="display: none;">Save Changes</button>
                    <button type="button" id="cancel-edit" class="button" style="display: none;" onclick="cancelEdit()">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
