<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        header {
            background-color: #000000;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #FFD700;
        }
        .logo h1 {
            font-size: 24px;
            margin: 0;
        }
        nav ul {
            list-style-type: none;
            display: flex;
            align-items: center;
        }
        nav ul li {
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #FFD700;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 1rem;
            width: 300px;
            text-align: center;
        }
        .card img {
            max-width: 100%;
            border-radius: 8px;
        }
        .card h3 {
            margin: 0.5rem 0;
        }
        .card p {
            margin: 0.5rem 0;
        }
        .card button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }
        .card button:hover {
            background-color: #218838;
        }
        .form-container {
            display: none;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 500px;
            border: 2px solid #FFD700;
            margin-top: 20px;
        }
        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #000000;
            font-weight: bold;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .form-container select,
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 15px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: bold;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .price-container {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo"><h1>Welcome to the User Dashboard</h1></div>
        <div class="username">
            <?php echo "Welcome, " . htmlspecialchars($_SESSION['email']); ?>
        </div>
        <div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $conn = new mysqli($servername, $username, $password, "drivers");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch driver records
            $sql = "SELECT * FROM signup ORDER BY username ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($row['rc_copy']) . '" alt="Car Image">';
                    echo '<h3>' . htmlspecialchars($row['username']) . '</h3>';
                    echo '<p>Email: ' . htmlspecialchars($row['email']) . '</p>';
                    echo '<p>Phone: ' . htmlspecialchars($row['pnumber']) . '</p>';
                    echo '<p>Vehicle: ' . htmlspecialchars($row['vnumber']) . '</p>';
                    echo '<button class="book-now" data-driver-email="' . htmlspecialchars($row['email']) . '">Book Now</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No drivers found.</p>';
            }

            $conn->close();
            ?>
        </div>
        <div class="form-container" id="booking-form-container">
            <h2>Book Your Ride</h2>
            <form method="post" action="bookings.php" id="booking-form">
                <label for="from">Enter Destination From:</label>
                <select id="from" name="from" required>
                    <option value="" disabled selected>Select starting point</option>
                    <option value="kundapura">Kundapura</option>
                    <option value="udupi">Udupi</option>
                    <option value="basrur">Basrur</option>
                    <option value="mangalore">Mangalore</option>
                    <option value="manipal">Manipal</option>
                    <option value="bangalore">Bangalore</option>
                    <option value="mysore">Mysore</option>
                    <option value="shimoga">Shimoga</option>
                    <option value="belgaum">Belgaum</option>
                    <option value="hubli">Hubli</option>
                </select>
                <label for="to">Enter Destination To:</label>
                <select id="to" name="to" required>
                    <option value="" disabled selected>Select destination</option>
                    <option value="kundapura">Kundapura</option>
                    <option value="udupi">Udupi</option>
                    <option value="basrur">Basrur</option>
                    <option value="mangalore">Mangalore</option>
                    <option value="manipal">Manipal</option>
                    <option value="bangalore">Bangalore</option>
                    <option value="mysore">Mysore</option>
                    <option value="shimoga">Shimoga</option>
                    <option value="belgaum">Belgaum</option>
                    <option value="hubli">Hubli</option>
                </select>
                <label for="user_phone">Phone Number:</label>
                <input type="tel" id="user_phone" name="user_phone" pattern="[0-9]{10}" required placeholder="Enter 10-digit phone number">
                <input type="hidden" id="price" name="price" value="">
                <input type="hidden" id="driver_email" name="driver_email" value="">
                <button type="submit" id="book-now">Book Now</button>
            </form>
            <div class="price-container" id="price-container">Select cities to see the price.</div>
        </div>
    </main>
    
    <script>
        const pricing = {
            "kundapura": {"udupi": 100, "basrur": 150, "mangalore": 300, "manipal": 200, "bangalore": 700, "mysore": 600, "shimoga": 400, "belgaum": 800, "hubli": 900},
            "udupi": {"kundapura": 100, "basrur": 100, "mangalore": 250, "manipal": 150, "bangalore": 650, "mysore": 550, "shimoga": 350, "belgaum": 750, "hubli": 850},
            "basrur": {"kundapura": 150, "udupi": 100, "mangalore": 300, "manipal": 200, "bangalore": 700, "mysore": 600, "shimoga": 400, "belgaum": 800, "hubli": 900},
            "mangalore": {"kundapura": 300, "udupi": 250, "basrur": 300, "manipal": 200, "bangalore": 600, "mysore": 500, "shimoga": 450, "belgaum": 750, "hubli": 850},
            "manipal": {"kundapura": 200, "udupi": 150, "basrur": 200, "mangalore": 200, "bangalore": 650, "mysore": 550, "shimoga": 350, "belgaum": 750, "hubli": 850},
            "bangalore": {"kundapura": 700, "udupi": 650, "basrur": 700, "mangalore": 600, "manipal": 650, "mysore": 150, "shimoga": 350, "belgaum": 500, "hubli": 600},
            "mysore": {"kundapura": 600, "udupi": 550, "basrur": 600, "mangalore": 500, "manipal": 550, "bangalore": 150, "shimoga": 400, "belgaum": 600, "hubli": 700},
            "shimoga": {"kundapura": 400, "udupi": 350, "basrur": 400, "mangalore": 450, "manipal": 350, "bangalore": 350, "mysore": 400, "belgaum": 500, "hubli": 600},
            "belgaum": {"kundapura": 800, "udupi": 750, "basrur": 800, "mangalore": 750, "manipal": 750, "bangalore": 500, "mysore": 600, "shimoga": 500, "hubli": 150},
            "hubli": {"kundapura": 900, "udupi": 850, "basrur": 900, "mangalore": 850, "manipal": 850, "bangalore": 600, "mysore": 700, "shimoga": 600, "belgaum": 150}
        };

        const fromSelect = document.getElementById('from');
        const toSelect = document.getElementById('to');
        const priceContainer = document.getElementById('price-container');
        const priceInput = document.getElementById('price'); // Hidden input field
        const bookingFormContainer = document.getElementById('booking-form-container');
        const bookingForm = document.getElementById('booking-form');
        const driverEmailInput = document.getElementById('driver_email');

        function updatePrice() {
            const from = fromSelect.value;
            const to = toSelect.value;
            if (from && to && from !== to) {
                const price = pricing[from][to];
                priceContainer.textContent = `Price: ₹${price}`;
                priceInput.value = price;
            } else {
                priceContainer.textContent = 'Select cities to see the price.';
            }
        }

        fromSelect.addEventListener('change', updatePrice);
        toSelect.addEventListener('change', updatePrice);

        document.querySelectorAll('.book-now').forEach(button => {
            button.addEventListener('click', function () {
                const driverEmail = this.getAttribute('data-driver-email');
                driverEmailInput.value = driverEmail;
                bookingFormContainer.style.display = 'block';
                window.scrollTo({ top: bookingFormContainer.offsetTop, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
