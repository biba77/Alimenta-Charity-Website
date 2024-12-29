<?php
$page_title = 'Create a Fundraiser';
include('fundraise.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require('mysqli_connect.php'); // Connect to the database.
    $errors = array(); // Initialize an error array.
    
    // Validate title:
    if (empty($_POST['title'])) {
        $errors[] = 'You forgot to enter a fundraiser title.';
    } else {
        $title = mysqli_real_escape_string($dbc, trim($_POST['title']));
    }
    
    // Validate description:
    if (empty($_POST['description'])) {
        $errors[] = 'You forgot to enter a description.';
    } else {
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
    }
    
    // Validate organizer:
    if (empty($_POST['organizer'])) {
        $errors[] = 'You forgot to enter the organizer name.';
    } else {
        $organizer = mysqli_real_escape_string($dbc, trim($_POST['organizer']));
    }
    
    // Validate email:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter the email.';
    } else {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }
    
    // Validate phone:
    if (empty($_POST['phone'])) {
        $errors[] = 'You forgot to enter the phone number.';
    } else {
        $phone = mysqli_real_escape_string($dbc, trim($_POST['phone']));
    }
    
    // Validate amount:
    if (empty($_POST['amount'])) {
        $errors[] = 'You forgot to enter the target amount.';
    } else {
        $amount = floatval(trim($_POST['amount']));
    }
    
    // Validate start date:
    if (empty($_POST['sdate'])) {
        $errors[] = 'You forgot to enter the start date.';
    } else {
        $sdate = mysqli_real_escape_string($dbc, trim($_POST['sdate']));
    }
    
    // Validate end date:
    if (empty($_POST['edate'])) {
        $errors[] = 'You forgot to enter the end date.';
    } else {
        $edate = mysqli_real_escape_string($dbc, trim($_POST['edate']));
    }
    
    // Validate type:
    if (empty($_POST['type'])) {
        $errors[] = 'You forgot to select the fundraiser type.';
    } else {
        $type = mysqli_real_escape_string($dbc, trim($_POST['type']));
    }
    
    // Validate location:
    if (empty($_POST['location'])) {
        $errors[] = 'You forgot to enter the location.';
    } else {
        $location = mysqli_real_escape_string($dbc, trim($_POST['location']));
    }
    
    // Validate terms acceptance:
    $terms = isset($_POST['terms']) ? 1 : 0;
    
    // If no errors, proceed to insert data into the database.
    if (empty($errors)) {
        $q = "INSERT INTO fundraise (title, description, organizer, email, phone, amount, sdate, edate, type, location, terms)
              VALUES ('$title', '$description', '$organizer', '$email', '$phone', $amount, '$sdate', '$edate', '$type', '$location', $terms)";
        $r = @mysqli_query($dbc, $q);
        
        if ($r) { // If the query ran successfully.
            /// Show success message and redirect.
            echo '
            <script>
                alert("Your fundraiser has been submitted. Please wait to see if it gets accepted.");
                window.location.href = "homepage.php";
            </script>';
        } else { // If the query failed.
            echo '<h1>System Error</h1>
            <p class="error">Your fundraiser could not be created due to a system error. We apologize for any inconvenience.</p>';
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
        }
        
        mysqli_close($dbc); // Close the database connection.
        exit();
    } else { // Report errors.
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
