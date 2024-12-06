<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Form - Alimenta</title>
    <link rel="stylesheet" href="inquiry.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="Alimenta%20Logo.png" alt="Alimenta">
            <span class="brand-name">Alimenta</span>
        </div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Notifications</a>
            <a href="#">Donations</a>
            <a href="#" class="active">Contact Us</a>
            <button class="donate-button">Donate Now &gt;</button>
            <div class="profile">
                <span>Profile</span>
            </div>
        </nav>
    </header>


<main>
    <div class="form-container">
        <h2>Inquiry Form</h2>
        <p>We will get back to you soon!</p>
        <form id="inquiryForm" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="submit-button">Send Inquiry</button>
        </form>
    </div>
</main>

<!-- JavaScript Validation -->
<script>
    document.getElementById("inquiryForm").addEventListener("submit", function(event) {
        let firstName = document.getElementById("first_name").value.trim();
        let email = document.getElementById("email").value.trim();

        if (firstName === "" || email === "") {
            alert("Please fill in all required fields.");
            event.preventDefault();
        }
    });
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Database connection setup
    $conn = new mysqli('localhost', 'username', 'password', 'alimentadb');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO inquiries (first_name, last_name, email, phone, message)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Your inquiry has been submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . " \\n" . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

</body>
</html>
