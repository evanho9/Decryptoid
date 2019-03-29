<?php
  require_once 'login.php';
  require_once 'tools.php';
  echo "<link rel='stylesheet' href='style.css'>";
  echo "<link rel='shortcut icon' href='favicon.png;>";
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  
  if (isset($_POST['email'] && isset($_POST['username']) && isset($_POST['password']) ) {
    $email = get_post($conn, $_POST['email']);
    $username = get_post($conn, $_POST['username']);
    $password = get_post($conn, $_POST['password']);
    if (user_exists($username)) {
      echo "Username '$username' exists in the database already.<br>";
    } else {
      add_user($email, $username, $password);
      echo "Successfully registered! <br>";
    }
  }
  
  echo <<<_END
  <div class="userform">
  <form action="registerform.php" method="post"><pre>
  Email <input type="text" name="email"><br>
  Username <input type="text" name="username"><br>
  Password <input type="text" name="password"><br>
  <input type="submit" value="Register">
  </pre></form>
  </div>
_END;
  
  //Print all the user names and passwords
  $query = "SELECT * FROM users";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error . "<br>");
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