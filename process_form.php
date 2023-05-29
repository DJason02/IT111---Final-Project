<?php
// Retrieve form data
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$middleName = $_POST['minit-name'];
$address = $_POST['Address'];
$email = $_POST['email'];
$guardianPhone = $_POST['phone-number'];
$gender = $_POST['gender'];
$roomTaken = $_POST['room'];
$startDate = $_POST['start-date'];

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

// SQL query to insert the form data into a table
$sql = "INSERT INTO reservation (first_name, last_name, middle_name, address, email, guardian_phone, gender, room_taken, start_date) 
VALUES ('$firstName', '$lastName', '$middleName', '$address', '$email', '$guardianPhone', '$gender', '$roomTaken', '$startDate')";

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {
  echo '<script language="javascript" type="text/javascript"> alert("message successfully Inserted");  window.location = "Ml Dormitory.html"; </script>';
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
