<?php

  $test_alphabet = array("a" => "x", "b" => "y", "c" => "z");

  echo substitution_encrypt("abc", $test_alphabet) . "<br>";
  
  echo substitution_decrypt("xyz", $test_alphabet);

  function substitution_encrypt($to_encrypt, $alphabet) {
    $to_encrypt = str_split($to_encrypt);
    $res = str_repeat("*", sizeOf($to_encrypt));
    for ($i=0; $i<sizeOf($to_encrypt); $i++) {
      if ($res[$i] !== " ")
        $res[$i] = $alphabet[$to_encrypt[$i]];
    }
    return $res;
  }
  
  function substitution_decrypt($to_decrypt, $alphabet) {
    $to_decrypt = str_split($to_decrypt);
    $res = str_repeat("*", sizeOf($to_decrypt));
    for ($i=0; $i<sizeOf($to_decrypt); $i++) {
      if ($res[$i] !== " ")
        $res[$i] = array_search($to_decrypt[$i], $alphabet);
    }
    return $res;
  }
  
  function double_transposition_encrypt($to_encrypt, $alphabet) {
    
  }
  
  function double_transposition_decrypt($to_decrypt, $alphabet) {
    
  }
  
  function RC4_encrypt($to_encrypt, $alphabet) {
    
  }
  
  function RC4_decrypt($to_decrypt, $alphabet) {
    
  }
  
?>