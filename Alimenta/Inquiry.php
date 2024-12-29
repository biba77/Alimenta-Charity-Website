<?php 
$page_title = 'Contact Us';
include('inquiry.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require ('mysqli_connect.php'); // Connect to the database.
    $errors = array(); // Initialize an error array.

    // Validate form inputs (same as before)
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    }

    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }

    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }

    if (empty($_POST['phone_number'])) {
        $errors[] = 'You forgot to enter your phone number.';
    } else {
        $phone = mysqli_real_escape_string($dbc, trim($_POST['phone_number']));
    }

    if (empty($_POST['message'])) {
        $errors[] = 'You forgot to enter your message.';
    } else {
        $message = mysqli_real_escape_string($dbc, trim($_POST['message']));
    }

    if (empty($errors)) { // If everything is OK.
        $q = "INSERT INTO inquiries (first_name, last_name, email, phone_number, message, submitted_at) 
              VALUES ('$fn', '$ln', '$e', '$phone', '$message', NOW())";
        $r = @mysqli_query($dbc, $q); // Run the query.

        if ($r) { // If it ran OK.
            // Success modal with Close option
            echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    showModal(
                        "Success!", 
                        "Your inquiry has been submitted successfully!", 
                        true,
                        "Close",
                        "inquiry.php" // Redirect to refreshed page
                    );
                });
            </script>';
        
        } else { 
            // System error
            echo '<h1>System Error</h1>
            <p class="error">Your inquiry could not be submitted due to a system error. We apologize for any inconvenience.</p>'; 
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
        }

    } else { 
        // Error modal with Try Again option
        $errorList = implode('<br>', $errors);
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showModal(
                    "Error!", 
                    "' . $errorList . '", 
                    false,
                    "Try Again",
                    "" // Stay on the same page
                );
            });
        </script>';
    }

    mysqli_close($dbc); // Close the database connection.
}
?>