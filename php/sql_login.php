<?php
/*
____________________________________________________________________________________
INFORMATION:
Created on:     23.11.16
Created by:     Tobias Zweifel
Last changes:   11.02.17
Last edited by: Tobias Zweifel
Function:       script contains database information and open a connection
____________________________________________________________________________________
*/

  $dbname="";
  $dbhost="localhost";
  $dbuser="";
  $dbpass="";
  $sql_connection=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  mysqli_set_charset($sql_connection, "utf8");
  $GLOBALS['sql_connection'] = $sql_connection;
  // Check connection
  if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 ?>
