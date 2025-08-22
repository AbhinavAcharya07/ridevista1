<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://www.yelowsoft.com/static/acfe201ad9c005ef7fcf1a98ca3caf70/2ae51/corporate-taxi-booking-social.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: green;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .confirmation-message, .otp-verification, .user-details {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            text-align: center;
            margin-top: 20px;
        }

        .otp-verification input[type="text"], .otp-verification input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .neat-button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .neat-button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<div class="confirmation-message">

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

// Get booking ID from the form submission
$booking_id = $_POST['booking_id'];

// Update the status of the booking to "confirmed"
$sql = "UPDATE bookings SET status='confirmed' WHERE id='$booking_id'";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Booking confirmed successfully!!!</h2>";

    // Fetch the user details based on booking ID
    $sql_user = "SELECT from_location, to_location, user_phone, otp
                 FROM bookings 
                 WHERE id='$booking_id'";
    
    $result = $conn->query($sql_user);
    
    if ($result === FALSE) {
        die("Error executing SQL query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='user-details'>";
            echo "<h3>User Details</h3>";
            echo "<p><strong>User Phone:</strong> " . htmlspecialchars($row["user_phone"]) . "</p>";
            echo "<p><strong>From:</strong> " . htmlspecialchars($row["from_location"]) . "</p>";
            echo "<p><strong>To:</strong> " . htmlspecialchars($row["to_location"]) . "</p>";

            // Check if the OTP form is submitted
            if (isset($_POST['verify_otp'])) {
                $entered_otp = $_POST['entered_otp'];
                $stored_otp = $row['otp'];

                if ($entered_otp == $stored_otp) {
                    echo "<p>OTP verified successfully!</p>";
                    // Proceed to show trip details and pricing
                    
                    $from = $row["from_location"];
                    $to = $row["to_location"];
                    $pricing = array(
                        "kundapura" => array("udupi" => 100, "basrur" => 150, "mangalore" => 300, "manipal" => 200, "bangalore" => 700, "mysore" => 600, "shimoga" => 400, "belgaum" => 800, "hubli" => 900),
                        "udupi" => array("kundapura" => 100, "basrur" => 100, "mangalore" => 250, "manipal" => 150, "bangalore" => 650, "mysore" => 550, "shimoga" => 350, "belgaum" => 750, "hubli" => 850),
                        "basrur" => array("kundapura" => 150, "udupi" => 100, "mangalore" => 300, "manipal" => 200, "bangalore" => 700, "mysore" => 600, "shimoga" => 400, "belgaum" => 800, "hubli" => 900),
                        "mangalore" => array("kundapura" => 300, "udupi" => 250, "basrur" => 300, "manipal" => 200, "bangalore" => 600, "mysore" => 500, "shimoga" => 450, "belgaum" => 750, "hubli" => 850),
                        "manipal" => array("kundapura" => 200, "udupi" => 150, "basrur" => 200, "mangalore" => 200, "bangalore" => 650, "mysore" => 550, "shimoga" => 350, "belgaum" => 750, "hubli" => 850),
                        "bangalore" => array("kundapura" => 700, "udupi" => 650, "basrur" => 700, "mangalore" => 600, "manipal" => 650, "mysore" => 150, "shimoga" => 350, "belgaum" => 500, "hubli" => 600),
                        "mysore" => array("kundapura" => 600, "udupi" => 550, "basrur" => 600, "mangalore" => 500, "manipal" => 550, "bangalore" => 150, "shimoga" => 400, "belgaum" => 600, "hubli" => 700),
                        "shimoga" => array("kundapura" => 400, "udupi" => 350, "basrur" => 400, "mangalore" => 450, "manipal" => 350, "bangalore" => 350, "mysore" => 400, "belgaum" => 500, "hubli" => 600),
                        "belgaum" => array("kundapura" => 800, "udupi" => 750, "basrur" => 800, "mangalore" => 750, "manipal" => 750, "bangalore" => 500, "mysore" => 600, "shimoga" => 500, "hubli" => 150),
                        "hubli" => array("kundapura" => 900, "udupi" => 850, "basrur" => 900, "mangalore" => 850, "manipal" => 850, "bangalore" => 600, "mysore" => 700, "shimoga" => 600, "belgaum" => 150)
                    );

                    if (isset($pricing[$from][$to])) {
                        echo "<p><strong>Price:</strong> ₹" . $pricing[$from][$to] . "</p>";
                    } else {
                        echo "<p><strong>Price:</strong> Price not available for selected route</p>";
                    }

                    // Fetch the pickedup and droppedoff status from the database
                    $sql_status = "SELECT pickedup, droppedoff FROM bookings WHERE id = '$booking_id'";
                    $result_status = $conn->query($sql_status);

                    if ($result_status === FALSE) {
                        die("Error executing SQL query: " . $conn->error);
                    }

                    $row_status = $result_status->fetch_assoc();
                    $pickedup = $row_status['pickedup'];
                    $droppedoff = $row_status['droppedoff'];

                    if ($pickedup == 1 && $droppedoff == 1) {
                        echo "<p>Your trip has been completed.</p>";
                        echo "<meta http-equiv='refresh' content='5;url=index.html'>";
                    } else {
                        echo "<p>Trip Status:</p>";
                        if ($pickedup == 1) {
                            echo "<p>User has been picked up.</p>";
                        } else {
                            echo "<p>User has not been picked up.</p>";
                        }

                        if ($droppedoff == 1) {
                            echo "<p>User has been dropped off at the location.</p>";
                        } else {
                            echo "<p>User has not been dropped off.</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'>Incorrect OTP. Please try again.</p>";
                    // Show OTP form again for retry
                    echo "<div class='otp-verification'>";
                    echo "<h3>Verify OTP</h3>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='booking_id' value='$booking_id'>";
                    echo "<p><input type='text' name='entered_otp' placeholder='Enter OTP'></p>";
                    echo "<p><input type='submit' name='verify_otp' class='neat-button' value='Verify'></p>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                // Show OTP form for the first time
                echo "<div class='otp-verification'>";
                echo "<h3>Verify OTP</h3>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='booking_id' value='$booking_id'>";
                echo "<p><input type='text' name='entered_otp' placeholder='Enter OTP'></p>";
                echo "<p><input type='submit' name='verify_otp' class='neat-button' value='Verify'></p>";
                echo "</form>";
                echo "</div>";
            }

            echo "</div>";
        }
    } else {
        echo "<h2>No user details found for this booking.</h2>";
    }
} else {
    echo "<h2>Error updating record: " . $conn->error . "</h2>";
}

// Close connection
$conn->close();
?>

</div>

</body>
</html>
