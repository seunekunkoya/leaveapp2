<?php
session_start();
set_time_limit(0);
ini_set('memory_limit', '-1'); 

	//include_once('zz.php');
	date_default_timezone_set("Africa/Lagos");
	  $host = "localhost";
	  $db_name = "leavedb";
	  $username = "root";
	  $password = "";

	  try {
	      $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
	      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	      	//echo "Connected";
	  } catch (PDOException $e) {
	    echo "Connection error: ". $e->getMessage();
	  }
	
	//import all classes
	include_once 'classes.php';
	
?>