<?php
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$pnumber = $_POST['pnumber'];
$vnumber = isset($_POST['vnumber']) ? $_POST['vnumber'] : null;

// Create connection to the database
$conn = new mysqli('localhost', 'root', '', 'drivers');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT * FROM signup WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='message-container error-message'>Error: User with this email already exists.</div>";
    } else {
        // Insert new driver
        $stmt = $conn->prepare("INSERT INTO signup (username, email, password, pnumber, vnumber) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $password, $pnumber, $vnumber);
        $stmt->execute();

        echo "<div class='message-container success-message'>
                <div class='success-icon'>✔️</div>
                Registration successful. <a href='login.html'>Click here to login</a>
              </div>";
    }

    // Close statements
    $check_stmt->close();
    $stmt->close();
    // Close connection
    $conn->close();
}
?>
