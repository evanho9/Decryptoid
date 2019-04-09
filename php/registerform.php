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
  
  if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    $email = mysql_fix_string($conn, $_POST['email']);
    $username = mysql_fix_string($conn, $_POST['username']);
    $password = mysql_fix_string($conn, $_POST['password']);
    if (user_exists($username)) {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Username is taken!</a> Click <a href="loginform.php" style="color:blue">here</a> to login instead.</p>
      </div>
_END;
    } else {
      add_user($email, $username, $password);
    }
  }
  
  echo <<<_END
  <div class="userform">
  <form action="registerform.php" method="post"><pre>
  Email    <input type="text" name="email"><br>
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