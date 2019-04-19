<?php
  require_once 'tools.php';
  require_once 'cryptotools.php';
  
  session_start();
  
  if (isset($_SESSION['check']) && $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))
    different_user();
  if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }
  
  echo <<<_END
  <html>
    <head>
      <title>Decryptoid Main Page</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
    </head>
    <body>
      <div class="header">
        <h1>Decryptoid. >_</h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script>
        <script src="../js/control.js"></script>
      </div>
_END;
  
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true ) {
    //$logged_in = true;
    //destroy_session_and_data();
  } else {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Session not valid!</a> Click <a href="loginform.php" style="color:blue">here</a> to login!</p>
      </div>
_END;
  }

  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);

  if (isset($_POST['textboxbutton']) && !empty($_POST['textinput']) && $_POST['textinput'] != 'Enter ciphertext/plaintext here...' &&
    isset($_POST['ciphertype']) && isset($_POST['encryptordecrypt'])) {
      $text_box_input = mysql_entities_fix_string($conn, $_POST['textinput']);
      $alphabet = string_to_alphabet_map(mysql_entities_fix_string($conn, $_POST['key']));
      $result = "Encrypt/decrypt not successful";
      switch ($_POST['ciphertype']) {
        case 'substitution':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = substitution_encrypt($text_box_input, $alphabet);
            echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Input receieved, {$_POST['ciphertype']} was used to {$_POST['encryptordecrypt']}.</a></p>
          <p>{$result}</p>
      </div>
_END;
          } else {
            $result = substitution_decrypt($text_box_input, $alphabet);
            echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Input receieved, {$_POST['ciphertype']} was used to {$_POST['encryptordecrypt']}.</a></p>
          <p>{$result}</p>
      </div>
_END;
          }
        case 'double_transposition':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = double_transposition_encrypt($text_box_input, $num_rows, $num_cols, $row_perm, $col_perm);
          } else {
            $result = double_transposition_decrypt($text_box_input, $num_rows, $num_cols, $row_perm, $col_perm);
          }
        case 'RC4':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = RC4_encrypt($text_box_input, $key);
          } else {
            $result = RC4_decrypt($text_box_input, $key);
          }
      }
  }
  
  //TODO FIX THIS
  if (isset($_POST['inputfilebutton']) && $_FILES) {
    if ($_FILES['file']['type'] == 'text/plain') {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Correct file type!</a></p>
      </div>
_END;
    }
  }
  
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true ) {
    echo <<<_END
  <div class="userform">
  <form method='post' action='mainform.php'>
    <textarea name="textinput" cols="100" rows="5" style="border: none" style="padding:5px">Enter ciphertext/plaintext here...</textarea>
    <br>
    <select id="cipherselector" name="ciphertype">
      <option value="substitution">Substitution</option>
      <option value="double_transposition">Double Transposition</option>
      <option value="RC4">RC4</option>
    </select>
    <input type='radio' name='encryptordecrypt' value='encrypt'>Encrypt
    <input type='radio' name='encryptordecrypt' value='decrypt'>Decrypt
    <input type='submit' name='textboxbutton' value='Submit'>
    <br>
    Key: <input id="key" name="key" size="27" maxchars="26" value="abcdefghijklmnopqrstuvwxyz" type="text">
    <input name="generateKey" value="Generate Random Key" onclick="GenRandKey()" type="button">
  </form>
  </div>
  
_END;
  
    echo <<<_END
  <div class="userform">
  <form method='post' action='mainform.php' enctype='multipart/form-data'>
    Or Select .txt File: <input type='file' name='Filename' size='16'>
    <input type='submit' name='inputfilebutton' value='Upload File'>
  </form>
  </div>
_END;
  }

  //End duties
  $conn->close();
  $_POST = array();
  echo "</body></html>";
?>