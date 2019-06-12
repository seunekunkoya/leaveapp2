<?php

/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      leaverec.php
For every appno entrying this file, the transactionid increases by 1.
*/

include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;


extract($_POST);

// $time = strtotime('10/16/2003');

// $newformat = date('Y-m-d',$time);

// echo $newformat;
// // 2003-10-16

$edate = date('Y-m-d', strtotime($edate));
$sdate = date('Y-m-d', strtotime($sdate));
//get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');

$comment = '';//to allow it to be passed as argument

try {

	if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment, $status, $sdate, $edate, $remarks))
	{             
          if($lvobj->updateLeaveApplication($status, $stage, $appno))
          {
              $message = "Remarks Submitted";
              echo $message;
              //$lvobj->sendMail($to, $header, $subject, $message);
          }

 	}//end of if
		else 
		{
		  $error="Not Inserted,Some Problem occur.";
		  // print_r($stmtu->errorInfo());
		  //echo json_encode($error);
		  echo $error;
		}//end of else statement
 }//end of try
 catch(PDOException $e){
   	 echo "Error: " . $e->getMessage();
 }//end of catch

?>
