<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            background-image: url('https://www.sender.net/wp-content/uploads/2021/03/sign_up_success_message_templates.png');
            background-size: cover;
            background-position: center;
        }

        .blur-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            backdrop-filter: blur(10px);
            z-index: -1;
        }

        .message-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 500px;
            border: 2px solid #FFD700;
            position: relative;
            z-index: 1;
        }

        .message-container h2 {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
        }

        .message-container p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .status-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000000;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .status-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>
    <div class="message-container">
    <?php
        session_start(); // Start the session if not already started

        // Check if booking details have already been inserted
        if (!isset($_SESSION['booking_inserted'])) {
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

            // Function to sanitize user inputs
            function sanitize($input) {
                global $conn;
                return mysqli_real_escape_string($conn, $input);
            }

            // Get user inputs
            $from = sanitize($_POST['from']);
            $to = sanitize($_POST['to']);
            $user_phone = sanitize($_POST['user_phone']); 
            $price = sanitize($_POST['price']); 
            $driver_email = sanitize($_POST['driver_email']); // Get the driver email from the form

            // Get current timestamp
            $timestamp = date("Y-m-d H:i:s");

            // SQL query to insert booking details into database
            $sql = "INSERT INTO bookings (from_location, to_location, user_phone, driver_email, timestamp) VALUES ('$from', '$to', '$user_phone', '$driver_email', '$timestamp')";

            if ($conn->query($sql) === TRUE) {
                // Set session variable to indicate booking details have been inserted
                $_SESSION['booking_inserted'] = true;

                // Display a confirmation message for successful booking
                echo "<h2>Booking status.!!!</h2>";
                echo "<p>Booking details sent successfully. Waiting for confirmation of the ride.</p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
            // Close connection
            $conn->close();
        } else {
            echo "<h2>Booking Already Inserted</h2>";
            echo "<p>You have already inserted booking details. Please check your booking status.</p>";
        }
        ?>
        <a href="booking_status.php" class="status-link">Check Booking Status</a>
    </div>
</body>
</html>
