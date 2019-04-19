<?php
  require_once 'login.php';
  require_once 'tools.php';
  
  session_start();
  
  if (isset($_SESSION['check']) && $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))
    different_user();
  if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }
  
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
  create_database($conn);
  create_usercredentials_table($conn);
  
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
  
    //Login logic
  if (!isset($_SESSION['loggedin']) && isset($_POST['loginbutton']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM usercredentials WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows > 0) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $result->close();
      $token = salt_and_hash($password);
      if ($token == $row['password']) {
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;
        $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
        echo '<script>window.location.href = "mainform.php";</script>';
      } else {
          echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
      </div>
_END;
      }
    } else {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
      </div>
_END;
    }
  }
  
  echo <<<_END
  <div class="userform">
    <form action="loginform.php" method="post"><pre>
    Username <input type="text" name="username"><br>
    Password <input type="password" name="password"><br>
    <input type="submit" name="loginbutton" value="Login">
    </pre></form>
  </div>
_END;
  
  //End duties
  $conn->close();
  $_POST = array();
  echo "</body></html>";
?>