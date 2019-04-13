<?php
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

  if (isset($_POST['textinput']) && $_POST['textinput'] != 'Enter ciphertext/plaintext here...' &&
    isset($_POST['ciphertype']) && isset($_POST['encryptordecrypt'])) {
      //TODO sanitize?
      switch ($_POST['ciphertype']) {
        case 'substitution':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            substitution_encrypt($to_encrypt, $alphabet);
          } else {
            substitution_decrypt($to_encrypt, $alphabet);
          }
        case 'double_transposition':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            double_transposition_encrypt($to_encrypt, $num_rows, $num_cols, $row_perm, $col_perm);
          } else {
            double_transposition_decrypt($to_decrypt, $num_rows, $num_cols, $row_perm, $col_perm);
          }
        case 'RC4':
          if ($_POST['encryptordecrypt'] == 'encrypt') {
            RC4_encrypt($to_encrypt, $key);
          } else {
            RC4_decrypt($to_decrypt, $key);
          }
      }
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Input receieved! {$_POST['ciphertype']} was used to encrypt/decrypt.</a></p>
          <p>{$_POST['textinput']}</p>
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