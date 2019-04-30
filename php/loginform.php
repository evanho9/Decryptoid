<?php
  require_once 'login.php';
  require_once 'tools.php';
  
  session_start();
  
  //Session safety check
  if (isset($_SESSION['check']) && $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))
    different_user();
  if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }
  
  //Page preparation
  echo <<<_END
  <html>
    <head>
      <title>Login Page</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
    </head>
    <body>
    <div class="header">
        <h1><a href='index.php'>Decryptoid. >_</a></h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script>
      </div>
_END;
  create_database();
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  create_database($conn);
  create_usercredentials_table($conn);
  
  //Logged in already logic
  if (isset($_SESSION['loggedin'])) {
    echo <<<_END
      <div class="message">
          <p><a style="color:red">Already logged in!</a> Click <a href="mainform.php" style="color:blue">here</a> to proceed instead.</p>
          <p><a style="color:red">Click <a href="registerform.php" style="color:blue">here</a> to logout.</p>
      </div>
_END;
  }
  
  //Login logic
  if (!isset($_SESSION['loggedin']) && isset($_POST['loginbutton']) 
      && !empty($_POST['username']) && !empty($_POST['password'])) {
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
      <div class="message">
          <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
      </div>
_END;
      }
    } else {
      echo <<<_END
      <div class="message">
          <p><a style="color:red">Incorrect username or password!</a> Click <a href="registerform.php" style="color:blue">here</a> to register instead.</p>
      </div>
_END;
    }
  }
  
  //Login form
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