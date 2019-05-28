<?php
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      releasestaff.php

*/
include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

extract($_POST);

if($status == 'Released'){

//get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
$dateofrelease = date('Y-m-d H:i:s');

$edate = date('Y-m-d', strtotime($edate));
$sdate = date('Y-m-d', strtotime($sdate));
 
//format date to this format 00-Mon-0000
$dbegin = date_format(date_create($sdate), "d-M-Y"); //date began
$dend = date_format(date_create($edate), "d-M-Y"); //date ending

 try {

    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment, $status, $sdate, $edate, $remarks))
    {
        
        if($lvobj->approvedleavesUpdateByRelease($dateofrelease, $appno));
          {    
            if($lvobj->updateLeaveApplication($reco, $stage, $appno));
            {
               $message = "Query Submitted";
               echo $message;
            }
          }//end of if stmt1

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

}//end of if Released

else if ($reco == 'Not Yet Released') {
  
  //get transaction id for the current application stream 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;
    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
 
//format date to this format 00-Mon-0000
$dbegin = date_format(date_create($sdate), "d-M-Y"); //date began
$dend = date_format(date_create($edate), "d-M-Y"); //date ending

 try {

    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment = null, $status, $recstartdate, $recenddate, $remarks = null))
    {
     if($lvobj->updateLeaveApplication($status, $stage, $appno));
      {
        $message = "Query Submitted";
        echo $message;
      }
    }//end of if stmtu
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

}//end of if Not yet Released

else {
  //get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
 
//need be changed
//get comment
#comment is gotten from the post variables

//get status
//format date to this format 00-Mon-0000
$dbegin = date_format(date_create($sdate), "d-M-Y"); //date began
$dend = date_format(date_create($edate), "d-M-Y"); //date ending

try {

    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment = null, $status, $recstartdate, $recenddate, $remarks = null))
    {  
      if($lvobj->updateLeaveApplication($status, $stage, $appno))
      {
        $message = "Query Submitted";
        echo $message;
      }
    }//end of if stmtu
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

}//end of else.

?>
