<?php
  require_once 'tools.php';
  require_once 'cryptotools.php';
  
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
      </div>
_END;

  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die ($conn->connect_error);

  if (isset($_POST['textinput']) && $_POST['textinput'] != 'Enter ciphertext/plaintext here...' &&
    isset($_POST['ciphertype']) && isset($_POST['encryptordecrypt'])) {
      $text_box_input = mysql_entities_fix_string($conn, $_POST['textinput']);
      $result = "Encrypt/decrypt not successful";
      switch ($_POST['ciphertype']) {
        case 'substitution':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            $result = substitution_encrypt($text_box_input, $alphabet);
            echo $result;
          } else {
            $result = substitution_decrypt($text_box_input, $alphabet);
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
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Input receieved, {$_POST['ciphertype']} was used to {$_POST['encryptordecrypt']}.</a></p>
          <p>{$result}</p>
      </div>
_END;
  }
  
  //TODO FIX THIS
  if ($_FILES) {
    if ($_FILES['file']['type'] == 'text/plain') {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Correct file type!</a></p>
      </div>
_END;
    }
  }
  
  echo <<<_END
  <div class="userform">
  <form method='post' action='mainform.php'>
    <textarea name="textinput" cols="100" rows="5" style="border: none" style="padding:5px">Enter ciphertext/plaintext here...</textarea>
    <select name="ciphertype">
      <option value="substitution">Substitution</option>
      <option value="double_transposition">Double Transposition</option>
      <option value="RC4">RC4</option>
    </select>
    <input type='radio' name='encryptordecrypt' value='encrypt'>Encrypt
    <input type='radio' name='encryptordecrypt' value='decrypt'>Decrypt
    <input type='submit' value='Submit'>
  </form>
  </div>
_END;
  
  echo <<<_END
  <div class="userform">
  <form method='post' action='mainform.php' enctype='multipart/form-data'>
    Or Select .txt File: <input type='file' name='Filename' size='16'>
    <input type='submit' value='Upload File'>
  </form>
  </div>
_END;
?>