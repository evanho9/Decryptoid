<?php
  require_once 'login.php';
  require_once 'tools.php';
  echo "<link rel='stylesheet' href='style.css'>";
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = get_post($conn, 'username');
    $password = get_post($conn, 'password');
    if (user_exists($username)) {
      echo "Username '$username' exists in the database already.";
    } else {
      add_user($username, $password);
    }
  }
  
  echo <<<_END
  <div class="userform">
  <form action="loginform.php" method="post"><pre>
  Username <input type="text" name="username"><br>
  Password <input type="text" name="password"><br>
  <input type="submit" value="Submit">
  </pre></form>
  </div>
_END;
  
  //Print all the user names and passwords
  $query = "SELECT * FROM users";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);
  $rows = $result->num_rows;
  for ($i=0; $i<$rows; ++$i) {
    $result->data_seek($i);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo <<<_END
  <pre>
  Username $row[0]
  Password $row[1]
  </pre>
_END;
  }
  
  $result->close();
  $conn->close();
?>