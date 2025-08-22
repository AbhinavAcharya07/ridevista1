<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCTCOnUxvnX_9dIBZ6CfRxqjjkywqiXHi3HZEme3eVTA&s'); /* Path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: red;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ensure full viewport height */
            margin: 0;
        }

        .confirmation-message {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
            width: 300px;
            
            text-align: center;
        }
    </style>
</head>
<body>
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get booking ID from POST data
$booking_id = $_POST['booking_id'];

// Update booking status to canceled
$sql = "UPDATE bookings SET status = 'canceled' WHERE id = $booking_id";

if ($conn->query($sql) === TRUE) {
    echo "<div class='confirmation-message'>Booking canceled successfully!!!.</div>";
} else {
    echo "<div class='confirmation-message'>Error updating record: " . $conn->error . "</div>";
}

// Close connection
$conn->close();
?>
</body>
</html>
