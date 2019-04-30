<?php

  //Crypto test cases
  /*
  $test_alphabet = array("a" => "x", "b" => "y", "c" => "z");

  echo substitution_encrypt("abc", $test_alphabet) . "<br>";
  
  echo substitution_decrypt("xyz", $test_alphabet);
  */
  
  /*
  print "<pre>";
  print_r(double_transposition_encrypt("hello world i am evan", "(0,2,1,3)", "(0,2,3,1,4)"));
  print "</pre>";
 
  print "<pre>";
  print_r(double_transposition_decrypt("hlleoimeavwrloda__n_", "(0,2,1,3)", "(0,2,3,1,4)"));
  print "</pre>";
  */
  
  /*
  print_r(string_to_alphabet_map("dzprjqnucwtayblshgvmfxekio"));
  */
  
  /*
  $encryption = rc4("hello world", "abc");
  echo $encryption;
  $decryption = rc4($encryption, "abc");
  echo $decryption;
  */
  
  //All crypto based functions and helpers
  
  function string_to_alphabet_map($string) {
    $res_map = array("a" => "a", "b" => "b", "c" => "c", "d" => "d", "e" => "e", "f" => "f",
                     "g" => "g", "h" => "h", "i" => "i", "j" => "j", "k" => "k", "l" => "l",
                     "m" => "m", "n" => "n", "o" => "o", "p" => "p", "q" => "q", "r" => "r",
                     "s" => "s", "t" => "t", "u" => "u", "v" => "v", "w" => "w", "x" => "x",
                     "y" => "y", "z" => "z");
    $keys = array_keys($res_map);
    for ($i=0; $i<26; $i++) {
      if ($string[$i] != null)
        $res_map[$keys[$i]] = ($string[$i]);
      else
        $res_map[$keys[$i]] = "*";
    }
    return $res_map;
  }

  function substitution_encrypt($to_encrypt, $alphabet) {
    $to_encrypt = str_split($to_encrypt);
    $res = str_repeat(" ", sizeOf($to_encrypt));
    for ($i=0; $i<sizeOf($to_encrypt); $i++) {
      if ($to_encrypt[$i] != ' ' && $to_encrypt[$i] != null && array_key_exists($to_encrypt[$i], $alphabet))
        $res[$i] = $alphabet[$to_encrypt[$i]];
    }
    return $res;
  }
  
  function substitution_decrypt($to_decrypt, $alphabet) {
    $to_decrypt = str_split($to_decrypt);
    $res = str_repeat(" ", sizeOf($to_decrypt));
    for ($i=0; $i<sizeOf($to_decrypt); $i++) {
      if ($to_decrypt[$i] != ' ' && $to_decrypt[$i] != null && $alphabet[$to_decrypt[$i]] != null && $alphabet[$to_decrypt[$i]] != ' ') {
        
        $res[$i] = array_search($to_decrypt[$i], $alphabet);
      }
    }
    return $res;
  }
  
  
  function double_transposition_encrypt($string, $row_perm, $col_perm) {  
    $row_perm = str_replace('(', '', $row_perm);
    $row_perm = str_replace(')', '', $row_perm);
    $col_perm = str_replace('(', '', $col_perm);
    $col_perm = str_replace(')', '', $col_perm);
    $row_perm = str_replace(' ', '', $row_perm);
    $row_perm = explode(',', $row_perm);
    $num_rows = sizeof($row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $col_perm = explode(',', $col_perm);
    $num_cols = sizeof($col_perm);
    $string = str_replace(' ', '', $string);
    
    $matrix = array();
    $to_encrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if($to_encrypt_index < strlen($string)) {
          $matrix[$i][$j] = $string[$to_encrypt_index];
        } else {
          $matrix[$i][$j] = '_';
        }
        $to_encrypt_index++;
      }
    }
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if (isset($matrix[$row_perm[$i]][$j]))
          $temp_matrix[$i][$j] = $matrix[$row_perm[$i]][$j];
      }
    }
    
    $res_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if (isset($temp_matrix[$i][$col_perm[$j]]))
          $res_matrix[$i][$j] = $temp_matrix[$i][$col_perm[$j]];
      }
    }
    
    $res = '';
    $res_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if (isset($res_matrix[$i][$j]) && $res_matrix[$i][$j] != ' ' && $res_matrix[$i][$j] != null)
          $res[$res_index] = $res_matrix[$i][$j];
        $res_index++;
      }
    }
    return $res;
  }
  
  function double_transposition_decrypt($string, $row_perm, $col_perm) {  
    $row_perm = str_replace('(', '', $row_perm);
    $row_perm = str_replace(')', '', $row_perm);
    $col_perm = str_replace('(', '', $col_perm);
    $col_perm = str_replace(')', '', $col_perm);
    $row_perm = str_replace(' ', '', $row_perm);
    $row_perm = explode(',', $row_perm);
    $num_rows = sizeof($row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $col_perm = explode(',', $col_perm);
    $num_cols = sizeof($col_perm);
    $string = str_replace(' ', '', $string);
    
    $matrix = array();
    $to_encrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if($to_encrypt_index < strlen($string)) {
          $matrix[$i][$j] = $string[$to_encrypt_index];
        } else {
          $matrix[$i][$j] = '_';
        }
        $to_encrypt_index++;
      }
    }
    
    $col_assoc = array();
    $col_perm_index = 0;
    $temp_matrix = array();
    for ($j=0; $j<$num_cols; $j++) {
      $col_assoc[$col_perm[$col_perm_index]] = array();
      for ($i=0; $i<$num_rows; $i++) {
        array_push($col_assoc[$col_perm[$col_perm_index]], $matrix[$i][$j]);
      }
      $col_perm_index++;
    }
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $temp_matrix[$i][$j] = $col_assoc[$j][$i];
      }
    }
    
    $row_assoc = array();
    $row_perm_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      $row_assoc[$row_perm[$row_perm_index]] = array();
      for ($j=0; $j<$num_cols; $j++) {
        array_push($row_assoc[$row_perm[$i]], $temp_matrix[$i][$j]);
      }
      $row_perm_index++;
    }
    
    $res_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $res_matrix[$i][$j] = $row_assoc[$i][$j];
      }
    }
    
    $res = '';
    $res_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if (isset($res_matrix[$i][$j]) && $res_matrix[$i][$j] != ' ' && $res_matrix[$i][$j] != null)
          $res[$res_index] = $res_matrix[$i][$j];
        $res_index++;
      }
    }
    return $res;
  }
  
  /*
  function double_transposition_encrypt($to_encrypt,$row_perm, $col_perm) {  
    $row_perm = str_replace('(', '', $row_perm);
    $row_perm = str_replace(')', '', $row_perm);
    $col_perm = str_replace('(', '', $col_perm);
    $col_perm = str_replace(')', '', $col_perm);
    $row_perm = str_replace(' ', '', $row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $row_perm = explode(',', $row_perm);
    $num_rows = sizeof($row_perm);
    $col_perm = explode(',', $col_perm);
    $num_cols = sizeof($col_perm);
    
    $matrix = array();
    $to_encrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $matrix[$i][$j] = $to_encrypt[$to_encrypt_index];
        $to_encrypt_index++;
      }
    }
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      $temp_matrix[$i] = $matrix[$row_perm[$i]];
    }
    
    $res = '';
    $res_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if ($temp_matrix[$i][$j] != ' ' && $temp_matrix[$i][$j] != null)
          $res[$res_index] = $temp_matrix[$i][$j];
        else 
          $res[$res_index] = ' ';
        $res_index++;
      }
    }
    return $res;
  }
  
  function double_transposition_decrypt($to_decrypt, $row_perm, $col_perm) {
    $row_perm = str_replace('(', '', $row_perm);
    $row_perm = str_replace(')', '', $row_perm);
    $col_perm = str_replace('(', '', $col_perm);
    $col_perm = str_replace(')', '', $col_perm);
    $row_perm = str_replace(' ', '', $row_perm);
    $col_perm = str_replace(' ', '', $col_perm);
    $row_perm = explode(',', $row_perm);
    $num_rows = sizeof($row_perm);
    $col_perm = explode(',', $col_perm);
    $num_cols = sizeof($col_perm);
    
    $matrix = array();
    $to_decrypt_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $matrix[$i][$j] = $to_decrypt[$to_decrypt_index];
        $to_decrypt_index++;
      }
    }
    
    $temp_matrix = array();
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        $temp_matrix[$i][$j] = $matrix[$i][$col_perm[$j]];
      }
    }
    
    $res = '';
    $res_index = 0;
    for ($i=0; $i<$num_rows; $i++) {
      for ($j=0; $j<$num_cols; $j++) {
        if ($temp_matrix[$i][$j] != ' ' && $temp_matrix[$i][$j] != null)
          $res[$res_index] = $temp_matrix[$i][$j];
        else 
          $res[$res_index] = ' ';
        $res_index++;
      }
    }
    return $res;
  }
  */
  
  function RC4($string, $key) {
    $s = array();
    $t = array();
    for ($i=0; $i<256; $i++) {
      $s[$i] = $i;
      $t[$i] = ord($key[$i % strlen($key)]);
    }
    $temp = 0;
    for ($i=0; $i<256; $i++) {
      $temp = ($temp + $s[$i] + $t[$i]) % 256;
      $swap_temp = $s[$i];
      $s[$i] = $s[$temp];
      $s[$temp] = $swap_temp;
    }
    $i = 0;
    $j = 0;
    $res = '';
    for ($k=0; $k<strlen($string); $k++) {
      $i = ($i+1) % 256;
      $j = ($j+1) % 256;
      $swap_temp = $s[$i];
      $s[$i] = $s[$j];
      $s[$j] = $swap_temp;
      $t = ($s[$i] + $s[$k]) % 256;
      $val = $s[$t];
      $res .= $string[$k] ^ chr($val);
    }
    return $res;
  }
  
?>