<?php
      // Retrieve data from the database
      $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mldorm";

      // Create a new connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Fetch data from the "applications" table
      $sql = "SELECT first_name, last_name, middle_name, address, email, guardian_phone, gender, room_taken, start_date FROM reservation";
      $result = $conn->query($sql);

      // Display the data in the table
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["first_name"] . "</td>";
          echo "<td>" . $row["last_name"] . "</td>";
          echo "<td>" . $row["middle_name"] . "</td>";
          echo "<td>" . $row["address"] . "</td>";
          echo "<td>" . $row["email"] . "</td>";
          echo "<td>" . $row["guardian_phone"] . "</td>";
          echo "<td>" . $row["gender"] . "</td>";
          echo "<td>" . $row["room_taken"] . "</td>";
          echo "<td>" . $row["start_date"] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='9'>No application form entries found.</td></tr>";
      }

      // Close the connection
      $conn->close();
    ?>