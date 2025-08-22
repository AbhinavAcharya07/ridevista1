<?php



$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$pnumber = $_POST['pnumber'];
$user_type = $_POST['user_type'];
$vnumber = isset($_POST['vnumber']) ? $_POST['vnumber'] : null;

$dbname = ($user_type == 'user') ? 'users' : 'drivers';
// Check if user_type is valid (you can add more validation if needed)
if ($user_type != 'user' && $user_type != 'driver') {
    die('Error: Invalid user type.');
}

// Create connection for users or drivers database based on user_type
if ($user_type == 'user') {
    $conn = new mysqli('localhost', 'root', '', 'users');
} else {
    $conn = new mysqli('localhost', 'root', '', 'drivers');
}

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
        // Insert new user
        if ($user_type == 'user') {
            $stmt = $conn->prepare("INSERT INTO signup (username, email, password, pnumber) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $pnumber);
        } else {
            $stmt = $conn->prepare("INSERT INTO signup (username, email, password, pnumber, vnumber) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password, $pnumber, $vnumber);
        }
        
        if ($stmt->execute()) {
            // Upload license and RC copy files for drivers
            if ($user_type == 'driver') {
                $uploadDir = './images/'; // Specify the directory path where you want to store the files

                // Upload license file
                if ($_FILES['license']['error'] === UPLOAD_ERR_OK) {
                    $licenseFile = $uploadDir . basename($_FILES['license']['name']);
                    if (move_uploaded_file($_FILES['license']['tmp_name'], $licenseFile)) {
                        $licensePath = $licenseFile;
                        $license_stmt = $conn->prepare("UPDATE signup SET license = ? WHERE email = ?");
                        if ($license_stmt) {
                            $license_stmt->bind_param("ss", $licensePath, $email);
                            $license_stmt->execute();
                            $license_stmt->close(); // Close the statement
                        } else {
                            echo "<div class='message-container error-message'>Error preparing update statement: " . $conn->error . "</div>";
                        }
                    } else {
                        echo "<div class='message-container error-message'>Failed to upload license file.</div>";
                    }
                } else {
                    echo "<div class='message-container error-message'>Error uploading license file.</div>";
                }

                // Upload RC copy file
                if ($_FILES['rc_copy']['error'] === UPLOAD_ERR_OK) {
                    $rcCopyFile = $uploadDir . basename($_FILES['rc_copy']['name']);
                    if (move_uploaded_file($_FILES['rc_copy']['tmp_name'], $rcCopyFile)) {
                        $rcCopyPath = $rcCopyFile;
                        $rcCopy_stmt = $conn->prepare("UPDATE signup SET rc_copy = ? WHERE email = ?");
                        if ($rcCopy_stmt) {
                            $rcCopy_stmt->bind_param("ss", $rcCopyPath, $email);
                            $rcCopy_stmt->execute();
                            $rcCopy_stmt->close(); // Close the statement
                        } else {
                            echo "<div class='message-container error-message'>Error preparing update statement: " . $conn->error . "</div>";
                        }
                    } else {
                        echo "<div class='message-container error-message'>Failed to upload RC copy file.</div>";
                    }
                } else {
                    echo "<div class='message-container error-message'>Error uploading RC copy file.</div>";
                }
            }

            echo "<div class='message-container success-message'>
                    <div class='success-icon'>✔️</div>
                    Registration successful. <a href='login.html'>Click here to login</a>
                  </div>";
        } else {
            echo "<div class='message-container error-message'>Error executing statement: " . $stmt->error . "</div>";
        }

        // Close statements
        $stmt->close();
    }

    // Close statements
    $check_stmt->close();
    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://www.sender.net/wp-content/uploads/2021/03/sign_up_success_message_templates.png') no-repeat center center fixed;
            background-size: cover;
        }
        .blurry-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            filter: blur(10px);
            z-index: -1;
        }
        .message-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            max-width: 500px;
            margin: 20px;
        }
        .success-message {
            color: green;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }
        a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="blurry-background"></div>
</body>
</html>
