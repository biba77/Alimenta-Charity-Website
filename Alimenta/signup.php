<?php 
$page_title = 'Sign Up';
include('signup.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require ('mysqli_connect.php'); // Connect to the database.
    $errors = array(); // Initialize an error array.
    
    // Check for a first name:
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    }
    
    // Check for a last name:
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }
    
    // Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }
    
    // Check for a password and match against the confirmed password:
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }
    
    if (empty($errors)) { // If everything is OK.
        $q = "INSERT INTO users (first_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";        
        $r = @mysqli_query ($dbc, $q); // Run the query.
        
        if ($r) { // If it ran OK.
            // Show success modal using JavaScript.
            echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    showModal("Success!", "You have successfully registered!", true);
                });
            </script>';
        
        } else { 
            echo '<h1>System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
        }
        
        mysqli_close($dbc); // Close the database connection.
        exit();
        
    } else { 
        // Report the errors.
        $errorList = implode('<br>', $errors); 
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showModal("Error!", "' . $errorList . '", false);
            });
        </script>';
    }

    mysqli_close($dbc); // Close the database connection.
}
?>
