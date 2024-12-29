<?php
// Start the session
session_start();

// Include the login page's HTML
include 'login.html';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include helper files and establish a database connection
    require('login_functions.inc.php');
    $dbc = @mysqli_connect("localhost", "root", "", "alimentadb") OR die('Could not connect to MySQL: ' . mysqli_connect_error());

    // Determine if the login is for user or admin
    $isAdmin = isset($_POST['admin_email']);

    if ($isAdmin) {
        // Admin login logic
        list($check, $data) = check_admin_login($dbc, $_POST['admin_email'], $_POST['admin_pass']);
    } else {
        // User login logic
        list($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);
    }

    if ($check) {
        // Login successful
        $_SESSION['user_id'] = $data['user_id'] ?? $data['admin_id'];
        $_SESSION['first_name'] = $data['first_name'];

        // Determine redirection and modal message
        $redirectPage = $isAdmin ? 'admin.php' : 'homepage.php';
        $successMessage = "You have successfully logged in!";

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('Success!', '$successMessage', true, '$redirectPage');
            });
        </script>";
    } else {
        // Login failed, show error messages
        $errors = $data;
        $errorList = implode('<br>', $errors);
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('Error!', '$errorList', false);
            });
        </script>";
    }

    // Close the database connection
    mysqli_close($dbc);
}

// Create the page:
include('login_page.inc.php');
?>
