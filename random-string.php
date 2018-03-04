<?php
function random_string(){
  $len = 7;
  $chars = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
  $charlen = strlen($chars);
  $randstr = '';
  for($i = 0; $i < $len; $i++){
    $randstr.=$chars[rand(0, ($charlen - 1))];
  }
  return $randstr;
}
?>
