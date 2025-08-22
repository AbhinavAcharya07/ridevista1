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
            flex-direction: column;
            align-items: center;
            background-image: url('https://static.vecteezy.com/system/resources/previews/004/725/828/original/online-taxi-booking-travel-service-flat-design-illustration-via-mobile-app-on-smartphone-take-someone-to-a-destination-suitable-for-background-poster-or-banner-vector.jpg');
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

        .menu-bar {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 4px 0;
            transition: all 0.3s ease-in-out;
        }

        .menu-items {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        .menu-items a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }

        .menu-items a:hover {
            background-color: #444;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .menu-items {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                position: absolute;
                top: 50px;
                left: 0;
                padding: 10px 0;
            }

            .menu-items.show {
                display: flex;
            }

            .menu-items li {
                text-align: center;
                margin: 10px 0;
            }
        }

        .status-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 300px;
            text-align: center;
            border: 2px solid #FFD700;
        }

        .status-container button {
            background-color: #FFD700;
            border: none;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .status-container button:hover {
            transform: scale(1.1);
        }

        .feedback-form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 250px;
            text-align: center;
            border: 2px solid #FFD700;
        }

        .feedback-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .feedback-form button {
            background-color: #FFD700;
            border: none;
            color: white;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .feedback-form button:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>
    <!-- Menu Bar -->
    <div class="menu-bar">
        <!-- Menu Toggle (Hamburger Icon) -->
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- Menu Items -->
        <ul class="menu-items">
            <li><a href="index.html">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Forms to update picked up and dropped off status -->
    <div class="status-container">
        <form method="post" action="update_status.php">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <?php $booking_id = '';
$status = '';
$pickedup = 0;
$droppedoff = 0;
$otp = '';
 
            if ($pickedup == 0) { ?>
                <button type="submit" name="action" value="pickedup" class="neat-button">Picked Up</button>
            <?php } ?>
            <?php if ($droppedoff == 0) { ?>
                <button type="submit" name="action" value="droppedoff" class="neat-button">Dropped Off</button>
            <?php } ?>
        </form>
    </div>

    <!-- PHP Code to Fetch Booking Status -->
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

// Fetch the most recent booking status from the database
$sql_status = "SELECT id, status, pickedup, droppedoff, otp FROM bookings ORDER BY timestamp DESC LIMIT 1";
$result_status = $conn->query($sql_status);

// Initialize booking status variables
$booking_id = '';
$status = '';
$pickedup = 0;
$droppedoff = 0;
$otp = '';

// Check if the query was successful and if there is a row returned
if ($result_status && $result_status->num_rows > 0) {
    $row_status = $result_status->fetch_assoc();
    $booking_id = $row_status['id'];
    $status = $row_status['status'];
    $pickedup = $row_status['pickedup'];
    $droppedoff = $row_status['droppedoff'];
    $otp = $row_status['otp'];

    // Display the booking status
    echo "<div class='status-container'>";
    echo "<h2>Booking Status</h2>";
    echo "<p>Status: " . ucfirst($status) . "</p>";
    if ($pickedup == 1 && $droppedoff == 1) {
        echo "<p>Your trip has been completed.</p>";
        echo "<div class='feedback-form'>";
        echo "<h2>Trip Feedback</h2>";
        echo "<form method='post' action='submit_feedback.php'>";
        echo "<textarea name='feedback' placeholder='How was your trip?' required></textarea>";
        echo "<input type='hidden' name='booking_id' value='$booking_id'>";
        echo "<button type='submit'>Submit Feedback</button>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>Your booking ID is: $booking_id</p>";
        echo "<p>Picked up status: " . ($pickedup == 1 ? "Yes" : "No") . "</p>";
        echo "<p>Dropped off status: " . ($droppedoff == 1 ? "Yes" : "No") . "</p>";

        // If picked up and OTP is not set, generate a new OTP
        if ($pickedup == 1 && empty($otp)) {
            $otp = rand(100000, 999999);
            $sql_update_otp = "UPDATE bookings SET otp='$otp' WHERE id='$booking_id'";
            if ($conn->query($sql_update_otp) === TRUE) {
                echo "<p>OTP: $otp</p>"; // Display the generated OTP
            } else {
                echo "<p>Error updating OTP: " . $conn->error . "</p>";
            }
        } elseif ($pickedup == 1) {
            // Display the existing OTP
            echo "<p>OTP: $otp</p>";
        }
    }
    echo "</div>";
} else {
    echo "<div class='status-container'>";
    echo "<p>No booking found.</p>";
    echo "</div>";
}

// Close connection
$conn->close();
?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var menuToggle = document.querySelector(".menu-toggle");
            var menuItems = document.querySelector(".menu-items");

            menuToggle.addEventListener("click", function() {
                menuItems.classList.toggle("show");
            });
        });
    </script>
</body>
</html>