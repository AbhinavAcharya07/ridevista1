<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Records</title>
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

    // Create connection for users database
    $conn_users = new mysqli($servername, $username, $password, "users");
    if ($conn_users->connect_error) {
        die("Connection failed: " . $conn_users->connect_error);
    }

    // Handle delete user request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user_email'])) {
        $delete_user_email = $_POST['delete_user_email'];
        $delete_sql = "DELETE FROM signup WHERE email=?";
        $stmt = $conn_users->prepare($delete_sql);
        $stmt->bind_param("s", $delete_user_email);
        $stmt->execute();
        $stmt->close();
        // Refresh the page to show updated users list
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Fetch user records
    $user_records = [];
    $sql = "SELECT * FROM signup ORDER BY username ASC"; // Using 'username' column to order
    $result_records = $conn_users->query($sql);

    // Check if the query was successful
    if ($result_records === false) {
        echo "<p>Error: " . $conn_users->error . "</p>";
    } else {
        if ($result_records->num_rows > 0) {
            while ($row = $result_records->fetch_assoc()) {
                $user_records[] = $row;
            }
        }
    }

    // Close connection
    $conn_users->close();
  ?>

  <header>
    <h1>User Records</h1>
  </header>
  <main>
    <div class="card">
      <h2>All Users</h2>
      <table>
        <tr>
          <th>User Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
        <?php if (count($user_records) > 0): ?>
          <?php foreach ($user_records as $record): ?>
            <tr>
              <td><?php echo htmlspecialchars($record['username']); ?></td>
              <td><?php echo htmlspecialchars($record['email']); ?></td>
              <td>
                <form action="" method="POST" style="display:inline;">
                  <input type="hidden" name="delete_user_email" value="<?php echo htmlspecialchars($record['email']); ?>">
                  <button type="submit">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="3">No users found.</td></tr>
        <?php endif; ?>
      </table>
    </div>
  </main>
</body>
</html>
