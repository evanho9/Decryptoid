<?php
  require_once 'login.php';
  
  function salt_and_hash($a) {
    $salt1 = "^m%1";
    $salt2 = "7u^w";
    return hash('ripemd128', $salt1 . $a . $salt2);
  }
  
  function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
  }
  
  function user_exists($username) {
    global $hn, $un, $pw, $db;
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die ($conn->connect_error);
    $query = "SELECT * FROM users WHERE username=''$username''";
    $result = $conn->query($query);
    if (!$result) return false;
    return true;
  }
  
  function add_user($email, $username, $password) {
    global $hn, $un, $pw, $db;
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die ($conn->connect_error);
    
    $password = salt_and_hash($password);
    $query = "INSERT INTO users VALUES" .
            "('$email','$username','$password')";
    $result = $conn->query($query);
    if (!$result) echo "INSERT failed. <br>" . $conn->error . "<br>";
    
  }
?>