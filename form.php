<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>ML Dormitory</title>
    <link rel="icon" type="image/png" href="https://drive.google.com/uc?export=view&id=1MVx5IU6m_QeOVHLPYrlHXEXgyeFs1PZE">
    <link rel="stylesheet" href="form.css" />
    <meta name="description" content="Connect with people over travelling">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300&family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  </head>

  <body>

    <header id="header">
      <h2><a href="#">ML Dormitory</a></h2>
      <nav>
        <li><a href="ML Dormitory.html">Home</a></li>
        <li><a href="ML Dormitory.html#about">About</a></li>
        <li><a href="ML Dormitory.html#testimonials">Testimonials</a></li>
        <li><a href="ML Dormitory.html#footer">Contact</a></li>        
      </nav>
    </header>

    <section class="banner1">
      <div class="content1">
        <div class="animation">
          <ul>
            <li class="li1">Family Sized Room</li><br>
            <li class="li2">Bed Space Room, Six Slot</li><br>
            <li class="li3">Bed Space Room, Four Slot</li><br>
          </ul>
          <div class="booking"><img src="https://drive.google.com/uc?export=view&id=1BHDYhn3ePasyQyGrft9Et5IUkULEogK4" alt="room"></div>                                                
        </div>
      </div>
    </section>

    <section class="banner">
      <div class="container"> 
        <div class="content">
          <div class="animation">
            <br>
            <h1>ML Dormitory Reservation Form</h1>
            <p>Please fill out this form with the required information</p>
          </div>            
            
          <form action="process_form.php" method="post"> <!-- Specify the action and method for form submission -->
            <fieldset class="animation">
              <label for="first-name">Enter Your First Name: <input class="input-resize" id="first-name" placeholder="First Name" name="first-name" type="text" required /></label>
              <label for="last-name">Enter Your Last Name: <input class="input-resize" id="last-name" placeholder="Last Name" name="last-name" type="text" required /></label>
              <label for="minit-name">Enter Your Middle Name: <input class="input-resize" placeholder="Middle Name" id="minit-name" name="minit-name" type="text"/></label>
              <label for="Address">Enter Your Address: <input class="input-resize" id="Address" placeholder="Address" name="Address" type="text" required /></label>
              <label for="email">Enter Your Email: <input class="input-resize" id="email" name="email" placeholder="Email" type="email" required /></label>
              <label for="phone-number">Enter your Guardian Phone Number:<input class="input-resize" placeholder="Phone Number" id="phone-number" name="phone-number" type="number" oninput="checkPhoneNumberLength(this)" required /></label>
              
              <!-- Phone - Max Length 11 -->
              <script>
                function checkPhoneNumberLength(input) {
                  if (input.value.length > 11) {
                    input.value = input.value.slice(0, 11); // Truncate input if it exceeds 11 characters
                  }
                }
              </script>
              <!-- Phone - Max Length 11 -->
              
              <br><label for="Male">Select Your Gender:</label>
              <label for="Male"><input id="Male" type="radio" name="gender" class="inline" value="Male" required /> Male</label>
              <label for="Female"><input id="Female" type="radio" name="gender" class="inline" value="Female" required /> Female</label> 
            </fieldset>

            <fieldset class="animation">
              <label for="room">Which Room Would you like to Reserve? The following are the available slots:    
                  <select id="room" name="room" required>
                      <option value="">(select one)</option>
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
          
                      // Fetch room options from the database
                      $sql = "SELECT RoomNum, Available FROM `rooms` WHERE NOT `Available` = 0";
                      $result = $conn->query($sql);
          
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $optionText = $row['RoomNum'] . ' (' . $row['Available'] . ')';
                            echo '<option value="' . $row['RoomNum'] . '">' . $optionText . '</option>';
                          }
                      }
          
                      // Close the connection
                      $conn->close();
                      ?>
                  </select>
              </label>
          
              <label for="start-date">Start Date:</label>
              <input type="date" id="start-date" name="start-date" required>
          </fieldset>
          
            <fieldset>
              <input type="submit" value="Submit" />   
            </fieldset>
          </form>  
        </div>  
      </div> 
    </section> 
  </body>
</html>
