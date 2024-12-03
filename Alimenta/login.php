<?php
// This page processes the login form submission.
// The script uses sessions.
include 'login.html';

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Need two helper files:
    require ('login_functions.inc.php'); // Connect to the db.

    // Need the database connection:
    $dbc = @mysqli_connect ("localhost", "root", "", "alimentadb") OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

    // Check the login:
    list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

    if ($check) { // OK!
        // Set the session data:
        session_start();
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];

        // Show success modal using JavaScript:
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showModal("Success!", "You have successfully logged in!", true);
            });
        </script>';
    } else { // Unsuccessful!
        // Assign $data to $errors for login_page.inc.php:
        $errors = $data;

        // Show error modal with the messages:
        $errorList = implode('<br>', $errors);
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showModal("Error!", "' . $errorList . '", false);
            });
        </script>';
    }

    mysqli_close($dbc); // Close the database connection.

} // End of the main submit conditional.

// Create the page:
include ('login_page.inc.php');
?>
