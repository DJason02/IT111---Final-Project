<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Filter Example</title>
    <style>
        .filtered {
            background-color: red;
        }
    </style>
</head>
<body>
    <h1>Room Filter Example</h1>
    <!--GET FILTER OF ROOMS FOR MGA TAO NA NASA LOOB-->
    <select id="roomFilter" onchange="filterTable()">
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
        $sql = "SELECT RoomNum FROM rooms";
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

    <table id="roomTable">
        <tr>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Location</th>
        </tr>
        <tr>
            <td>Room 1</td>
            <td>Single</td>
            <td>John Doe</td>
            <td>25</td>
            <td>Male</td>
            <td>City A</td>
        </tr>
        <tr>
            <td>Room 1</td>
            <td>Double</td>
            <td>Jane Smith</td>
            <td>30</td>
            <td>Female</td>
            <td>City B</td>
        </tr>
        <tr>
            <td>301</td>
            <td>Single</td>
            <td>Michael Johnson</td>
            <td>28</td>
            <td>Male</td>
            <td>City C</td>
        </tr>
        <!-- Add more rows as needed -->
    </table>

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
</body>
</html>
