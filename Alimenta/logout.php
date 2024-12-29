<?php
session_start(); // Start the session

$logout_success = false;

// Check if session data exists to determine if the user was logged in
if (isset($_SESSION['user_id'])) {
    session_unset();  // Clear session data
    session_destroy();  // Destroy the session
    $logout_success = true; // Logout was successful
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            background-color: #8A8D56;
        }

        /* Modal container */
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            margin: 0;
            color: #333;
        }

        .modal-content p {
            margin: 10px 0 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Modal container -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <?php if ($logout_success): ?>
                <h3>Logout Successful</h3>
                <p>You have successfully logged out.</p>
            <?php else: ?>
                <h3>Logout Unsuccessful</h3>
                <p>You were not logged in.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-close modal and redirect to homepage after 2.5 seconds
        setTimeout(() => {
            window.location.href = 'homepage.php';
        }, 2500);
    </script>
</body>
</html>
