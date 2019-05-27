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

$numRel = $lvobj->numdays($startFr, $toRes);
$numAppr = $lvobj->numdays($sdate, $edate);

if($numRel > $numAppr)
{
  $result['daysGreater'] = true;
  $result['numrel'] = $numRel;
  $result['numappr'] = $numAppr;
}
else 
{

if($status == 'Released'){

//get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
$dateofrelease = date('Y-m-d H:i:s');

$edate = date('Y-m-d', strtotime($edate));
$sdate = date('Y-m-d', strtotime($sdate));

$comment = '';
 
 try {

    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment, $status, $sdate, $edate, $remarks))
    {
        if($lvobj->newApprovedleavesUpdateByRelease($dateofrelease, $appno, $numD, $startFr, $toRes));
          {    
            if($lvobj->updateLeaveApplication($status, $stage, $appno));
            {
               // $message = "Query Submitted";
               // echo $message;
              $result['insert'] = true;
            }
          }//end of if stmt1

  }//end of if
    else 
    {
      $result['error'] = true;
      // $error="Not Inserted,Some Problem occur.";
      // print_r($stmtu->errorInfo());
      //echo json_encode($error);
      //echo $error;
    }//end of else statement
 }//end of try
 catch(PDOException $e){
     echo "Error: " . $e->getMessage();
  }//end of catch

 }//end of if Released

}//end of days test

echo json_encode($result);

?>
