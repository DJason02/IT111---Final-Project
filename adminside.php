<?php
function startDatabase(){
    // Example code to connect to a MySQL database
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
    return $conn;
}
function displayFormEntries(){
    $conn = startDatabase(); // Assign the returned connection to a variable
    // Fetch data from the "applications" table
    $sql = "SELECT first_name, last_name, middle_name, address, email, guardian_phone, gender, room_taken, start_date, id FROM reservation";
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
            echo '<td>
            <form method="post" onsubmit="return confirm(\'Are you sure you want to accept this row?\')">
            <input type="hidden" name="row_id" value="' . $row["id"] . '">
            <button class=button--accept type="submit" name="submit"><i class="fa fa-check"></i></button></form>

            <form method="post" onsubmit="return confirm(\'Are you sure you want to process this row?\')">
            <input type="hidden" name="row_id" value="' . $row["id"] . '">
            <button class=button--decline type="submit" name="delete"><i class="fa fa-remove"></i></button></form>
            
            </td>';
            echo "</tr>";
        }
        deleteReservation();
    } else {
        echo "<tr><td colspan='10'>No application form entries found.</td></tr>";
    }

    // Close the connection
    $conn->close();
}
function displayOccupants(){
    $conn = startDatabase(); // Assign the returned connection to a variable
    // Fetch data from the "applications" table
    $sql = "SELECT RoomNum, Name, Gender, Address, Email, Guardian_Number FROM occupied_rooms";
    $result = $conn->query($sql);

    // Display the data in the table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["RoomNum"] . "</td>";
            echo "<td>" . $row["Name"] . "</td>";
            echo "<td>" . $row["Gender"] . "</td>";
            echo "<td>" . $row["Address"] . "</td>";
            echo "<td>" . $row["Email"] . "</td>";
            echo "<td>" . $row["Guardian_Number"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No application form entries found.</td></tr>";
    }

    // Close the connection
    $conn->close();
}
function displayRoomAvailable(){
    $conn = startDatabase(); // Assign the returned connection to a variable
    // Fetch data from the "applications" table
    $sql = "SELECT RoomNum, Kind_of_Room, Available FROM rooms";
    $result = $conn->query($sql);

    // Display the data in the table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["Available"] == 0) {
                echo '<tr class="availability red">';
                echo "<td>" . $row["RoomNum"] . "</td>";
                echo "<td>" . $row["Kind_of_Room"] . "</td>";
                echo "<td>" . $row["Available"] . "</td>";
            } else {
                echo '<tr class="availability">';
                echo "<td>" . $row["RoomNum"] . "</td>";
                echo "<td>" . $row["Kind_of_Room"] . "</td>";
                echo "<td>" . $row["Available"] . "</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No rooms available.</td></tr>";
    }

    // Close the connection
    $conn->close();
}
function deleteReservation(){
    $conn = startDatabase(); // Assign the returned connection to a variable

    if (isset($_POST['delete']) && isset($_POST['row_id'])) {
        // Get the ID of the row to be deleted
        $rowId = $_POST['row_id'];

        // Delete the row from the database
        $sql = "DELETE FROM reservation WHERE id = $rowId";

        if ($conn->query($sql) === TRUE) {
            echo "Successfully processed!";
        } else {
            echo "Error deleting row: " . $conn->error;
        }
    } elseif (isset($_POST['submit']) && isset($_POST['row_id'])) {
        // Get the ID of the row to be processed
        $rowId = $_POST['row_id'];

        // Retrieve the data from the reservation table
        $sql2 = "SELECT * FROM reservation WHERE id = $rowId";
        $result = $conn->query($sql2);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Extract the relevant data
            $roomTaken = $row["room_taken"];
            $name = $row["last_name"] . ', ' . $row["first_name"] . ' ' . $row["middle_name"];
            $address = $row["address"];
            $email = $row["email"];
            $guardianPhone = $row["guardian_phone"];
            $gender = $row["gender"];

            // Update the available slots in the rooms table
            $sql1 = "UPDATE rooms SET Available = Available - 1 WHERE RoomNum = '$roomTaken'";
            if ($conn->query($sql1) === TRUE) {
                echo "Successfully updated available slots!";
            } else {
                echo "Error updating available slots: " . $conn->error;
            }

            // Insert the data into the occupied_rooms table
            $sql3 = "INSERT INTO occupied_rooms (RoomNum, Name, Gender, Address, Email, Guardian_Number)
                     VALUES ('$roomTaken', '$name', '$gender', '$address', '$email', '$guardianPhone')";

            if ($conn->query($sql3) === TRUE) {
                echo "Successfully processed!";
            } else {
                echo "Error inserting data into occupied_rooms table: " . $conn->error;
            }
        }

        // Delete the row from the reservation table
        $sql4 = "DELETE FROM reservation WHERE id = $rowId";

        if ($conn->query($sql4) === TRUE) {
            echo "";
        } else {
            echo "Error deleting row: " . $conn->error;
        }
    }
    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ML Dormitory Admin</title>
    <link rel="icon" type="image/png" href="https://drive.google.com/uc?export=view&id=1MVx5IU6m_QeOVHLPYrlHXEXgyeFs1PZE">
    <meta name="description" content="Connect with people over travelling">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="adminside.css">
    <!-- google font api -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <script>
        function filterTable() {
            var select = document.getElementById("roomFilter");
            var selectedValue = select.value.toUpperCase();
            var table = document.getElementById("roomTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var roomNumber = rows[i].getElementsByTagName("td")[0];
                if (roomNumber) {
                    var textValue = roomNumber.textContent || roomNumber.innerText;
                    if (selectedValue === "" || textValue.toUpperCase() === selectedValue) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }            
    </script>
</head>
<body>
    <header id="header">
        <h2><a href="#">ML Dormitory Admin</a></h2>
        <nav>
            <li><a href="#">Home</a></li>
            <li><a href="#status">Room's Status</a></li>
            <li><a href="#occu">Occupied Rooms</a></li>
            <li><a href="#reservations">Reservations</a></li>
            <li><a href="ML Dormitory.html" class="action_bttn">Go Back To User</a></li>
        </nav>
    </header>
    <section class="banner">
    <img src="https://drive.google.com/uc?export=view&id=1AdfFhaA3snj6XlPBkQ13xvT7QBCzB8uG" alt="dorm" class="fitBg">
    <div class="content">
        <div class="animation">
          <h2>ML Dormitory Dashboard</h2>
        </div>
    </div> 
  </section>
    <div class=overview>
        <div class="simple-grid__cell simple-grid__cell--1/3" id="status">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row align-items-end align-items-lg-start">
                        <div class="col-7 col-lg-12 text-left text-lg-center mb-0 pb-0">
                                <h4>CURRENT ROOM'S STATUS</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <!--TABLE FOR AVAILABILITY OF ROOMS-->
                <table class="RoomsTable">
                    <tr>
                        <th>Room</th>
                        <th>Type Of Room</th>
                        <th>Available Slots</th>
                    </tr>
                    <?php displayRoomAvailable(); // Call the function to display the form entries ?>
                </table>
            </div>
        </div>

        <div class="simple-grid__cell simple-grid__cell--fill">
            <div class="panel" id="occu">
                <div class="panel-heading">
                    <div class="row align-items-end align-items-lg-start">
                        <div class="col-7 col-lg-12 text-left text-lg-center mb-0 pb-0">
                            <h4>OCCUPIED ROOMS</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="filter">
                    <select class="combo" id="roomFilter" onchange="filterTable()">
                        <option value="">All Rooms</option>
                        <?php
                            // Example code to connect to a MySQL database
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

                            // Fetch room numbers from the database
                            $sql = "SELECT RoomNum FROM occupied_rooms GROUP BY RoomNum";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["RoomNum"] . "'>" . $row["RoomNum"] . "</option>";
                                }
                            }

                            // Close the connection
                            $conn->close();
                        ?>
                    </select>
                </div>    
                <div class="filter ed">
                    <table id="roomTable" class="RoomsTable">
                        <tr>
                            <th>Room</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Guardian Phone</th>
                        </tr>
                        <?php displayOccupants(); // Call the function to display the form entries ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="overview">
        <div class="simple-grid__cell simple-grid__cell--fill">
            <div class="panel" id="reservations">
                <div class="panel-heading">
                    <div class="row align-items-end align-items-lg-start">
                        <div class="col-7 col-lg-12 text-left text-lg-center mb-0 pb-0">
                                <h3>RESERVATIONS QUEUE</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter ed">
                <table class="RoomsTable">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Guardian Phone</th>
                        <th>Gender</th>
                        <th>Room Taken</th>
                        <th>Start Date</th>
                        <th colspan="2">Accept Form?</th>
                    </tr>
                    <?php displayFormEntries(); // Call the function to display the form entries ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
