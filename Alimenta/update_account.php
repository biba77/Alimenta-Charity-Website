<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require('mysqli_connect.php');

$user_id = $_SESSION['user_id'];

// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));

    // Update the user details
    $query = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $first_name, $last_name, $email, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to account page after successful update
        header('Location: account.php?update=success');
    } else {
        echo '<p>Error updating account information. Please try again.</p>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
}
?>
