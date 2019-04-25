<?php
  require_once 'login.php';
  
  //General application tools
  
  function create_database() {
    global $hn, $un, $pw, $db;
    $link = mysqli_connect($hn, $un, $pw);
    if (!$link) die ('Could not connect: ' . mysql_error());
    $db_selected = mysqli_select_db($link, $db);
    if (!$db_selected) {
    $sql = "CREATE DATABASE $db";
    if (!mysqli_query($link, $sql)) die('Error creating database: ' . mysql_error());
    }
  }
  
  function create_usercredentials_table($conn) {
    $query = "SHOW TABLES LIKE 'usercredentials'";
    $result = $conn->query($query);
    if ($result) {
      if ($result->num_rows < 1) {
        $query = "CREATE TABLE usercredentials (
                    email VARCHAR(64) NOT NULL,
                    username VARCHAR(32) NOT NULL PRIMARY KEY,
                    password VARCHAR(64) NOT NULL
                    ) engine MyISAM;";
        $result = $conn->query($query);
        if (!$result) die ("Database access failed: " . $conn->error);
      }
    } else if (!$result) die ("Database access failed: " . $conn->error);
  }
  
  function create_userfiles_table($conn) {
    $query = "SHOW TABLES LIKE 'userfiles'";
    $result = $conn->query($query);
    if ($result) {
      if ($result->num_rows < 1) {
        $query = "CREATE TABLE userfiles (
                     owner VARCHAR(32) NOT NULL,
                     cipher CHAR(20) NOT NULL,
                     encordec CHAR(7) NOT NULL,
                     input BLOB,
                     output BLOB,
                     ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
                     ) ENGINE MyISAM";
        $result = $conn->query($query);
        if (!$result) die ("Database access failed: " . $conn->error);
      }
    } else if (!$result) die ("Database access failed: " . $conn->error);
  }
  
  function store_content($conn, $user, $cipher, $encordec, $input, $output) {
    $stmt = $conn->prepare("INSERT INTO userfiles VALUES(?, ?, ?, ?, ?, NULL, NULL)");
    $stmt->bind_param('sssss', $user, $cipher, $encordec, $input, $output);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  }
  
  function get_text_files_of_user($conn, $user) {
    $stmt = $conn->prepare("SELECT * FROM userfiles WHERE owner=?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
  
  function different_user() {
    destroy_session_and_data();
  }
  
  function destroy_session_and_data() {
    //session_start();
    $_SESSION = array();
    setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
  }
  
  function salt_and_hash($a) {
    $salt1 = "^m%1";
    $salt2 = "7u^w";
    return hash('ripemd128', $salt1 . $a . $salt2);
  }
  
  function user_exists($conn, $username) {
    $stmt = $conn->prepare("SELECT * FROM usercredentials WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close(); 
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) return true;
    else return false;
  }
  
  function add_user($conn, $email, $username, $password) {
    $password = salt_and_hash($password);
    $stmt = $conn->prepare("INSERT INTO usercredentials VALUES(?,?,?)");
    $stmt->bind_param('sss', $email, $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if (!$result) echo "INSERT failed. <br>" . $conn->error . "<br>";
  }
    
  function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn,$string));
  }
  
  function mysql_fix_string($conn, $string) {
    if (get_magic_quotes_gpc())
      $string = stripcslashes($string);
    return $conn->real_escape_string($string);
  }
?>