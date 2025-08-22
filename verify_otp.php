<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>otp</title>
</head>
<body>
    
<meta http-equiv="refresh" content="2;url=confirm_booking.php">
</body>
</html>

<?php
// Database connection parameters
$servername = "localhost"; // Change if your MySQL server is hosted elsewhere
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "booking"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get booking ID and OTP from the form submission
$booking_id = $_POST['booking_id'];
$otp = $_POST['otp'];

// Verify the OTP
$sql = "SELECT otp FROM bookings WHERE id='$booking_id'";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error executing SQL query: " . $conn->error);
}

$row = $result->fetch_assoc();

if ($row['otp'] == $otp) {
    // Update the pickedup status to 1
    $sql_update = "UPDATE bookings SET pickedup=1 WHERE id='$booking_id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<h2>OTP verified successfully! User has been picked up.</h2>";
    } else {
        echo "<h2>Error updating record: " . $conn->error . "</h2>";
    }
} else {
    echo "<h2>Invalid OTP. Please try again.</h2>";
}

// Close connection
$conn->close();
?>
