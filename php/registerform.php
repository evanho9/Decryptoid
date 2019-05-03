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
  
  //Logout logic
  if (isset($_POST['logoutbutton'])) {
    different_user();
  }
  
  //Database preparation
  create_database();
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  create_usercredentials_table($conn);
  
  $username = $password = $email = "";
  //Sanitation
  if (isset($_POST['username']))
    $username = mysql_entities_fix_string($conn, $_POST['username']);
  if (isset($_POST['password']))
    $password = mysql_entities_fix_string($conn, $_POST['password']);
  if (isset($_POST['email']))
    $email = mysql_entities_fix_string($conn, $_POST['email']);
  
  //Validation
  $fail = validate_username($username);
  $fail .= validate_password($password);
  $fail .= validate_email($email);
  
  //Page preparation
  echo <<<_END
  <html>
    <head>
      <title>Register Page</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
      <script src="../js/validation.js"></script>
    </head>
    <body>
    <div class="header">
        <h1><a href='index.php'>Decryptoid. >_</a></h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script> 
      </div>
_END;

  //Logged in already logic
  if (isset($_SESSION['loggedin'])) die (<<<_END
      <div class="message">
          <p><a style="color:red">Already logged in!</a> Click <a href="mainform.php" style="color:blue">here</a> to proceed instead.</p>
          <form method='post' action='loginform.php' accept-charset="UTF-8" enctype='multipart/form-data'>
          or
          <input type='submit' name='logoutbutton' value='Logout'><br>
          </form> 
      </div>
_END
);
  
  //Validation check
  if ($fail != "") die (<<<_END
    <div class="userform">
      <form method="post" action="registerform.php" onSubmit="return validateRegistration(this)">
      Register here:<br><br>
      Email: <input type="text" name="email"><br><br>
      Username: <input type="text" name="username"><br><br>
      Password: <input type="password" name="password"><br><br>
      <input type="submit" name="registerbutton" value="Register">
      </form>
    </div>
_END
);
  
  //Register logic
  if (isset($_POST['registerbutton']) && !empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $email = mysql_entities_fix_string($conn, $_POST['email']);
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    if (user_exists($conn, $username)) {
      echo <<<_END
      <div class="message">
          <p><a style="color:red">Username is taken!</a> Click <a href="loginform.php" style="color:blue">here</a> to login instead.</p>
      </div>
_END;
    } else {
      add_user($conn, $email, $username, $password);
      echo '<script>window.location.href = "loginform.php";</script>';
    }
  }
  
  //Register form
  if (!isset($_SESSION['loggedin'])) {
    echo <<<_END
    <div class="userform">
      <form method="post" action="registerform.php" onSubmit="return validateRegistration(this)">
      Register here:<br><br>
      Email: <input type="text" name="email"><br><br>
      Username: <input type="text" name="username"><br><br>
      Password: <input type="password" name="password"><br><br>
      <input type="submit" name="registerbutton" value="Register">
      </form>
    </div>
_END;
  }
  
  //End duties
  $conn->close();
  $_POST = array();
  echo "</body></html>";
?>