<?php
  require_once 'login.php';
  
  function salt_and_hash($a) {
    $salt1 = "^m%1";
    $salt2 = "7u^w";
    return hash('next123', $salt1, $a, $salt2);
  }
  
  function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
  }
  
  function add_user($username, $password) {
    global $hn, $un, $pw, $db;
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die ($conn->connect_error);
    $query = "INSERT INTO users VALUES" .
            "('$username','$password')";
    $result = $conn->query($query);
    if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
    
  }
?>