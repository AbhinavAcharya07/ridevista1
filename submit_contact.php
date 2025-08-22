<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sumit_query</title>
   
</head>
<body>
    
</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ridevista_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contact_form (name, phone, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $phone, $message);

// Execute the statement
if ($stmt->execute()) {
    echo "<div style='color: green; text-align: center;'>Your query sent successfully</div>";

} else {
    echo "Error: " . $stmt->error;
}
echo "<meta http-equiv='refresh' content='2;url=contact.html'>";

// Close connections
$stmt->close();
$conn->close();
?>
