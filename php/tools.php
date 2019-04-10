<?php
  require_once 'login.php';
  
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
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
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
    $stmt = $conn->prepare("INSERT INTO users VALUES(?,?,?)");
    $stmt->bind_param('sss', $email, $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if (!$result) echo "INSERT failed. <br>" . $conn->error . "<br>";
  }
?>