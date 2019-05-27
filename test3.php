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
$level = $_SESSION['staffdetails']['level'];

$formerror = array();

extract($_POST);

  //$result = array();//json goes in here


  if(empty($leavetype)) {
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

  if (count($formerror) == 0) {

        $datecreated = date('Y-m-d H:i:s');
        // $timeviewed = date('Y-m-d H:i:s');
        $appno = serAppno();
        //$appno = appNo(9);
        $leavestatus = "Submitted";
        $leavestageid = 1;
        $transactionid = 1;
        $role = "Applicant";//role of the staff as at the point of leave application
        $session = '2018/2019';

        $edate = date('Y-m-d', strtotime($edate));
        $sdate = date('Y-m-d', strtotime($sdate));

    if ($leavetype == 'casual') {

            $leavedaysgone = (int)casualleavedaysgone($staffid, $session);
            $leaveallowed = 7;//total number of days allowed for any staff
            $ndaysapplied = numdays($sdate, $edate);

            $dayspermissible = $leaveallowed - $leavedaysgone;

            if($ndaysapplied <= $dayspermissible)
            {
                $stmt = $con->prepare("INSERT INTO leaveapplication(staffid, appno, leavetype, reason, startdate, enddate, session, location, phone, officer1,officer2, officer3, leavestatus, datecreated) 
                                VALUES(:staffid, :appno, :leavetype, :reason, :startdate, :enddate, :session, :location, :phone, :officer1, :officer2, :officer3, :leavestatus, :datecreated )");

                    $stmt->bindparam(':staffid', $staffid);
                    $stmt->bindparam(':appno', $appno);
                    $stmt->bindparam(':leavetype', $leavetype);
                    $stmt->bindparam(':reason', $reason);
                    $stmt->bindparam(':startdate', $sdate);
                    $stmt->bindparam(':enddate', $edate);
                    $stmt->bindparam(':session', $session);
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
                        $stmt1->bindparam(':timeviewed', $datecreated);
                        $stmt1->bindparam(':comment', $reason);
                        $stmt1->bindparam(':leavestatus', $leavestatus);
                        $stmt1->bindparam(':startdate', $sdate);
                        $stmt1->bindparam(':enddate', $edate);

                        if ($stmt1->execute())
                        {
                            //$result['success'] = 'Data Inserted';
                            echo "CASUAL SUCCESS";
                        }
                        else 
                        {
                            //$result['failed'] = 'Please try again';
                          echo "CASUAL FAIL";
                        }//end of else
                      }
                      else
                      {
                        //$result['derror'] = 'Database Error';
                        echo "DBASE ERROR";
                      }//end of if statement executes


            }
            else
            {
              //$result['na'] = 'Leave Days Beyond Permissible';
              echo "BEYOND LIMIT";
            }

      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
    }//end of casual
    else if($leavetype == 'annual'){
        //$result['annual'] = 'You chose annual';


            $leavedaysgone = (int)annualleavedaysgone($staffid, $session);
            $leaveallowed = leavedays($level);//total number of days allowed for any staff
            $ndaysapplied = numdays($sdate, $edate);

            $dayspermissible = $leaveallowed - $leavedaysgone;

            if($ndaysapplied <= $dayspermissible)
            {
                $stmt = $con->prepare("INSERT INTO leaveapplication(staffid, appno, leavetype, reason, startdate, enddate, session, location, phone, officer1,officer2, officer3, leavestatus, datecreated) 
                                VALUES(:staffid, :appno, :leavetype, :reason, :startdate, :enddate, :session, :location, :phone, :officer1, :officer2, :officer3, :leavestatus, :datecreated )");

                    $stmt->bindparam(':staffid', $staffid);
                    $stmt->bindparam(':appno', $appno);
                    $stmt->bindparam(':leavetype', $leavetype);
                    $stmt->bindparam(':reason', $reason);
                    $stmt->bindparam(':startdate', $sdate);
                    $stmt->bindparam(':enddate', $edate);
                    $stmt->bindparam(':session', $session);
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
                        $stmt1->bindparam(':timeviewed', $datecreated);
                        $stmt1->bindparam(':comment', $reason);
                        $stmt1->bindparam(':leavestatus', $leavestatus);
                        $stmt1->bindparam(':startdate', $sdate);
                        $stmt1->bindparam(':enddate', $edate);

                        if ($stmt1->execute())
                        {
                            //$result['success'] = 'Data Inserted';
                            echo "ANNUAL SUCCESS";
                        }
                        else 
                        {
                            //$result['failed'] = 'Please try again';
                          echo "ANNUAL FAIL";
                        }//end of else
                      }
                      else
                      {
                        //$result['derror'] = 'Database Error';
                        echo "DBASE ERROR";
                      }//end of if statement executes


            }
            else
            {
              //$result['na'] = 'Leave Days Beyond Permissible';
              echo "BEYOND LIMIT";
            }

      //echo "ANNUAL";
    }//end of annual

    else if($leavetype == 'maternity'){
        //$result['maternity'] = 'You chose maternity';
      echo "MATERNITY";
    }//end of annual
  }
  else
  {
    foreach ($formerror as $error) 
    {
      echo $error;
    }
  }//end of form error

//echo json_encode($result);

?>