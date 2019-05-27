<?php  
 $connect = mysqli_connect("localhost", "root", "", "testing");  
 $query = "SELECT * FROM employee";  
 $result = mysqli_query($connect, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Webslesson Tutorial | Bootstrap Modal with Dynamic MySQL Data using Ajax & PHP</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:700px;">  
                <h3 align="center">Bootstrap Modal with Dynamic MySQL Data using Ajax & PHP</h3>  
                <br />  
                <div class="table-responsive">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th width="70%">Employee Name</th>  
                               <th width="30%">View</th>  
                          </tr>  
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                          ?>  
                          <tr>  
                               <td><?php echo $row["name"]; ?></td>  
                               <td><input type="button" name="view" value="view" id="<?php echo $row["id"]; ?>" class="btn btn-info btn-xs view_data" /></td>  
                          </tr>  
                          <?php  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <div id="dataModal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title">Employee Details</h4>  
                </div>  
                <div class="modal-body" id="employee_detail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>  
 <script>  
 $(document).ready(function(){  
      $('.view_data').click(function(){  
           var employee_id = $(this).attr("id");  
           $.ajax({  
                url:"select.php",  
                method:"post",  
                data:{employee_id:employee_id},  
                success:function(data){  
                     $('#employee_detail').html(data);  
                     $('#dataModal').modal("show");  
                }  
           });  
      });  
 });  
 </script>

 <?php  
 if(isset($_POST["employee_id"]))  
 {  
      $output = '';  
      $connect = mysqli_connect("localhost", "root", "", "testing");  
      $query = "SELECT * FROM employee WHERE id = '".$_POST["employee_id"]."'";  
      $result = mysqli_query($connect, $query);  
      $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td width="30%"><label>Name</label></td>  
                     <td width="70%">'.$row["name"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Address</label></td>  
                     <td width="70%">'.$row["address"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Gender</label></td>  
                     <td width="70%">'.$row["gender"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Designation</label></td>  
                     <td width="70%">'.$row["designation"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Age</label></td>  
                     <td width="70%">'.$row["age"].' Year</td>  
                </tr>  
                ';  
      }  
      $output .= "</table></div>";  
      echo $output;  
 }  
 ?>


    alert("All fields are required.");
    $('#apply').html("Submit");
  }
  else {
        
//AJAX code to send data to php file.
         $.ajax({
                  method: "POST",
                  url:   "test2.php",
                  data: {
                    leavetype:leavetype,
                    reason:reason,
                    sdate:sdate,
                    edate:edate,
                    location:location,
                    phone:phone,
                    officer1:officer1,
                    officer2:officer2,
                    officer3:officer3
                  }
                })
                .done(function(data){
                    // show the response
                    alert( "Sucessful Posting." );
                    $('#apply').html("Submit");
                  })
                .fail(function(data) {
                  // just in case posting your form failed
                    $('#leavedetail').html(data);  
                    $('#dataModal').modal("show");
                    $('#apply').html("Submit");
                 });  
              // to prevent refreshing the whole page page
              return false;
          }//end of if else
       });
  
});
</script>


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

$response = array();//json goes in here


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
                            //$response['success'] = 'Data Inserted';
                            echo "CASUAL SUCCESS";
                        }
                        else 
                        {
                            //$response['failed'] = 'Please try again';
                          echo "CASUAL FAIL";
                        }//end of else
                      }
                      else
                      {
                        //$response['derror'] = 'Database Error';
                        echo "DBASE ERROR";
                      }//end of if statement executes


            }
            else
            {
              //$response['na'] = 'Leave Days Beyond Permissible';
              echo "BEYOND LIMIT";
            }

      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
    }//end of casual
    else if($leavetype == 'annual'){
        //$response['annual'] = 'You chose annual';


            $leavedaysgone = (int)annualleavedaysgone($staffid, $session);
            $leaveallowed = leavedays($level);//total number of days allowed for any staff
            $ndaysapplied = numdays($sdate, $edate);

            $dayspermissible = $leaveallowed - $leavedaysgone;

            ///////////////////////////////////////////////////////////////
            #Put leave profile in an array to pass into json for rendering to application page
            $leaveprofile = array();
            $leaveprofile['daysgone'] = $leavedaysgone;
            $leaveprofile['leaveallowed'] = $leaveallowed;
            $leaveprofile['ndays'] = $ndaysapplied;
            $leaveprofile['permissible'] = $dayspermissible;
            //////////////////////////////////////////////////////////////

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
                            $response['status'] = 'ok';
                            //echo "ANNUAL SUCCESS";
                        }
                        else 
                        {
                            $response['status'] = 'ta';//try again
                            $response['resason'] = 'Try Again Later';
                          //echo "ANNUAL FAIL";
                        }//end of else
                      }
                      else
                      {
                        $response['status'] = 'err';
                        $response['reason'] = 'Some Error';

                        //echo "DBASE ERROR";
                      }//end of if statement executes


            }
            else
            {
              $response['status'] = 'na';//applied days beyond permissible
              $response['reason'] = $leaveprofile;


              //echo "BEYOND LIMIT";
            }

      //echo "ANNUAL";
    }//end of annual

    else if($leavetype == 'maternity'){
        //$response['maternity'] = 'You chose maternity';
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

echo json_encode($response);

?>

else if ( $leavetype == 'annual' )
  {
    /*
        $query = "SELECT level FROM stafflst WHERE staffid = '$staffid'";
        $stmt = $con->prepare($query);
        $stmt -> execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $level = $row['level'];

        if((int)$level >= 12)
        {
          $days = 30;
        }
        else if((int)$level >= 10)
        {
          $days = 21;
        }
        else {
          $days = 14;
        }
        */

      $ndays = 30;
  }//////////////////////////