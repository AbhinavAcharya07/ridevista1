<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Details</title>
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 2rem 0;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 1rem;
      text-align: left;
    }
    th {
      background-color: #f4f4f4;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    form {
      display: inline;
    }
  </style>
</head>
<body>
  <header>
    <h1>Feedback Details</h1>
  </header>
  <main>
    <?php
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

      // Handle delete request
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
          $booking_id_to_delete = $_POST['booking_id'];
          $delete_sql = "DELETE FROM feedback WHERE booking_id = ?";
          $stmt = $conn->prepare($delete_sql);
          $stmt->bind_param("i", $booking_id_to_delete);
          $stmt->execute();
          $stmt->close();
      }

      // Fetch feedback details
      $sql_feedbacks = "SELECT booking_id, feedback FROM feedback ORDER BY timestamp DESC";
      $result_feedbacks = $conn->query($sql_feedbacks);

      if ($result_feedbacks->num_rows > 0) {
          echo "<table>";
          echo "<tr><th>Booking ID</th><th>Feedback</th><th>Action</th></tr>";

          // Output data of each row
          while($row = $result_feedbacks->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row["booking_id"] . "</td>";
              echo "<td>" . $row["feedback"] . "</td>";
              echo "<td>";
              echo "<form method='post' action=''>";
              echo "<input type='hidden' name='booking_id' value='" . $row["booking_id"] . "'>";
              echo "<input type='submit' name='delete' value='Delete'>";
              echo "</form>";
              echo "</td>";
              echo "</tr>";
          }
          echo "</table>";
      } else {
          echo "<p>No feedbacks found</p>";
      }

      // Close connection
      $conn->close();
    ?>
  </main>
</body>
</html>
