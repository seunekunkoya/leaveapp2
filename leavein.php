<?php

/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Insert data into transaction table
File:      leavetrack.php
For every new application leave status is given an automatic status of pending recommendation
*/

include('config/database.php');
include('leavefunction.php');

checkSession();
$staffid = $_SESSION['staffdetails']['staffid'];

$formerror = array();

extract($_POST);

if (empty($leavetype)) {
    $formerror['leavetype'] = "Leavetype is blank";
  } else {
    $leavetype = test_input($leavetype);
  }

  if(empty($reason)) {
    $formerror['reason'] = "Reason for leave is blank";
  } else {
    $reason = test_input($reason);
  }

  if(empty($sdate)) {
    $formerror['sdate'] = "You have not entered a leave start date";
  } else {
    $sdate = test_input($sdate);
  }

  if(empty($edate)) {
    $formerror['edate'] = "Leave end date is blank";
  } else {
    $edate = test_input($edate);
  }

  if(empty($location)) {
    $formerror['location'] = "Destination Address is empty";
  } else {
    $location = test_input($location);
  }

  if(empty($phone)) {
    $formerror['phone'] = "Phone number is blank";
  } else {
    $phone = test_input($phone);
  }

  if(empty($officer1)) {
    $formerror['officer1'] = "Officer 1 is not selected";
  } else {
    $officer1 = test_input($officer1);
  }

  if(empty($officer2)) {
    $formerror['officer2'] = "Officer 2 is not selected";
  } else {
    $officer2 = test_input($officer2);
  }

  if(empty($officer3)) {
    $formerror['officer3'] = "Officer 3 is not selected";
  } else {
    $officer3 = test_input($officer3);
  }


$datecreated = date('Y-m-d H:i:s');
$timeviewed = date('Y-m-d H:i:s');
$appno = serAppno();
//$appno = appNo(9);
$leavestatus = "SUBMITTED";
$transactionid = 1;
//$staffid = $_SESSION['loginid'];

//$_SESSION['username'] ? $_SESSION['username'] : $_GET['id'] ;


$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(count($formerror) == 0 ) //test for errors in the form
{

$stmt = $con->prepare("INSERT INTO leaveapplication(staffid, appno, leavetype, reason, startdate, enddate, location, phone, officer1,officer2, officer3, leavestatus, datecreated) 
            VALUES(:staffid, :appno, :leavetype, :reason, :startdate, :enddate, :location, :phone, :officer1, :officer2, :officer3, :leavestatus, :datecreated )");

$stmt->bindparam(':staffid', $staffid);
$stmt->bindparam(':appno', $appno);
$stmt->bindparam(':leavetype', $leavetype);
$stmt->bindparam(':reason', $reason);
$stmt->bindparam(':startdate', $sdate);
$stmt->bindparam(':enddate', $edate);
$stmt->bindparam(':location', $location);
$stmt->bindparam(':phone', $phone);
$stmt->bindparam(':officer1', $officer1);
$stmt->bindparam(':officer2', $officer2);
$stmt->bindparam(':officer3', $officer3);
$stmt->bindparam(':leavestatus', $leavestatus);
$stmt->bindparam(':datecreated', $datecreated);


if($stmt->execute())
    {
        $query1 = "INSERT INTO leavetransaction (appno, tstaffid, transactionid, timeviewed, comment, status, recstartdate, recenddate) VALUE (:appno, :tstaffid, :transactionid, :timeviewed, :comment, :leavestatus, :startdate, :enddate)";
        $stmt1 = $con -> prepare($query1);

        $stmt1->bindparam(':appno', $appno);
        $stmt1->bindparam(':tstaffid', $staffid);
        $stmt1->bindparam(':transactionid', $transactionid);
        $stmt1->bindparam(':timeviewed', $timeviewed);
        $stmt1->bindparam(':comment', $reason);
        $stmt1->bindparam(':leavestatus', $leavestatus);
        $stmt1->bindparam(':startdate', $sdate);
        $stmt1->bindparam(':enddate', $edate);

        if ($stmt1->execute())
        {
            echo 'SUCCESS';
        }
        else 
        {
            echo 'ERROR';
        }//end of else
    }
    else
    {
      echo 'DATABASE ERROR';
    }//end of if statement executes
 /*
 }//end of try
      catch(PDOException $e){
       echo "Error: " . $e->getMessage();
      }//end of catch
      */

}//end of if form error
else 
{
  echo 'EMPTY FORM';
  /*
  foreach($formerror as $formerrors) 
  {
      echo $formerrors;
      echo "<br>";
  }*/
}//end of else to test for errors in form data
?>
