<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trips</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS styles */
        /* Same styles as the dashboard for consistency */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/previews/004/725/828/original/online-taxi-booking-travel-service-flat-design-illustration-via-mobile-app-on-smartphone-take-someone-to-a-destination-suitable-for-background-poster-or-banner-vector.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            line-height: 1.6;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            padding: 1rem;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome {
            font-size: 1.2rem;
        }

        .logout {
            color: white;
            text-decoration: none;
            background-color: #dc3545;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .logout:hover {
            background-color: #c82333;
        }

        main {
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #booking-table {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #booking-table h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<header>
    <div class="welcome">
        <?php echo "Welcome, " . htmlspecialchars($_SESSION['email']); ?>
    </div>
    <div>
    <a href="driver.php" class="logout">Goback</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

<main>
    <div id="booking-table">
        <h2>My Trips</h2>
        <table>
            <tr>
                <th>From Location</th>
                <th>To Location</th>
                <th>User Phone</th>
                <th>Status</th>
                <th>Timestamp</th>
            </tr>
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

            // Fetch all bookings for the logged-in driver from the database
            $email = $_SESSION['email'];
            $sql = "SELECT * FROM bookings WHERE driver_email='$email' ORDER BY timestamp DESC";
            $result = $conn->query($sql);

            // Display bookings in HTML table format
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["from_location"]."</td>";
                    echo "<td>".$row["to_location"]."</td>";
                    echo "<td>".$row["user_phone"]."</td>";
                    echo "<td>".$row["status"]."</td>";
                    echo "<td>".$row["timestamp"]."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No trips found.</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </table>
    </div>
</main>

</body>
</html>
