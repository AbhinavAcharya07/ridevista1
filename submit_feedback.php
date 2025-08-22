<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Submission</title>
    <meta http-equiv="refresh" content="2;url=index.html">
</head>
<body>
    <?php
    session_start();

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

    // Check if the feedback form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $booking_id = $_POST['booking_id'];
        $feedback = $_POST['feedback'];

        // Insert feedback into the database
        $sql_feedback = "INSERT INTO feedback (booking_id, feedback) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_feedback);
        $stmt->bind_param("is", $booking_id, $feedback);

        if ($stmt->execute()) {
            echo "<p>Feedback submitted successfully. Thank you!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
