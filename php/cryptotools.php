<?php
  /*
  $test_alphabet = array("a" => "x", "b" => "y", "c" => "z");

  echo substitution_encrypt("abc", $test_alphabet) . "<br>";
  
  echo substitution_decrypt("xyz", $test_alphabet);
  */
  
  /*
  print "<pre>";
  print_r(double_transposition_encrypt("123456789", 3, 3, "0,2,1", "2,1,0"));
  print "</pre>";
  
  print "<pre>";
  print_r(double_transposition_decrypt("321987654", 3, 3, "0,2,1", "2,1,0"));
  print "</pre>";
  */
  
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
  
  function double_transposition_encrypt($to_encrypt, $num_rows, $num_cols, $row_perm, $col_perm) {
    $matrix = array();
    $to_encrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $matrix[$i][$j] = $to_encrypt[$to_encrypt_index];
        $to_encrypt_index++;
      }
    }
    $row_perm = str_replace(' ', '', $row_perm);
    $row_perm = explode(',', $row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $col_perm = explode(',', $col_perm);
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      $temp_matrix[$i] = $matrix[$row_perm[$i]];
    }
    $res_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $res_matrix[$i][$j] = $temp_matrix[$i][$col_perm[$j]];
      }
    }
    return $res_matrix;
  }
  
  function double_transposition_decrypt($to_decrypt, $num_rows, $num_cols, $row_perm, $col_perm) {
    $matrix = array();
    $to_decrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $matrix[$i][$j] = $to_decrypt[$to_decrypt_index];
        $to_decrypt_index++;
      }
    }
    $row_perm = str_replace(' ', '', $row_perm);
    $row_perm = explode(',', $row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $col_perm = explode(',', $col_perm);
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $temp_matrix[$i][$j] = $matrix[$i][$col_perm[$j]];
      }
    }
    
    $res_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      $res_matrix[$i] = $temp_matrix[$row_perm[$i]];
    }
    
    return $res_matrix;
  }
  
  function RC4_encrypt($to_encrypt, $key) {
    
  }
  
  function RC4_decrypt($to_decrypt, $key) {
    
  }
  
?>