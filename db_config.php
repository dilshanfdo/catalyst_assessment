<?php

  function isDatabaseExist($connection, $dbName){
    $result = $connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" .$dbName. "'");
    if(mysqli_num_rows($result) !== 0){
      return true;
    }
    return false;
  }

  function createDatabase($connection, $dbName) {
    try{
      $connection->query("CREATE DATABASE ".$dbName);
      printf("\n Database created successfully");
    }
    catch(Exception $e) {
      printf("\n Could not create database: " .$e->getMessage());
    }    
  }

  function createDatabaseConnection($dbUser, $dbPassword, $dbHost, $dbName){
    try {
      $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
      printf("\n Successfully connected to the '" .$dbName. "' database");
      return $dbConnection;
    } catch (Exception $e) {
      printf("\n Database connection failed: ", $e->getMessage());
    }
  }

?>