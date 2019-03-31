<?php
  require_once 'login.php';
  require_once 'tools.php';
  
  echo <<<_END
  <html>
    <head>
      <title>Login Page</title>
      <link rel='stylesheet' href='style.css'>
      <link rel='shortcut icon' href='assets/favicon.png'>
    </head>
    <body>
_END;
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = get_post($conn, $_POST['username']);
    $password = get_post($conn, $_POST['password']);
    $hashed_password = salt_and_hash($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $result = $conn->query($query);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
      echo '<script>window.location.href = "mainform.php";</script>';
    }
    else {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
      </div>
_END;
    }
    $result->close();
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
  
  
  $conn->close();
  
  echo "</body></html>";
?>