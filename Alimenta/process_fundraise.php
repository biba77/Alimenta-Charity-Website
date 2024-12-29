<?php
// Include database connection file
require_once('mysqli_connect.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if ID and action are provided
    if (isset($_POST['id'], $_POST['action']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);
        $action = $_POST['action'];

        // Determine the status based on the action
        $status = '';
        if ($action === 'Accept') {
            $status = 'Accepted';
        } elseif ($action === 'Reject') {
            $status = 'Rejected';
        } else {
            echo 'Invalid action!';
            exit();
        }

        // Update the status in the database
        $q = "UPDATE fundraise SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($dbc, $q);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'si', $status, $id);
            mysqli_stmt_execute($stmt);

            // Check if the update was successful
            if (mysqli_stmt_affected_rows($stmt) === 1) {
                echo "The fundraiser has been successfully $status.";
            } else {
                echo 'No changes were made. Either the fundraiser does not exist or its status is already set.';
            }

            mysqli_stmt_close($stmt);
        } else {
            echo 'Database query failed.';
        }
    } else {
        echo 'Invalid request. Please provide a valid ID and action.';
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($dbc);
?>
