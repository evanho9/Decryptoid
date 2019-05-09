<?php  

  //Server-side validation tools
  //TODO make these better
  function validate_username($field) {
    $min_characters = 5;
    if (preg_replace('/\s+/', '', $field) == '') return "No username was entered<br>";
    else if (strlen($field) < $min_characters)
      return "Usernames must be at least $min_characters characters<br>";
    else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
      return "Only letters, numbers, - and _ in usernames<br>";
    return "";
  }
  
  function validate_password($field) {
    $min_characters = 6;
    if (preg_replace('/\s+/', '', $field) == '') return "No password was entered<br>";
    else if (strlen($field) < $min_characters)
      return "Passwords must be at least $min_characters characters<br>";
  }
  
  function validate_email($field) {
    if (preg_replace('/\s+/', '', $field) == '') return "No email was entered<br>"; 
    else if (!preg_match("/\S+@\S+\.\S+/", $field))
      return "The email address is invalid<br>";
    return "";
  }
?>