<?php
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
  create_userfiles_table($conn);

  //Page preparation
  echo <<<_END
  <html>
    <head>
      <title>Decryptoid Main Page</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      
    </head>
    <body>
      <div class="header">
        <h1><a href='index.php'>Decryptoid. >_</a></h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script>        
      </div>
_END;
  header('Content-Type: text/html; charset=utf-8');
  
  //Logged in check
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true ) {
    //$logged_in = true;
    //destroy_session_and_data();
  } else {
      echo <<<_END
  <div class="message">
    <p><a style="color:red">Not logged in/Session not valid!</a> Click <a href="loginform.php" style="color:blue">here</a> to login!</p>
  </div>
_END;
  }

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true
      && isset($_POST['clearbutton'])) {
    $user = $_SESSION['username'];
    clear_user_history($conn, $user);
  }
  
  //Show user history logic
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $username = mysql_entities_fix_string($conn, $_SESSION['username']);
    echo <<<_END
      <div class="userdata">
        You are currently logged in as $username.<br>
        <form method='post' action='userpage.php' accept-charset="UTF-8" enctype='multipart/form-data'><br>
          <a href='mainform.php'>Go Back to Main Form</a> or <input type='submit' name='logoutbutton' value='Logout'><br><br>
          <input type="submit" name="clearbutton" value="Clear history"><br><br><br>
        Here is your crypto history:
        </form>
_END;
    $user_files = get_text_files_of_user($conn, $username);
    if ($user_files->num_rows > 0) {
      while ($row = $user_files->fetch_assoc()) {
        echo <<<_END
  <p>
  <b>Cipher:</b> {$row['cipher']}
    {$row['encordec']}<br>
  <b>Input:</b> {$row['input']}<br>
  <b>Output:</b> {$row['output']}
  </p>
_END;
      }
    } else {
        echo <<<_END
  <pre>
  <b>No crypto history!</b>
  </pre>
_END;
    }
    echo "</div>";
  } else {
    /*
      echo <<<_END
<div class="message">
  <pre>
  You are not currently logged in.
  <a href='../php/loginform.php'">Login</a> or register in order to see your files or upload more:
  </pre>
</div>
_END;
*/
  }

  //End duties
  $conn->close();
  $_POST = array();
  echo "</body></html>";
?>