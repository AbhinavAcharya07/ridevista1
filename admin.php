<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taxi Service Admin</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f2f5;
      color: #333;
      display: flex;
    }

    header {
      background-color: #333;
      color: white;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    nav {
      background-color: #444;
      color: white;
      padding: 2rem 1rem;
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
    }

    nav ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
    }

    nav ul li {
      margin-bottom: 1rem;
    }

    nav a {
      color: white;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #dbc64c;
    }

    main {
      padding: 2rem;
      margin-left: 270px;
      flex-grow: 1;
    }

    .card {
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      flex-basis: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .card.users {
      background-color: #f0f9ff;
    }

    .card.drivers {
      background-color: #fef6f0;
    }

    .card.bookings {
      background-color: #f0f5f9;
    }

    .card.feedbacks {
      background-color: #f9f0f6;
    }

    .card.queries {
      background-color: #f9f9f0;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 0.5rem;
      margin-bottom: 1rem;
    }

    .card-header h2 {
      margin: 0;
      font-size: 1.25rem;
    }

    .card-header .actions {
      font-size: 1.25rem;
    }

    .card-header .actions a {
      color: #444;
      margin-left: 0.5rem;
      transition: color 0.3s ease;
    }

    .card-header .actions a:hover {
      color: #000;
    }

    .card-content {
      padding: 1rem 0;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
    }

    @media (min-width: 600px) {
      .card-half {
        flex-basis: calc(50% - 2rem);
      }
    }

    @media (min-width: 900px) {
      .card-half {
        flex-basis: calc(33.33% - 2rem);
      }
    }
  </style>
</head>
<body>
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Create connection for users database
$conn_users = new mysqli($servername, $username, $password, "users");
if ($conn_users->connect_error) {
    die("Connection failed: " . $conn_users->connect_error);
}

// Create connection for drivers database
$conn_drivers = new mysqli($servername, $username, $password, "drivers");
if ($conn_drivers->connect_error) {
    die("Connection failed: " . $conn_drivers->connect_error);
}

// Create connection for bookings database
$conn_bookings = new mysqli($servername, $username, $password, "booking");
if ($conn_bookings->connect_error) {
    die("Connection failed: " . $conn_bookings->connect_error);
}

// Create connection for ridevista_db database
$conn_ridevista = new mysqli($servername, $username, $password, "ridevista_db");
if ($conn_ridevista->connect_error) {
    die("Connection failed: " . $conn_ridevista->connect_error);
}

// Fetch total users
$result_users = $conn_users->query("SELECT COUNT(*) as total_users FROM signup");
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch total drivers
$result_drivers = $conn_drivers->query("SELECT COUNT(*) as total_drivers FROM signup");
$total_drivers = $result_drivers->fetch_assoc()['total_drivers'];

// Fetch total bookings
$result_bookings = $conn_bookings->query("SELECT COUNT(*) as total_bookings FROM bookings");
$total_bookings = $result_bookings->fetch_assoc()['total_bookings'];

// Fetch total feedbacks
$result_feedbacks = $conn_bookings->query("SELECT COUNT(*) as total_feedbacks FROM feedback");
$total_feedbacks = $result_feedbacks->fetch_assoc()['total_feedbacks'];

// Fetch total queries
$result_queries = $conn_ridevista->query("SELECT COUNT(*) as total_queries FROM contact_form");
$total_queries = $result_queries->fetch_assoc()['total_queries'];

// Close connections
$conn_users->close();
$conn_drivers->close();
$conn_bookings->close();
$conn_ridevista->close();
?>

  <!-- <header>
    <h1>Ridevista Admin Panel</h1>
    <div>
      <a href="index.html" style="color: white; text-decoration: none;">Logout</a>
    </div>
  </header> -->


  <nav>
    <ul>
      <li><h2><a href="#dashboard">ADMIN PANEL</a></h2></li>
      <li><h4><a href="userbookings.php">Users</a></h4></li>
      <li><h4><a href="driverbookings.php">Drivers</a></h4></li>
      <li><h4><a href="adminbookings.php">Bookings</a></h4></li>
      <li><h4><a href="adminfeedbacks.php">Feedbacks</a></h4></li>
      <li><h4><a href="adminqueries.php">Queries</a></h4></li>
      <li><h4><a href="index.html">Logout</a></h4></li>
    </ul>
  </nav>

  <main>
    <section id="dashboard" class="card">
      <div class="card-header">
        <h2>Dashboard</h2>
        <div class="actions">
          <a href="#"><i class="fas fa-sync"></i></a>
        </div>
      </div>
      <div class="card-content">
        <div class="grid">
          <div class="card users">
            <div class="card-header">
              <h2>Users</h2>
              <div class="actions">
                <a href="userbookings.php"><i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
            <div class="card-content">
              <p>Total Users: <span id="total-users"><?php echo $total_users; ?></span></p>
            </div>
          </div>
          <div class="card drivers">
            <div class="card-header">
              <h2>Drivers</h2>
              <div class="actions">
                <a href="driverbookings.php"><i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
            <div class="card-content">
              <p>Total Drivers: <span id="total-drivers"><?php echo $total_drivers; ?></span></p>
            </div>
          </div>
          <div class="card bookings">
            <div class="card-header">
              <h2>Bookings</h2>
              <div class="actions">
                <a href="adminbookings.php"><i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
            <div class="card-content">
              <p>Total Bookings: <span id="total-bookings"><?php echo $total_bookings; ?></span></p>
            </div>
          </div>
        </div>
    </section>
    <section id="dashboard" class="card">
      <div class="card-header">
        <h2>Feedbacks & Queries</h2>
        <div class="actions">
          <a href="#"><i class="fas fa-sync"></i></a>
        </div>
      </div>
      <div class="card-content">
        <div class="grid">
          <div class="card feedbacks">
            <div class="card-header">
              <h2>Feedbacks</h2>
              <div class="actions">
                <a href="adminfeedbacks.php"><i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
            <div class="card-content">
              <p>Total Feedbacks: <span id="total-feedbacks"><?php echo $total_feedbacks; ?></span></p>
            </div>
          </div>
          <div class="card queries">
            <div class="card-header">
              <h2>Customer Queries</h2>
              <div class="actions">
                <a href="adminqueries.php"><i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
            <div class="card-content">
              <p>Total Queries: <span id="total-queries"><?php echo $total_queries; ?></span></p>
            </div>
          </div>
          
        </div>
    </section>
  </main>
</body>
</html>
