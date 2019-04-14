<?php
  require_once 'login.php';
  require_once 'tools.php';
  
  echo <<<_END
  <html>
    <head>
      <title>Login Page</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
    </head>
    <body>
    <div class="header">
        <h1>Decryptoid. >_</h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script>
      </div>
_END;
  
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  /*
  if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $username_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
    $password_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $stmt->close();
    if (!result) die ($conn->error);
    elseif ($result->num_rows > 0 ) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $result->close();
      $token = saltandhash($password_temp);
      if ($token == $row['password']) {
        echo '<script>window.location.href = "mainform.php";</script>';
      } 
    } else {
        echo <<<_END
        <div class="loginmessage">
            <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
        </div>
_END;
    }
  } else {
      header('WWW-Authenticate: Basic realm="Restricted Section"');
      header('HTTP/1.0 401 Unauthorized');
      die("Please enter your username and password");
  }
  */
  
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    $hashed_password = salt_and_hash($password);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
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