<?php 
for($num =1; $num <= 100; $num++){
  // Can use if($num % 15 == 0) instead of below statement
  if($num % 3 == 0 && $num % 5 == 0){  
    $str = "foobar";
  } 
  elseif ($num % 5 == 0) {
    $str = "bar";
  } 
  elseif ($num % 3 == 0) {
    $str = "foo";
  } 
  else {
    $str = $num;
  }

  echo $str . ", ";
}
?>