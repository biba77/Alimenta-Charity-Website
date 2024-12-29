<?php
// This is the volunteer page of the website
$page_title = 'Volunteer';
include ('volunteer.html');

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
    // Validate country
    if (empty($_POST['country']) || $_POST['country'] === 'default') {
        $errors[] = 'You forgot to select a country.';
    } else {
        $country = mysqli_real_escape_string($dbc, trim($_POST['country']));
    }
    
    // Validate event
    if (empty($_POST['event']) || $_POST['event'] === 'default') {
        $errors[] = 'You forgot to select an event.';
    } else {
        $event = mysqli_real_escape_string($dbc, trim($_POST['event']));
    }
    
    // Validate age
    if (empty($_POST['age'])) {
        $errors[] = 'You forgot to enter your age.';
    } else {
        $age = mysqli_real_escape_string($dbc, trim($_POST['age']));
    }
    
    // Validate areas of interest
    if (empty($_POST['areas'])) {
        $errors[] = 'You forgot to select your areas of interest.';
    } else {
        // Assume areas are submitted as an array (e.g., checkboxes)
        $areas = mysqli_real_escape_string($dbc, trim($_POST['areas']));
    }
    
    // Validate terms and conditions agreement
    if (empty($_POST['terms'])) {
        $errors[] = 'You must agree to the terms and conditions.';
    } else {
        $terms = mysqli_real_escape_string($dbc, trim($_POST['terms']));; // Indicate the user agreed to the terms
    }
    
    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Insert data into the database
        $q = "INSERT INTO volunteering (first_name, last_name, email, phone_number, country, event, age, areas, terms, submitted_at)
                  VALUES ('$fn', '$ln', '$e', '$phone', '$country', '$event', '$age', '$areas', '$terms', NOW())";
        $r = @mysqli_query($dbc, $q); // Run the query.

        if ($r) { // If it ran OK.
            // Show success modal using JavaScript.
            echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    showModal("Success!", "Thank you for signing up to volunteer!", true);
                });
            </script>';
            
        } else {
            echo '<h1>System Error</h1>
            <p class="error">Your sign up form was not processed due to a system error. We apologize for any inconvenience.</p>';
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