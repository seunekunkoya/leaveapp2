<?php
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      releasestaff.php
For every appno entrying this file, the transactionid increases by 1.
*/
include "include/config.php";

$lvobj->checkSession();

$result = array();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

extract($_POST);

//get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
$dateofrelease = date('Y-m-d H:i:s');

$edate = date('Y-m-d', strtotime($edate));
$sdate = date('Y-m-d', strtotime($sdate));
$toRes = date('Y-m-d', strtotime($toRes));
$startFr = date('Y-m-d', strtotime($startFr));

$comment = '';
 
 try {

    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment, $status, $sdate, $edate, $remarks))
    {
        if($lvobj->newApprovedleavesUpdateByRelease($dateofrelease, $appno, $numD, $startFr, $toRes));
          {    
            if($lvobj->updateLeaveApplication($status, $stage, $appno));
            {
              $result['insert'] = true;
            }
          }//end of if stmt1

  }//end of if
    else 
    {
      $result['error'] = true;
      
    }//end of else statement
 }//end of try
 catch(PDOException $e){
     echo "Error: " . $e->getMessage();
  }//end of catch

echo json_encode($result);

?>
