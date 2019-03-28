<?php
  require_once 'login.php';
  require_once 'tools.php';
  echo "<link rel='stylesheet' href='style.css'>";
  echo "<link rel='shortcut icon' href='favicon.png;>";
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = get_post($conn, 'username');
    $password = get_post($conn, 'password');
    $hashed_password = salt_and_hash($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashedpassword'";
    $result = $conn->query($query);
    if (!$result) 
      echo "Username or password is incorrect!<br>";
    else
      echo "Access granted to '$username'!<br>";
  }
  
  echo <<<_END
  <div class="userform">
  <form action="loginform.php" method="post"><pre>
  Username <input type="text" name="username"><br>
  Password <input type="text" name="password"><br>
  <input type="submit" value="Login">
  </pre></form>
  </div>
_END;
  
  $result->close();
  $conn->close();
?>