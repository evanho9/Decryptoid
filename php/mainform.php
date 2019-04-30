<?php
  require_once 'tools.php';
  require_once 'cryptotools.php';
  
  session_start();
  
  //Session safety check
  if (isset($_SESSION['check']) && $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))
    different_user();
  if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }

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
        <script src="../js/control.js"></script>
        <nav>
          <a href='userpage.php'>User History</a>
        </nav>
      </div>
_END;
  header('Content-Type: text/html; charset=utf-8');
  create_database();
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);
  create_userfiles_table($conn);
  
  //Logged in check
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true ) {
    //$logged_in = true;
    //destroy_session_and_data();
  } else {
      echo <<<_END
      <div class="message">
          <p><a style="color:red">Session not valid!</a> Click <a href="loginform.php" style="color:blue">here</a> to login!</p>
      </div>
_END;
  }

  //Main textbox crypto logic
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true
      && isset($_POST['submitbutton']) && !empty($_POST['textinput']) 
      && !is_uploaded_file($_FILES['userfile']['tmp_name']) 
      && isset($_POST['ciphertype']) && isset($_POST['encryptordecrypt'])) {
      $text_box_input = strtolower(mysql_entities_fix_string($conn, $_POST['textinput']));
      $_SESSION['lasttextinput'] = $text_box_input;
      $cipher_type = mysql_entities_fix_string($conn, $_POST['ciphertype']);
      $encrypt_or_decrypt = mysql_entities_fix_string($conn, $_POST['encryptordecrypt']);
      $result = "Encrypt/decrypt not successful";
      switch ($cipher_type) {
        case 'substitution':
          $key =  strtolower(mysql_entities_fix_string($conn, $_POST['key']));
          $_SESSION['lastkey'] = $key;
          $alphabet = string_to_alphabet_map(mysql_entities_fix_string($conn, $_POST['key']));
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = substitution_encrypt($text_box_input, $alphabet);
          } else {
            $result = substitution_decrypt($text_box_input, $alphabet);
          }
          echo <<<_END
      <div class="message">
          <p><a style="color:red">Input: [ $text_box_input ] receieved, $cipher_type was used to $encrypt_or_decrypt with key: $key</a></p>
          <p>$result</p>
      </div>
_END;
          break;
        case 'double transposition':
          $row_perm = mysql_entities_fix_string($conn, $_POST['rowperm']);
          $col_perm = mysql_entities_fix_string($conn, $_POST['colperm']);
          $_SESSION['lastrowperm'] = $row_perm;
          $_SESSION['lastcolperm'] = $col_perm;
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = double_transposition($text_box_input, $row_perm, $col_perm, true);
          } else {
            $result = double_transposition($text_box_input, $row_perm, $col_perm, false);
          }
          echo <<<_END
      <div class="message">
      <p><a style="color:red">Input: [ $text_box_input ] receieved, $cipher_type was used to $encrypt_or_decrypt with key: $row_perm and $col_perm</a></p>
          <p>$result</p>
      </div>
_END;
          break;
        case 'RC4':
          $key = mysql_entities_fix_string($conn, $_POST['key']);
          $_SESSION['lastkey'] = $key;
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = utf8_encode(RC4($text_box_input, $key));
          } else {
            $result = utf8_encode(RC4($text_box_input, $key));
          }
          echo <<<_END
      <div class="message">
          <p><a style="color:red">Input: [ $text_box_input ] receieved, $cipher_type was used to $encrypt_or_decrypt with key: $key</a></p>
          <p>$result</p>
      </div>
