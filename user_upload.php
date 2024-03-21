<?php
require_once "test.php";

$short_options = "u::p::h::";
$long_options = ["file::", "db::", "create_table", "dry_run", "help"];
$options = getopt($short_options, $long_options);
var_dump($options);

$isOK = true;

function showHelp(){
  printf("\n           *************  HELP  ****************");
  printf("\n      arg     |    Required/Optional    |             Description");
  printf("\n ------------------------------------------------------------------------------------------------------");
  printf("\n -u           |       Required          | username - argument key(-u) and value should be provided");
  printf("\n                                                      -uroot");
  printf("\n -p           |        Optional         | password - argument key(-p) should be provided but value is optional");
  printf("\n                                                      -proot or -p");
  printf("\n -h           |        Required         | hostname - argument key(-h) and value should be provided");
  printf("\n                                                      -hlocalhost");
  printf("\n --file       |       Required          | filename - argument key(--file) and value should be provided ");
  printf("\n                                                      --file=users.csv");
}

if(array_key_exists("help", $options)){
  showHelp();
}
else {
  //  check for username
  if(array_key_exists("u", $options) && $options["u"] !== false ){
    $username = $options["u"];
  } 
  else{
    $isOK = false;
  }
  
  // check for password and assume password can be empty
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
  
  //  check for host
  if(array_key_exists("h", $options) && $options["h"] !== false ){
    $host = $options["h"];
  } 
  else {
    $isOK = false;
  }
  
  // check for file
  if(array_key_exists("file", $options) && $options["file"] !== false ){
    $file = $options["file"];
  }
  else {
    $isOK = false;
  }
  
  // check whether the user provide a database. if not assign default db as catalyst
  if(array_key_exists("db", $options)){
    if($options["db"] !== false){
      $db = $options["db"];
    }
    else {
      $db = "catalyst";
    }  
  } 
  
  if(!$isOK) {
    exit("ERROR - CLIarguments username(-u), password(-p), host(-h) and file(--file) required. Type --help for more information");
  }
  else {
    try {
      $connection = new mysqli($host, $username, $password);
      printf("\n Connected successfully");
    } catch (Exception $e) {
      printf("\n Connection failed: " .$e->getMessage());
    }
     
    
  }

  if(array_key_exists("create_table", $options)){

  }
  elseif(array_key_exists("dry_run", $options)){

  }
  else {

  }
    
  // echo "boom";
}
?>