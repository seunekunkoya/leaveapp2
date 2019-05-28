<?php 
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Insert data into transaction table
File:      leavetrack.php
For every new application leave status is given an automatic status of pending recommendation
*/

//include('config/database.php');
//include('leavefunction.php');

include "include/config.php";

$staffid = $_SESSION['staffid'];

$lvobj->staffInfo($staffid);
$lvobj->checkSession();

$staffid = $_SESSION['staffinfo']['staffid'];
$level = $_SESSION['staffinfo']['level'];

$formerror = array();

extract($_POST);


  if(empty($leavetype)) {
    $formerror['leavetype'] = "Leavetype is blank";
  } else {
    $leavetype = $lvobj->test_input($leavetype);
  }

  if(empty($reason)) {
    $formerror['reason'] = "Reason for leave is blank";
  } else {
    $reason = $lvobj->test_input($reason);
  }

  if(empty($sdate)) {
    $formerror['sdate'] = "You have not entered a leave start date";
  } else {
    $sdate = $lvobj->test_input($sdate);
  }

  if(empty($edate)) {
    $formerror['edate'] = "Leave end date is blank";
  } else {
    $edate = $lvobj->test_input($edate);
  }

  if(empty($location)) {
    $formerror['location'] = "Destination Address is empty";
  } else {
    $location = $lvobj->test_input($location);
  }

  if(empty($phone)) {
    $formerror['phone'] = "Phone number is blank";
  } else {
    $phone = $lvobj->test_input($phone);
  }

  if(empty($officer1)) {
    $formerror['officer1'] = "Officer 1 is not selected";
  } else {
    $officer1 = $lvobj->test_input($officer1);
  }

  if(empty($officer2)) {
    $formerror['officer2'] = "Officer 2 is not selected";
  } else {
    $officer2 = $lvobj->test_input($officer2);
  }

  if(empty($officer3)) {
    $formerror['officer3'] = "Officer 3 is not selected";
  } else {
    $officer3 = $lvobj->test_input($officer3);
  }

  if (count($formerror) == 0) {

        $datecreated = date('Y-m-d H:i:s');
        // $$lvobj->timeviewed = date('Y-m-d H:i:s');
        $appno = $lvobj->serAppno();
        //$appno = appNo(9);
        $leavestatus = "Submitted";
        $leavestageid = 1;
        $transactionid = 1;
        $role = "Applicant";//role of the staff as at the point of leave application
        //$session = '2018/2019';
        $session = $lvobj->getSession();
        
        $remarks = '';//so as to be able to use it as an argument
        $edate = date('Y-m-d', strtotime($edate));
        $sdate = date('Y-m-d', strtotime($sdate));
        $numdays = $lvobj->numdays($sdate, $edate);

        if($lvobj->insertLAT($staffid, $appno, $leavetype, $reason, $sdate, $edate, $numdays, $session, $location, $phone, $officer1, $officer2, $officer3, $leavestatus, $leavestageid, $datecreated))
        {                         
          if ($lvobj->insertLT($appno, $staffid, $role, $transactionid, $datecreated, $reason, $leavestatus, $sdate, $edate, $remarks))
          {
            //$result['success'] = 'Data Inserted';
            echo "SUCCESS";
            //$lvobj->sendMail($to, $header, $subject, $message);
          }
          else 
          {
            //$result['failed'] = 'Please try again';
            echo "FAIL";
          }//end of else
        }
        else
        {
          //$result['derror'] = 'Database Error';
          echo "DBASE ERROR";
        }//end of if statement executes
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  }//end of form not having error
  else
  {
    foreach ($formerror as $error) 
    {
      echo $error;
    }
  }//end of form error

//echo json_encode($result);

?>