_END;
          break;
      }
      store_content($conn, $_SESSION['username'], $cipher_type, $encrypt_or_decrypt, $text_box_input, $result);
  }
  
  //Upload file logic and file crypto logic
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true 
      && isset($_POST['submitbutton']) && is_uploaded_file($_FILES['userfile']['tmp_name']) 
      && isset($_POST['ciphertype']) && isset($_POST['encryptordecrypt'])) {
    $file_name_without_extension = mysql_entities_fix_string($conn, pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME));
    switch ($_FILES['userfile']['type']) {
      case 'text/plain' : $ext = 'txt'; break;
      default           : $ext = ''; break;
    }
    if ($ext) {
      $n = "$file_name_without_extension.$ext";
      move_uploaded_file($_FILES['userfile']['tmp_name'], $n);
      $text_file_content = file_get_contents($n) or die("Failed to open $n");
      $text_file_content = mysql_entities_fix_string($conn, $text_file_content);
      $cipher_type = mysql_entities_fix_string($conn, $_POST['ciphertype']);
      $encrypt_or_decrypt = mysql_entities_fix_string($conn, $_POST['encryptordecrypt']);
      $result = "Encrypt/decrypt not successful";
      switch ($cipher_type) {
        case 'substitution':
          $key =  strtolower(mysql_entities_fix_string($conn, $_POST['key']));
          $_SESSION['lastkey'] = $key;
          $alphabet = string_to_alphabet_map(mysql_entities_fix_string($conn, $_POST['key']));
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = substitution_encrypt($text_file_content, $alphabet);
          } else {
            $result = substitution_decrypt($text_file_content, $alphabet);
          }
          echo <<<_END
      <div class="message">
          <p><a style="color:red">Input receieved, $cipher_type was used to $encrypt_or_decrypt with key: $key</a></p>
          <p>$result</p>
      </div>
_END;
          break;
        case 'double transposition':
          $row_perm = mysql_entities_fix_string($conn, $_POST['rowperm']);
          $col_perm = mysql_entities_fix_string($conn, $_POST['colperm']);
          $_SESSION['lastrowperm'] = $row_perm;
          $_SESSION['lastcolperm'] = $col_perm;
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = double_transposition($text_file_content, $row_perm, $col_perm);
          } else {
            $result = double_transposition($text_file_content, $row_perm, $col_perm);
          }
          echo <<<_END
      <div class="message">
      <p><a style="color:red">Input receieved, $cipher_type was used to $encrypt_or_decrypt with key: $row_perm and $col_perm</a></p>
          <p>$result</p>
      </div>
_END;
          break;
        case 'RC4':
          $key = mysql_entities_fix_string($conn, $_POST['key']);
          $_SESSION['lastkey'] = $key;
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = utf8_encode(RC4($text_file_content, $key));
          } else {
            $result = utf8_encode(RC4($text_file_content, $key));
          }
          echo <<<_END
      <div class="message">
          <p><a style="color:red">Input receieved, $cipher_type was used to $encrypt_or_decrypt with key: $key</a></p>
          <p>$result</p>
      </div>
_END;
          break;
      }
      store_content($conn, $_SESSION['username'], $cipher_type, $encrypt_or_decrypt, $text_file_content, $result);
    } else {
       echo <<<_END
  <div class="message">
  <pre>
  Error! '$file_name_without_extension' is not an accepted text file! Try again!
  </pre>
  </div>
_END;
    }
  }
  
  //Main crypto form
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo <<<_END
  <div class="userform">
  <form method='post' action='mainform.php' accept-charset="UTF-8" enctype='multipart/form-data'>
    <textarea name="textinput" cols="100" rows="5" style="border: none" style="padding:5px" placeholder="Enter plaintext/ciphertext here..."></textarea>
    Or Select .txt File: <input type='file' name='userfile' size='16'>
    <br><br>
    <select id="cipherselector" name="ciphertype">
      <option value="substitution">Substitution</option>
      <option value="double transposition">Double Transposition</option>
      <option value="RC4">RC4</option>
    </select>
    <br><br>
    Key: <input id="key" name="key" size="27" maxchars="26" value="abcdefghijklmnopqrstuvwxyz" type="text">
    <input name="generateKey" value="Generate Random Key" onclick="GenRandKey()" type="button">
    <br><br>
    Row Permutation: <input id='rowperm' name='rowperm' value="0,1,2"" type='text'>
    Column Permutation: <input id="colperm" name="colperm" value="0,1,2,3" type="text">
    <br><br>
    <input type='radio' name='encryptordecrypt' checked="checked" value='encrypt'>Encrypt
    <input type='radio' name='encryptordecrypt' value='decrypt'>Decrypt
    <input type='submit' name='submitbutton' value='Submit'>
  </form>
  </div>
_END;
  }

  //End duties
  $conn->close();
  $_POST = array();
  $_FILES = array();
  echo "</body></html>";
?>