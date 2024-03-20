<?php
$short_options = "u::p::h::";
$long_options = ["file::", "create_table", "dry_run", "help"];
$options = getopt($short_options, $long_options);
var_dump($options);

$isOK = true;

if(array_key_exists("u", $options) && $options["u"] !== false ){
  $username = $options["u"];
} 
else{
  $isOK = false;
}

// Assume password can be empty
if(array_key_exists("p", $options)){
  if($options["p"] !== false){
    $password = $options["p"];
  }
  else {
    $password = "";
  }  
} 
else {
  $isOK = false;
}

if(array_key_exists("h", $options) && $options["h"] !== false ){
  $username = $options["h"];
} 
else {
  $isOK = false;
}

if(array_key_exists("file", $options) && $options["file"] !== false ){
  $username = $options["file"];
}
else {
  $isOK = false;
} 

if(!$isOK) {
  exit("ERROR - CLIarguments username(-u), password(-p), host(-h) and file(--file) required. Type --help for more information");
}

echo "boom";


?>