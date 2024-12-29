<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Your Password</title>
    <link rel="stylesheet" href="account.css"> 
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
            <button class="donate-button">Donate Now ></button>
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
        <div class="account-info">
            <div class="details-card">
                <h2>Change Your Password</h2>
                <form action="password.php" method="post">
                    <label for="email">Email Address:</label>
                    <input type="text" id="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                    
                    <label for="current-pass">Current Password:</label>
                    <input type="password" id="current-pass" name="pass" size="20" maxlength="255">
                    
                    <label for="new-pass">New Password:</label>
                    <input type="password" id="new-pass" name="pass1" size="20" maxlength="255">
                    
                    <label for="confirm-pass">Confirm New Password:</label>
                    <input type="password" id="confirm-pass" name="pass2" size="20" maxlength="255">
                    
                    <div class="buttons">
                        <button type="submit" class="button">Update Password</button>
                        <a href="account.php" class="button">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    <!-- Pop-up Modal -->
    <div id="popupModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h1 id="popupTitle"></h1>
            <p id="popupMessage"></p>
            <div class="modal-buttons" id="popupButtons">
                <!-- Buttons will be dynamically added here -->
            </div>
        </div>
    </div> 

    <script>
        function showPopup(title, message, isError = false) {
            document.getElementById('popupTitle').textContent = title;
            document.getElementById('popupMessage').textContent = message;

            const buttonsContainer = document.getElementById('popupButtons');
            buttonsContainer.innerHTML = ''; // Clear existing buttons

            if (isError) {
                // Add "Try Again" and "Account Details" buttons for errors
                const tryAgainButton = document.createElement('a');
                tryAgainButton.href = "password.php";
                tryAgainButton.className = 'button';
                tryAgainButton.textContent = 'Try Again';
                buttonsContainer.appendChild(tryAgainButton);

                const accountDetailsButton = document.createElement('a');
                accountDetailsButton.href = "account.php";
                accountDetailsButton.className = 'button';
                accountDetailsButton.textContent = 'Account Details';
                buttonsContainer.appendChild(accountDetailsButton);
            } else {
                // Add success buttons
                const goHomeButton = document.createElement('a');
                goHomeButton.href = "homepage.php";
                goHomeButton.className = 'button';
                goHomeButton.textContent = 'Go to Homepage';
                buttonsContainer.appendChild(goHomeButton);

                const accountDetailsButton = document.createElement('a');
                accountDetailsButton.href = "account.php";
                accountDetailsButton.className = 'button';
                accountDetailsButton.textContent = 'Account Details';
                buttonsContainer.appendChild(accountDetailsButton);
            }

            document.getElementById('popupModal').style.display = 'flex';
        }
    </script>
</body>
</html>

<?php
// This page lets a user change their password.
$page_title = 'Change Your Password';

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('mysqli_connect.php'); // Connect to the database.
    
    $errors = array(); // Initialize an error array.

    // Validate email:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }

    // Check for the current password:
    if (empty($_POST['pass'])) {
        $errors[] = 'You forgot to enter your current password.';
    } else {
        $current_pass = mysqli_real_escape_string($dbc, trim($_POST['pass']));
    }
    
    // Check for a new password and match against the confirmed password:
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your new password did not match the confirmed password.';
        } else {
            $new_pass = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'You forgot to enter your new password.';
    }
    
    if (empty($errors)) { // If everything's OK.
        
        // Verify the current password and email:
        $q = "SELECT user_id FROM users WHERE (email='$e' AND pass=SHA1('$current_pass') )";
        $r = @mysqli_query($dbc, $q);
        $num = @mysqli_num_rows($r);
        
        if ($num == 1) { // Match was made.
            
            // Get the user_id:
            $row = mysqli_fetch_array($r, MYSQLI_NUM);
            
            // Update the password:
            $q = "UPDATE users SET pass=SHA1('$new_pass') WHERE user_id=$row[0]";
            $r = @mysqli_query($dbc, $q);
            
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
                echo "<script>
                    showPopup('Success!', 'Your password has been updated successfully.');
                </script>";
            } else { // If it did not run OK.
                echo "<script>
                    showPopup('System Error', 'Your password could not be changed due to a system error. Please try again later.', true);
                </script>";
            }
            
        } else { // Invalid email/password combination.
            echo "<script>
                showPopup('Error!', 'The email address and current password do not match those on file.', true);
            </script>";
        }
        
    } else { // Report the errors.
        echo "<script>
            showPopup('Error!', 'The following error(s) occurred: " . implode('<br />', $errors) . "', true);
        </script>";
    }
    
    mysqli_close($dbc); // Close the database connection.
}
?>
