<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://s3.envato.com/files/221341756/preview-images/404_04.png');
            background-size: cover;
            background-position: bottom;
        }
        .error-message {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $response = ['error' => ''];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userType = $_POST['userType'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($userType == 'admin' && $email == 'admin@admin.com' && $password == 'adminpassword') {
            $_SESSION['userType'] = 'admin';
            $_SESSION['email'] = $email;
            header("Location: admin.php");
            exit();
        }

        $userDB = ($userType === 'user') ? 'users' : 'drivers';
        $conn = new mysqli('localhost', 'root', '', $userDB);

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("SELECT * FROM signup WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($user['password'] === $password) {
                    $_SESSION['userType'] = $userType;
                    $_SESSION['email'] = $email;
                    if ($userType === "user") {
                        header("Location: user.php");
                        exit();
                    } else if ($userType === "driver") {
                        header("Location: driver.php");
                        exit();
                    }
                } else {
                    $response['error'] = "Invalid password. Please try again.";
                }
            } else {
                $response['error'] = "User not found. Please check your credentials.";
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
    <div class="error-message">
        <?php echo isset($response['error']) ? $response['error'] : ''; ?>
    </div>
</body>
</html>
