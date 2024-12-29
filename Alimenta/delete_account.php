<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require('mysqli_connect.php'); // Database connection

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Handle form submission for account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete related notifications first
    $query = "DELETE FROM notifications WHERE user_id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Delete user data from the database
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $delete_success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbc);

    // If the deletion is successful, log the user out and redirect to the login page
    if ($delete_success) {
        session_destroy(); // Destroy the session
        header('Location: homepage.php'); // Redirect to login
        exit();
    } else {
        // In case of an error, show a message
        $error_message = "Error deleting account. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="account.css">
    <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #8A8D56;
            margin: 0;
            padding: 0;
        }
        	header {
	    display: flex;
	    align-items: center;
	    justify-content: space-between;
	    background-color: #f7e2cc;
	    padding: 10px 30px;
	    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}
	
	.logo-container {
	    display: flex;
	    align-items: center;    /* Center-align the items */
	}
	
	.logo img {
	    height: 100px;
	}
	
	.brand-name {
	    font-size: 27px;
	    font-weight: bold;
	    margin-left: 10px;
	    color: #6b6048;
	}
	
	nav {
	    display: flex;
	    align-items: center;
	    justify-content: space-between;
	    padding: 20px 50px;
	    font-size: 24px;
	}
	
	nav a {
	    text-decoration: none;
	    margin: 0 15px;
	    padding: 20px 20px;
	    color: #6b6048;
	    font-weight: bold;
	    font-size: 24px;
	    border: 2px solid transparent; /* Box for buttons */
		border-radius: 8px; /* Rounded corners */
		transition: all 0.2s ease;
	}
	
	
	nav a:hover {
	    color: white;
	    background-color: #8A8D56; 
	    border-color: #8A8D56; 
	}
	
	nav .donate-button {
		margin-left: 15px;	
		margin-right: 15px;
	    background-color: #8A8D56;
	    border: none;
	    padding: 20px 20px;
	    color: #fff;
	    font-size: 24px;
	    border-radius: 20px;
	    cursor: pointer;
	}
	
	nav .donate-button:hover {
	    background-color: #d9c5a1;
	    border-color: #d9c5a1;
	}
	
	/* Profile Button with Dropdown */
	.profile-container {
	    position: relative; /* Ensure dropdown is positioned relative to this container */
	    margin-left: auto;
	    padding: 0;
	}
	
	.profile {
	    padding: 20px 20px;
	    font-size: 24px;
	    font-weight: bold;
	    color: #6b6048;
	    border: 2px solid transparent;
		border-radius: 8px;
		cursor: pointer;
		background-color: transparent;
	}
	
	.profile:hover {
	    background-color: #8A8D56;
	    color: white;
	    border-color: #8A8D56;
	    transition: all 0.2s ease;
	}
	
	.dropdown-menu {
	    position: absolute;
	    top: 100%; /* Position below the profile button */
	    right: 0;
	    background-color: white;
	    color: white;
	    border-radius: 8px;
	    box-shadow: 0 2px 4px rgba(0, 0, 0, 2);
	    align-items: center;
	    display: none; /* Hidden by default */
	    flex-direction: column;
	    min-width: 200px;
	    z-index: 1000;
	    overflow: hidden;
	}
	
	.dropdown-menu a {
	    padding: 15px 20px;
	    text-decoration: none;
	    color: #6b6048;
	    font-size: 18px;
	    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
	    transition: background-color 0.3s ease;
	}
	
	.dropdown-menu a:last-child {
	    border-bottom: none; /* Remove border for last item */
	}
	
	.dropdown-menu a:hover {
	    background-color: #6b6048;
	}
	
	.profile-container:hover .dropdown-menu {
	    display: flex; /* Show dropdown menu on hover */
	}
	
        .details-card {
            max-width: 500px;
            margin: 100px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .details-card h2 {
            margin-bottom: 20px;
        }
        .details-card p {
            margin-bottom: 30px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button.delete {
            background-color: #e74c3c;
        }
        .button.delete:hover {
            background-color: #c0392b;
        }
        .button.cancel {
            background-color: #3498db;
        }
        .button.cancel:hover {
            background-color: #2980b9;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
    </style>
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
    
    <main class="account-info">
        <div class="details-card">
            <h2>Are you sure you want to delete your account?</h2>
            <p>This action is irreversible. All your data will be permanently removed.</p>

            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <div class="button-container">
                <form action="delete_account.php" method="POST" style="margin: 0;">
                    <button type="submit" class="button delete">Delete Account</button>
                </form>
                <a href="account.php" class="button cancel">Cancel</a>
            </div>
        </div>
    </main>
</body>
</html>
