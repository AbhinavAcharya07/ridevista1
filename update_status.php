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

// Fetch the latest booking ID
$sql_latest_id = "SELECT id FROM bookings ORDER BY id DESC LIMIT 1";
$result_latest_id = $conn->query($sql_latest_id);

if ($result_latest_id->num_rows > 0) {
    $row_latest_id = $result_latest_id->fetch_assoc();
    $booking_id = $row_latest_id['id'];
} else {
    die("No bookings found.");
}

// Get action from the form submission
$action = $_POST['action'];

// Validate action
if (!in_array($action, ['pickedup', 'droppedoff'])) {
    die("Invalid action specified.");
}

// Determine which column to update based on the action
$column = ($action == 'pickedup') ? 'pickedup' : 'droppedoff';



// Update the status in the database
$sql_update = "UPDATE bookings SET $column = 1 WHERE id = $booking_id";

if ($conn->query($sql_update) === TRUE) {
    echo "<p class='success'>Status updated successfully!</p>";
} else {
    echo "<p class='error'>Error updating status: " . $conn->error . "</p>";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2;url=booking_status.php">
    <title>Booking Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        p {
            margin: 10px 0;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .debug {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
   
</body>
</html>
