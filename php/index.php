<?php
  echo <<<_END
  <html>
    <head>
      <title>Decryptoid</title>
      <link rel='stylesheet' href='../css/style.css'>
      <link rel='shortcut icon' href='../assets/favicon.png'>
      <script type="text/JavaScript" src="../js/control.js"></script>
    </head>
    <body>
      <div class="header">
        <h1>Decryptoid. >_</h1>
        <h1 id="blink">|</h1>
        <script src="../js/htimer.js"></script>
      </div>
      <div class="main">
        <div class="title">
          <h1>Decryptoid</h1>
        </div>
        <div class="link" onClick="location.href='../php/loginform.php'">
          <p>Login</p>
        </div>
        <div class="link" onClick="location.href='../php/registerform.php'">
          <p>Register</p>
        </div>
        <div class="description">
          <p>This is a web application meant to encrypt/decrypt an input plaintext/ciphertext. Login or register above to begin.</p>
        </div>
      </div>
    </body>
  </html>
_END;
?>