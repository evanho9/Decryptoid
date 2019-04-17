<?php
  require_once 'login.php';
  require_once 'tools.php';
  echo <<<_END
  <html>
    <head>
      <title>Register Page</title>
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
  create_userdata_table($conn);
  
  if (isset($_POST['loginbutton']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    $email = mysql_entities_fix_string($conn, $_POST['email']);
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    if (user_exists($conn, $username)) {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Username is taken!</a> Click <a href="loginform.php" style="color:blue">here</a> to login instead.</p>
      </div>
_END;
    } else {
      add_user($conn, $email, $username, $password);
      echo '<script>window.location.href = "loginform.php";</script>';
    }
  }
  
  echo <<<_END
  <div class="userform">
  <form action="registerform.php" method="post"><pre>
  Email    <input type="text" name="email"><br>
  Username <input type="text" name="username"><br>
  Password <input type="password" name="password"><br>
  <input type="submit" value="Register">
  </pre></form>
  </div>
_END;

  $conn->close();
?>