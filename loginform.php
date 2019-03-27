<?php
  require_once 'login.php';
  require_once 'tools.php';
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = get_post($conn, 'username');
    $password = get_post($conn, 'password');
    add_user($username, $password);
  }
  
  echo <<<_END
  <form action="loginform.php" method="post"><pre>
  Username<input type="text" name="username">
  Password<input type="text" name="password">
  <input type="submit" value="Submit">
  </pre></form>
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