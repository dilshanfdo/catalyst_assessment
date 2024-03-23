<?php
require_once "db_config.php";

$short_options = "u::p::h::";
$long_options = ["file::", "db::", "create_table", "dry_run", "help"];
$options = getopt($short_options, $long_options);

$isOK = true;
$username = "";
$password = "";
$host = "";
$file = "";
$db = "catalyst";
$table = "users";
$indexName = "user_index";
$indexFields = "email";
$dbConnection; 
$tableStructure = "(
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  surname VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE
)";

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
  printf("\n no command       | -u -p -h --db --file    | run the script and insert into the database ");
  
}

function executeStepsToCreateTable(){
  printf("\n Connecting to the database server...");
  $GLOBALS['dbConnection'] = createDatabaseConnection($GLOBALS['username'], $GLOBALS['password'], $GLOBALS['host'], $GLOBALS['db']);
  if($GLOBALS['dbConnection']){
    $tableCreated = createTable($GLOBALS['dbConnection'], $GLOBALS['table'], $GLOBALS['tableStructure']);
    if($tableCreated){
      return true;
      // $indexCreated = createIndex($GLOBALS['dbConnection'], $GLOBALS['table'], $GLOBALS['indexName'], $GLOBALS['indexFields']);
      // if($indexCreated){
      //   return true;
      // }
    }
  }
}

function validateData($data){
  if(filter_var($data[2], FILTER_VALIDATE_EMAIL)){
    $atPos = mb_strpos($data[2], '@');
    $domain = mb_substr($data[2], $atPos + 1);
    if (checkdnsrr($domain, 'MX')) {
      $name = ucfirst(strtolower($data[0]));
      $surname = ucfirst(strtolower($data[1]));
      $email = strtolower($data[2]);
      return array($name, $surname, $email);     
    }    
  }  
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
  
  if($GLOBALS['isOK']) {
    if(array_key_exists("create_table", $options) && array_key_exists("dry_run", $options)){
      exit("\n ERROR - Can not contain CLIarguments --create_table and --dry_run at the same time. Either of them can be executed. Type --help for more information");
    }
    elseif(array_key_exists("create_table", $options)){
      printf("\n Executing create_table");
      printf("\n ----------------------");
      echo "\n";

      if(executeStepsToCreateTable()){
        
      }
    }  
    elseif(array_key_exists("dry_run", $options)){
      printf("\n Executing dry_run");
      printf("\n -----------------");
      echo "\n";

      if(executeStepsToCreateTable()){
        $file = fopen($GLOBALS['file'], 'r');
        $count = 0;
        while (($data = fgetcsv($file)) !== FALSE) { 
          $count++ ;
          if ($count == 1) { continue; }
          $validatedData = validateData($data);
          if($validatedData) {
            printf("\n $validatedData[0]    $validatedData[1]     $validatedData[2]");
          }
          else {
            printf("\n Error");
          }
        }
      }
    }
    else {
      printf("\n Executing full script");
      printf("\n ---------------------");
      echo "\n";

      if(executeStepsToCreateTable()){
        $file = fopen($GLOBALS['file'], 'r');
        $count = 0;
        while (($data = fgetcsv($file)) !== FALSE) { 
          $count++ ;
          if ($count == 1) { continue; }
          $validatedData = validateData($data);
          if($validatedData) {
            insertDataIntoTable($GLOBALS['dbConnection'], $GLOBALS['table'], $validatedData);
          }
          else {
            $stdout = fopen('php://stdout', 'w');
            printf("\n $data[0] $data[1]  $data[2] record could not insert. $data[2] is not a valid email");
          }
        }
      }
    }    
  }
  else { 
    exit("ERROR - CLIarguments username(-u), password(-p), host(-h) and file(--file) required. Type --help for more information");
  }
}

?>