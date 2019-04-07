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

  if (isset($_POST['textinput']) && $_POST['textinput'] != 'Enter ciphertext/plaintext here...') {
      echo <<<_END
      <div class="loginmessage">
          <p><a style="color:red">Input receieved! {$_POST['cryptotype']} was used to encrypt/decrypt.</a></p>
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
    <select name="cryptotype">
      <option value="substitution_encrypt">Substitution Encrypt</option>
      <option value="substitution_decrypt">Substitution Decrypt</option>
      <option value="double_transposition_encrypt">Double Transposition Encrypt</option>
      <option value="double_transposition_decrypt">Double Transposition Decrypt</option>
      <option value="RC4_encrypt">RC4 Encrypt</option>
      <option value="RC4_decrypt">RC4 Decrypt</option>
    </select>
    <input type='submit' value='Encrypt/Decrypt'>
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