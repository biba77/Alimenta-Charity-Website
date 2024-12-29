<?php
// Start the session at the top of the page to avoid errors
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit();
}

$page_title = 'Make a Donation';
include('donations.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require('mysqli_connect.php'); // Connect to the database.
    $errors = array(); // Initialize an error array.
    $donation_date = date('Y-m-d H:i:s'); // Current date and time
    
    // Validate donation amount:
    if (empty($_POST['donation_amount'])) {
        $errors[] = 'You forgot to select or enter a donation amount.';
    } else {
        $amount = floatval(trim($_POST['donation_amount'])); // Convert to float
    }
    
    // Validate donation frequency:
    if (empty($_POST['frequency'])) {
        $errors[] = 'You forgot to select a donation frequency.';
    } else {
        $frequency = mysqli_real_escape_string($dbc, trim($_POST['frequency']));
    }
    
    // Validate payment method:
    if (empty($_POST['payment_method'])) {
        $errors[] = 'You forgot to enter your payment method.';
    } else {
        $payment_method = mysqli_real_escape_string($dbc, trim($_POST['payment_method']));
    }
    
    // Get user ID from the session:
    $user_id = $_SESSION['user_id'];
    
    if (empty($errors)) { // If no errors, insert the data.
        $q = "INSERT INTO donations (user_id, donation_amount, donation_date, frequency, payment_method)
              VALUES ('$user_id', '$amount', '$donation_date', '$frequency', '$payment_method')";
        $r = @mysqli_query($dbc, $q); // Run the query.

        if ($r) { // If donation query ran successfully
            // Insert notification into notifications table
            $notification_text = "You have donated an amount of RM$amount. Thank you for your donation!";
            $notification_query = "INSERT INTO notifications (user_id, notification_text)
                            VALUES ('$user_id', '$notification_text')";
            @mysqli_query($dbc, $notification_query);
            
            // Show success modal
            echo '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showModal("Success!", "Your donation has been recorded. Thank you for your generosity!", true);
        });
    </script>';
        } else {
            echo '<h1>System Error</h1>
                  <p class="error">Your donation could not be recorded due to a system error. We apologize for any inconvenience.</p>';
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
