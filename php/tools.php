<?php
  require_once 'login.php';
  
  function create_database($conn) {
    global $db;
    $query = "CREATE DATABASE IF NOT EXISTS $db";
    $result = $conn->query($query);
    if (!$result) die ($conn->error);
    $conn->select_db($db);
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
                     name VARCHAR(32) NOT NULL,
                     textfilecontent BLOB,
                     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
                     ) ENGINE MyISAM";
        $result = $conn->query($query);
        if (!$result) die ("Database access failed: " . $conn->error);
      }
    } else if (!$result) die ("Database access failed: " . $conn->error);
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
  
  function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn,$string));
  }
  
  function mysql_fix_string($conn, $string) {
    if (get_magic_quotes_gpc())
      $string = stripcslashes($string);
    return $conn->real_escape_string($string);
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
?>