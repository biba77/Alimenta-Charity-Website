<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

include 'homepage.html';
?>