<?php

  function isDatabaseExist($connection, $dbName){
    try {
      $result = $connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" .$dbName. "'");
      if(mysqli_num_rows($result) !== 0){
        return true;
      }
      return false;
    } catch (Exception $e) {      
      printf("\n Connection failed: $e->getMessage()");
    }    
  }

  function createDatabase($connection, $dbName) {
    try{
      $result = $connection->query("CREATE DATABASE ".$dbName);
      if($result !== 0){
        printf("\n Database created successfully");
      }
      else {
        printf("\n Could not create database:");
      }      
    }
    catch(Exception $e) {
      printf("\n Could not create database: .$e->getMessage()");
    }    
  }

  function createDatabaseConnection($dbUserName, $dbPassword, $dbHost, $dbName){
    try {
      $connection = new mysqli($dbHost, $dbUserName, $dbPassword);    
      printf("\n Successfully connected to the database server.");
      // printf("\n Connecting to the '$db' database...");

      printf("\n Checking '$dbName' database exist...");

      //  Check database exist
      if(isDatabaseExist($connection, $dbName)){
        printf("\n Database exist");
      }
      else {
        printf("\n Database not exist");
        printf("\n Creating '$dbName' database...");

        //  Create database
        createDatabase($connection, $dbName);
      }

      closeDatabaseConnection($connection);

      // Database connection        
      try {
        printf("\n Connecting to the '$dbName' database...");
        $dbConnection = new mysqli($dbHost, $dbUserName, $dbPassword, $dbName);
        printf("\n Successfully connected to the database");
        return $dbConnection;
      } catch (Exception $e) {
        printf("\n Database connection failed: $e->getMessage()");
      }
    } catch (Exception $e) {
      printf("\n Database Server connection failed: " .$e->getMessage());
    }
  }

  function isTableExist($dbConnection, $tableName){
    printf("\n Checking '$tableName' table exist...");
    try {
      $rows_count = mysqli_num_rows($dbConnection->query("SHOW TABLES LIKE '$tableName'"  ));
      if($rows_count !== 0){
        return true;
      }
      return false;
    } catch (Exception $e) {
      printf("\n Connection failed: " .$e->getMessage());
    }

  }

  function createTable($dbConnection, $tableName, $tableStructure){    
    //  Check table exist
    if(isTableExist($dbConnection, $tableName)){
      printf("\n Table exist");
      return true;
    }
    else {
      printf("\n Table not exist");

      //  Create table 
      try {
        printf("\n Creating '$tableName' table...");
        $result = $dbConnection->query("CREATE TABLE " .$tableName. " " .$tableStructure);
        if ($result !== 0) { 
          printf("\n Table created successfully");         
          return true;
        } else {
          printf("\n Could not create table");
          return false;
        }
      } catch (Exception $e) {
        printf("\n Could not create table: " .$e->getMessage());
      }
    }    
  }

  function isIndexExist($dbConnection, $tableName, $indexName){
    printf("\n Checking '$tableName' table has any index...");
    try {
      $result = $dbConnection->query("SHOW INDEX FROM " .$tableName. " WHERE key_name = 'user_index'");
      if($result !== 0){
        echo "\n INSIDE IF";
        return true;
      }
      echo "\n INSIDE IF";
      return false;
    } catch (Exception $e) {
      printf("\n Connection failed: " .$e->getMessage());
    }
  }

  function createIndex($dbConnection, $tableName, $indexName, $indexFields) {
    printf("\n Creating index '$indexName' to '$tableName' table...");
    try {
      $result = $dbConnection->query("ALTER TABLE " .$tableName. " ADD INDEX " .$indexName. " (" .$indexFields. ")");
      if($result !== 0){
        print ("\n Index created successfully");
        return true;
      } 
      else {
        printf("\n Could not create index");
        return false;
      }  
    } catch (Exception $e) {
      printf("\n Could not create index: " .$e->getMessage());
    }

    // if(isIndexExist($dbConnection, $tableName, $indexName)){
    //   printf("\n Index exist");
    //   return true;
    // }
    // else {
    //   printf("\n Index not exist");    
    //   printf("\n Creating index '$indexName' to '$tableName' table...");
    //   try {
    //     $result = $dbConnection->query("ALTER TABLE " .$tableName. " ADD INDEX " .$indexName. " (" .$indexFields. ")");
    //     if($result !== 0){
    //       print ("\n Index created successfully");
    //       return true;
    //     } 
    //     else {
    //       printf("\n Could not create index");
    //       return false;
    //     }  
    //   } catch (Exception $e) {
    //     printf("\n Could not create index: " .$e->getMessage());
    //   }
    // }
  }

  function insertDataIntoTable($dbConnection, $tableName, $data){
    foreach($data as $key => $value){
      if(str_contains($value, "'")){
        $data[$key] = str_replace("'", "''", $value);
      }
    }
    try {
      $result = $dbConnection->query("SELECT * FROM $tableName WHERE email = '$data[2]'");
      if($result->num_rows == 0){
        try {
          $dbConnection->query("INSERT INTO $tableName (name, surname, email) VALUES ('" .$data[0]. "', '" .$data[1]. "', '" .$data[2]. "')");
        } catch (EXception $e) {
          printf("\n Could not insert data: " .$e->getMessage());
        }
      }
      else {
        printf("\n $data[0]   $data[1]    $data[2] record could not insert. Email already exist");
      }
    } catch (Exception $e) {
      printf("\n Connection failed: " .$e->getMessage());
    }
  }

  function closeDatabaseConnection($dbConnection){
    $dbConnection->close();
  }
?>