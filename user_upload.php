<?php
require_once "db_config.php";

$short_options = "u::p::h::";
$long_options = ["file::", "db::", "create_table", "dry_run", "help"];
$options = getopt($short_options, $long_options);
var_dump($options);

$isOK = true;
$username = "";
$password = "";
$host = "";
$file = "";
$db = "catalyst";

function showHelp(){
  printf("\n           *************  HELP  ****************");
  printf("\n \n Properties");
  printf("\n ==========");
  printf("\n");
  printf("\n      arg         |     Key     |     value   |               Description");
  printf("\n ------------------------------------------------------------------------------------------------------");
  printf("\n -u               |  Required   |  Required   |   username - argument key(-u) and value should be provided");
  printf("\n                                                      -uroot");
  printf("\n -p               |  Required   |  Optional   |   password - argument key(-p) should be provided but value is optional");
  printf("\n                                                      -proot or -p");
  printf("\n -h               |  Required   |  Optional   |   hostname - argument key(-h) and value should be provided");
  printf("\n                                                      -hlocalhost");
  printf("\n --db             |  Optional   |  Optional   |   databasename - argument key(--dv) and value are optional ");
  printf("\n                                                      --db=catalyst or --db or not mention it");
  printf("\n --file           |  Required   |  Optional   | filename - argument key(--file) and value should be provided ");
  printf("\n                                                      --file=users.csv");
  printf("\n \n Commands");
  printf("\n ========");
  printf("\n");
  printf("\n      arg         |   Required Properties   |   Description");
  printf("\n --------------------------------------------------------------------");
  printf("\n --create_table   | -u -p -h --db --file    | create the table ");
  printf("\n                                               --create_table");
  printf("\n --dry_run        | -u -p -h --db --file    | run the script but not insert into the database ");
  printf("\n                                               --dry_run");
}

if(array_key_exists("help", $options)){
  showHelp();
}
else {
  //  check for username
  if(array_key_exists("u", $options) && $options["u"] !== false ){
    $GLOBALS['username'] = $options["u"];
  } 
  else{
    $GLOBALS['isOK'] = false;
  }
  
  // check for password and assume password can be empty
  if(array_key_exists("p", $options)){
    if($options["p"] !== false){
      $GLOBALS['password'] = $options["p"];
    }
  } 
  else {
    $GLOBALS['isOK'] = false;
  }
  
  //  check for host
  if(array_key_exists("h", $options) && $options["h"] !== false ){
    $GLOBALS['host'] = $options["h"];
  } 
  else {
    $GLOBALS['isOK'] = false;
  }
  
  // check for file
  if(array_key_exists("file", $options) && $options["file"] !== false ){
    $GLOBALS['file'] = $options["file"];
  }
  else {
    $GLOBALS['isOK'] = false;
  }
  
  // check whether the user provide a database. if not assign default db as "catalyst"
  if(array_key_exists("db", $options) && $options["db"] !== false){
    $GLOBALS['db'] = $options["db"];
  } 
  
  if(!$GLOBALS['isOK']) {
    exit("ERROR - CLIarguments username(-u), password(-p), host(-h) and file(--file) required. Type --help for more information");
  }
  else {
    try {
      $connection = new mysqli($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password']);
      printf("\n Successfully connected to the database server.");
      if(isDatabaseExist($connection, $GLOBALS['db'])){
        printf("\n Database '" .$GLOBALS['db']. "' exist");
      }
      else {
        printf("\n Database not exist");
        createDatabase($connection, $GLOBALS['db']);
      }
      $connection->close();
    } catch (Exception $e) {
      printf("\n Connection failed: " .$e->getMessage());
    }    
  }

  //  check both create_table and dry_run args are exist
  if(array_key_exists("create_table", $options) && array_key_exists("dry_run", $options)){
    exit("\n ERROR - Can not contain CLIarguments --create_table and --dry_run at the same time. Either of them can be executed. Type --help for more information");
  }
  elseif(array_key_exists("create_table", $options)){
    echo ("\n create table");
    $dbConnection = createDatabaseConnection($GLOBALS['username'], $GLOBALS['password'], $GLOBALS['host'], $GLOBALS['db']);
  }  
  elseif(array_key_exists("dry_run", $options)){
    echo ("\n dry run");
  }
  else {
    echo ("\n insert data into db");
  }
    
  // echo "boom";
}
?>