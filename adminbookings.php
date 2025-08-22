<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Details</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f2f5;
      color: #333;
    }

    header {
      background-color: #333;
      color: white;
      padding: 1rem;
      text-align: center;
    }

    main {
      padding: 2rem;
    }

    .card {
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 1rem;
      text-align: left;
    }

    th {
      background-color: #f0f2f5;
    }

    button {
      background: none;
      border: none;
      color: red;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "booking";

    // Create connection for bookings database
    $conn_bookings = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn_bookings->connect_error) {
        die("Connection failed: " . $conn_bookings->connect_error);
    }

    // Handle delete booking request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_booking_id'])) {
        $delete_booking_id = $_POST['delete_booking_id'];
        $delete_sql = "DELETE FROM bookings WHERE id=?";
        $stmt = $conn_bookings->prepare($delete_sql);
        $stmt->bind_param("i", $delete_booking_id);
        $stmt->execute();
        $stmt->close();
        // Refresh the page to show updated bookings list
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Fetch booking records
    $booking_records = [];
    $sql = "SELECT id, from_location, to_location, user_phone, driver_email,status, pickedup, droppedoff, timestamp FROM bookings ORDER BY timestamp DESC";
    $result_records = $conn_bookings->query($sql);

    // Check for query execution
    if (!$result_records) {
        die("Query failed: " . $conn_bookings->error);
    }

    // Fetch and store records
    if ($result_records->num_rows > 0) {
        while($row = $result_records->fetch_assoc()) {
            $booking_records[] = $row;
        }
    }

    // Close connection
    $conn_bookings->close();
  ?>

  <header>
    <h1>Booking Details</h1>
  </header>
  <main>
    <div class="card">
      <h2>All Bookings</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>From Location</th>
          <th>To Location</th>
          <th>User Phone</th>
          <th>Driver email</th>
          <th>Status</th>
          <th>Picked Up</th>
          <th>Dropped Off</th>
          <th>Timestamp</th>
          <th>Actions</th>
        </tr>
        <?php if (count($booking_records) > 0): ?>
          <?php foreach ($booking_records as $record): ?>
            <tr>
              <td><?php echo $record['id']; ?></td>
              <td><?php echo $record['from_location']; ?></td>
              <td><?php echo $record['to_location']; ?></td>
              <td><?php echo $record['user_phone']; ?></td>
              <td><?php echo $record['driver_email']; ?></td>
              <td><?php echo $record['status']; ?></td>
              <td><?php echo $record['pickedup'] ? 'Yes' : 'No'; ?></td>
              <td><?php echo $record['droppedoff'] ? 'Yes' : 'No'; ?></td>
              <td><?php echo $record['timestamp']; ?></td>
              <td>
                <form action="" method="POST" style="display:inline;">
                  <input type="hidden" name="delete_booking_id" value="<?php echo $record['id']; ?>">
                  <button type="submit">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No bookings found.</td></tr>
        <?php endif; ?>
      </table>
    </div>
  </main>
</body>
</html>
