<?php

  include "include/config.php";

  //checksession();
  $lvobj->checkSession();
  $staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;


  $staffid = $_SESSION['staffid'];
  $dept = $_SESSION['staffinfo']['dept'];
  $kol = $_SESSION['staffinfo']['kol'];
  $cat = $_SESSION['staffinfo']['category'];
  $hodid = $_SESSION['staffinfo']['hod'];
  $deanid = $_SESSION['staffinfo']['dean'];
  $hro = $_SESSION['staffinfo']['hro'];
  $rego = $_SESSION['staffinfo']['rego'];
  $vco = $_SESSION['staffinfo']['vco'];

  $appno  = base64_decode($_GET['appno']); //? base64_decode($_GET['appno']): header("Location:logout.php") ;

try {
        #extract reccommended date of appno        
        $reccDates = $lvobj->extractRecc($appno);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  #A QUICK QUERY TO CHECK IF A SUPERVISOR HAS ACTED ON AN APPLICATION
        $chkstmt = $lvobj->checkSupervisor1($appno, $staffid);
        $supnum = $chkstmt->rowCount();

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave details of the $this staff
        $stmtleave = $lvobj->leaveDetails($appno);   
        $num = $stmtleave->rowCount();
//        print_r($stmtleave);
        
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave progress of staff
        $stmtr = $lvobj->leaveProgress($appno);        
        $numtr = $stmtr->rowCount();  
        //print_r($stmtr);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        #Query to select leave history. staffid is first queried from the leave application tableand then used to find the leave history from the approve leaves table. 
        

                
  }//end of try
    catch(PDOException $e){
         echo "Error: " . $e->getMessage();
    }//end of catch      

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Leave Application Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="js/datepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/leavedash1.css">
</head>
<body>

<?php
  
  if ($num > 0) { 
        while($staffdet=$stmtleave->fetch(PDO::FETCH_ASSOC))
        {
          #approvedArr holds data going into the approvedleaves table
          $approvedArr = array(
            "staffid" => $staffdet['staffid'],
            "appno" => $appno,
            "leavetype" => $staffdet['leavetype'],
            "reason" => $staffdet['reason'],
            "session" => $staffdet['session'],
            "location" => $staffdet['location'],
            "phone" => $staffdet['phone']
          );
          
          
    ?>
<div class="wrapper">
  <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <h3>Applicant Details</h3>
            </div>
        </div>
        <div class="row">
                <div class="col-md-8">
                        <table class="table table-bordered table-condensed">
                          <tr>
                                <th>Staff Name</th>
                                <th>Post</th>
                                <th>Category</th>
                                <th>Unit/Program</th>
                                <th>Department</th>
                                <th>College/Directorate</th>
                          </tr>
                          <tr>
                            <td>
                              <?php 
                                  $historyname = $lvobj->getname($staffdet['staffid']);//To input staff name in the history modal. 
                                  echo $lvobj->getname($staffdet['staffid']);  
                              ?>
                            </td>
                            <td>
                              <?php echo $staffdet['post']; ?>
                            </td>
                            <td>
                              <?php 
                                $staffcat = $staffdet['category'];
                                echo $staffdet['category'];  
                              ?>
                            </td>
                            <td>
                              <?php echo $staffdet['unitprg'];  ?>
                            </td>
                            <td>
                              <?php echo $staffdet['dept'];  ?>
                            </td>
                            <td>
                              <?php echo $staffdet['kol'];  ?>
                            </td>    
                         </tr>
                       </table>
                </div>     
                <div class="col-md-4">
                       <button type="button" class="btn btn-md" data-toggle="modal" data-target="#myModal" data-backdrop="false">View History</button>
                </div>
            </div>
  </div><!--Container div-->
  

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
<!-----------------------------------------New Content------------------------------------------------------------------------------------------->

<h4 class="card-title"><b>Application Details</b></h4>

               <table class="table table-bordered table-condensed">
                      <tbody>                        
                         <tr>
                            <td>Leave Type:</td>
                            <td><?php echo $staffdet['leavetype']; ?></td>
                         </tr>

                         <tr>
                            <td>Applied Start Date:</td>
                              <td>
                                   <?php
                                     $stdate = date_create($staffdet['startdate']);
                                     echo date_format($stdate, "d-M-Y");
                                   ?>         
                              </td>
                          </tr>
                          
                          <tr>
                            <td>Applied End Date</td>
                            <td>
                               <?php
                                  $eddate = date_create($staffdet['enddate']);
                                  echo date_format($eddate, "d-M-Y");                                                
                               ?>    
                            </td>
                          </tr> 
                          
                          <tr>
                           <td> Days </td>
                           <td> <?php echo $lvobj->numdays($staffdet['startdate'], $staffdet['enddate']); ?> </td>
                          </tr>
                                    
                                    <tr>
                                        <td>Reason:</td>
                                        <td><?php echo $staffdet['reason']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone number:</td>
                                        <td><?php echo $staffdet['phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Officers to handover to : </b></td>
                                    </tr>
                                    <tr>
                                        <td>Officer 1:</td>
                                        <td><?php echo $lvobj->getname($staffdet['officer1']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Officer 2:</td>
                                        <td><?php echo $lvobj->getname($staffdet['officer2']); ?></td>
                                    </tr>
                                     <tr>
                                        <td>Officer 3:</td>
                                        <td><?php echo $lvobj->getname($staffdet['officer3']); ?></td>
                                    </tr>                                   
                                  </tbody>
                            </table>
                        <?php  } // end of while loop 
                           }//end of if statement
                            else {
                                echo "No Active Leave Application";
                            }
                        ?>


<!---------------------------------------------------------------------------------------------------------------------------------------------------->
</div><!---End of Side bar--->
<!----------------------------------------------------------------------------------------------------------------------------------------------->
<div class="col-sm-5">
  <?php 

      if ($supnum) {//this to check if any of the supervisors have made a recommendation or review of the current leave application
          echo '<b>You have already made recommendation on this application</b>';
          echo '&nbsp; <button><a style="font-size: 14px;"  href="leaveview.php?id='.base64_encode($staffid).'">Back</a>
        </button>';
          exit();
        }//end of $supnum

  ?>
      <h4 id="title"><b>Recommendations/Approvals</b></h4>
  <?php

    $extractDay = array();

       if ($numtr > 0) { //if starts here                 
              while($rowtr=$stmtr->fetch(PDO::FETCH_ASSOC))
                 {
                    $extractDay[] = $rowtr;
                    //$extractDay['edate'] = $rowtr['recenddate'];

                    //extract row this truns array keys into variables
  ?>               
   
    <h5>
        <span class="sub-title">
          <b><?php echo $rowtr['role']; ?></b>
        </span>
    </h5>
    <table class="table table-bordered table-condensed">
    <tr>
      <th>Recommended Start Date</th>
      <td>
        <?php
                $resdate = date_create($rowtr['recstartdate']);
                echo date_format($resdate, "d-M-Y");
        ?>
      </td>

      <th>Recommended End Date</th>
      <td>
        <?php
                   $recedate = date_create($rowtr['recenddate']);
                   echo date_format($recedate, "d-M-Y");
                ?>
      </td>
      <th>Days</th>
      <td>
        <?php
            echo $lvobj->numdays($rowtr['recstartdate'], $rowtr['recenddate']);
        ?>
      </td>
    </tr>
    <tr>
      <th>Comment </th>
      <td colspan="5">
        <?php
            echo $rowtr['remarks'];
        ?>
      </td>
    </tr>
    <tr>
      <th>Recommendation</th>
      <td colspan="5">
         <?php
             echo $rowtr['status'];
           ?>
      </td>
    </tr>
  </table>
        
<hr style="margin: 0px 0 0px;">
                    <?php
                        }//end of while
                    }//end of if statement
                    else {
                       // echo "Application in Progress";
                    }
                 ?>
<!----------------------------------------------------------------------------------------------------------------------------------------------------->
<h5><span class="sub-title">

<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
if(!isset($hodid) || !isset($deanid) || !isset($hro) || !isset($vco) || !isset($rego)){
    $lastRow = count($extractDay) - 1;
    //print_r($extractDay[$lastRow]);
    $lastRowElements = $extractDay[$lastRow];
   // print_r($lastRowElements);
    foreach ($lastRowElements as $lastRowElement) {
      $sdate = date("d-M-y", strtotime($lastRowElements['recstartdate']));
      $edate = date("d-M-y", strtotime($lastRowElements['recenddate']));
    }
    echo $sdate.'---------'.$edate;
}
  #Query for recommendations 
  /*
  Testing each staff id to know which role each staff is to play.
  */
  $recstmt = $lvobj->leaveRec($staffid, $staffcat, $hodid, $deanid, $rego, $hro, $vco);          
  $recnum = $recstmt->rowCount(); 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($_SESSION['staffid'] == $_SESSION['staffinfo']['hod'] ) 
      {
        

        echo '<b>Make Recommendation</b>';

        echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table table-condensed">';
             echo '<tr>';
               echo '<td><b>Recommended Start date</b></td>';
              
            while($lvdate = $reccDates->fetch(PDO::FETCH_ASSOC))
              {
                  $rdt = strtotime($lvdate["recstartdate"]);
                  $rdate1 = date("d-M-y", $rdt); 

                  $redt = strtotime($lvdate["recenddate"]);
                  $redate1 = date("d-M-y", $redt); 

               echo '<td> <input type="text" id="sdate" value='.$rdate1.'></td>';
               echo '<td><b>Recommended End date</b></td>';
               echo '<td> <input type="text" id="edate" value='.$redate1.'></td>';
               echo '<td id="datecomot">'.$lvobj->numdays($lvdate['recstartdate'], $lvdate['recenddate']). ' days';
               echo  '</td>';
               echo '<td id="datedif"> </td>';
             }
             //print_r($lvdate);
                    
                   echo '</tr>';
                    //echo '</table>';
                    //echo '<table class="table">';
                      echo '<tr>';
                        echo '<td><b>Comment</b></td>';
                        echo '<td colspan="5"><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>';
                      echo '</tr>';
                    //echo '</table>';                    
                    
                    //role
                    echo '<input type="hidden" id="role" value="Hod">';
                    //stage
                    echo '<input type="hidden" id="stage" value="2">';                  
                    
                    echo '<input type="hidden" id="appno" value="'.$appno.'">';
                     
                    echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">';

                    //echo '<table class="table">';
                      echo '<tr>';
                        echo '<td colspan="3"><label>Recommendation</label>';

                          echo '&nbsp; &nbsp;<select id="reco" required>';
                            echo '<option>Select Recommendation</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>';
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>';

                          
    }

else if ($_SESSION['staffid'] == $_SESSION['staffinfo']['dean']) {

   echo '<b>Make Recommendation</b>';
   echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table">';
             echo '<tr>';
               echo '<td><b>Recommended Start date</b></td>';
    
            while($lvdate=$reccDates->fetch(PDO::FETCH_ASSOC))
              {
                  $rdt = strtotime($lvdate["recstartdate"]);
                  $rdate1 = date("d-M-y", $rdt); 

                  $redt = strtotime($lvdate["recenddate"]);
                  $redate1 = date("d-M-y", $redt); 

               echo '<td> <input type="text" id="sdate" value='.$rdate1.'></td>';
               echo '<td><b>Recommended End date</b></td>';
               echo '<td> <input type="text" id="edate" value='.$redate1.'></td>';
               echo '<td id="datecomot">'.$lvobj->numdays($lvdate['recstartdate'], $lvdate['recenddate']). ' days';
               echo  '</td>';
               echo '<td id="datedif"> </td>';
             }                             
                   echo '</tr>';
                    //echo '</table>';
                    //echo '<table class="table">';
                      echo '<tr>';
                        echo '<td><b>Comment</b></td>';
                        echo '<td colspan="5"><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>';
                      echo '</tr>';
                    //echo '</table>';                    
                    
                    //role of reviewing staff
                    echo '<input type="hidden" id="role" value="Dean">';   

                    //stage of leave application process
                    echo '<input type="hidden" id="stage" value="3">';               
                    
                    echo '<input type="hidden" id="appno" value="'.$appno.'">';
                     
                    echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">';

                    //echo '<table class="table">';
                      echo '<tr>';
                        echo '<td colspan="3"><label>Recommendation</label>';

                          echo '&nbsp; <select id="reco" required>';
                            echo '<option>Select Recommendation</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>';
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>'; 

  }

 else if ($_SESSION['staffid'] == $_SESSION['staffinfo']['hro'] ) {

   echo '<b>Make Recommendation</b>';
   echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table">';
             echo '<tr>';
               echo '<td><b>Recommended Start date</b></td>';
         
            while($lvdate=$reccDates->fetch(PDO::FETCH_ASSOC))
              {   
                $rdt = strtotime($lvdate["recstartdate"]);
                $rdate1 = date("d-M-y", $rdt); 

                $redt = strtotime($lvdate["recenddate"]);
                $redate1 = date("d-M-y", $redt); 

               echo '<td> <input type="text" id="sdate" value='.$rdate1.'></td>';
               echo '<td><b>Recommended End date</b></td>';
               echo '<td> <input type="text" id="edate" value='.$redate1.'></td>';
               echo '<td id="datecomot">'.$lvobj->numdays($lvdate['recstartdate'], $lvdate['recenddate']). ' days';
               echo  '</td>';
               echo '<td id="datedif"> </td>';
             }                    
                   echo '</tr>';
                    //echo '</table>';
                    //echo '<table class="table">';
                      echo '<tr>';
                        echo '<td><b>Comment</b></td>';
                        echo '<td colspan="5"><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>';
                      echo '</tr>';
                    //echo '</table>';                    
                    
                    //role of review officer
                    echo '<input type="hidden" id="role" value="HR">';

                    //stage of leaveapplication process
                    echo '<input type="hidden" id="stage" value="4">';                  
                    
                    echo '<input type="hidden" id="appno" value="'.$appno.'">';
                     
                    echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">';

                   // echo '<table class="table">';
                      echo '<tr>';
                        echo '<td colspan ="3"><label>Recommendation</label> ';

                          echo ' <select id="reco" required>';
                            echo '<option>Select Recommendation</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>'; 
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>'; 

  }

  else if ($_SESSION['staffid'] == $_SESSION['staffinfo']['rego'] ) {

   echo ' <b>Make Recommendation/Approval</b>';

   echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table">';
             echo '<tr>';
               echo '<td>Recommended Start date</td>';
              
            while($lvdate=$reccDates->fetch(PDO::FETCH_ASSOC))
              {
                  $rdt = strtotime($lvdate["recstartdate"]);
                  $rdate1 = date("d-M-y", $rdt); 

                  $redt = strtotime($lvdate["recenddate"]);
                  $redate1 = date("d-M-y", $redt); 

               echo '<td> <input type="text" id="sdate" value='.$rdate1.'></td>';
               echo '<td><b>Recommended End date</b></td>';
               echo '<td> <input type="text" id="edate" value='.$redate1.'></td>';
               echo '<td id="datecomot">'.$lvobj->numdays($lvdate['recstartdate'], $lvdate['recenddate']). ' days';
               echo  '</td>';
               echo '<td id="datedif"> </td>';
             }
                      
                   echo '</tr>';
                    echo '</table>';
                    echo '<table class="table">';
                      echo '<tr>';
                        echo '<td>Comment</td>';
                        echo '<td><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>';
                      echo '</tr>';
                    echo '</table>';                    
                    
                    //role
                    echo '<input type="hidden" id="role" value="Registrar">';                  
                    
                    //stage
                    echo '<input type="hidden" id="stage" value="5">';

                    echo '<input type="hidden" id="appno" value="'.$appno.'">';
                     
                    echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">';

                    echo '<table class="table">';
                      echo '<tr>';
                        echo '<td><label>Recommendation  </label>';

                          echo '<select id="reco" required>';
                            echo '<option>Select Recommendation</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>';
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>'; 

  }
  else if ($_SESSION['staffid'] == $_SESSION['staffinfo']['vco'] ) {

    
    echo '<b>Make Approval</b>';

        echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table">';
             echo '<tr>';
               echo '<td>Recommended Start date</td>';
              
            while($lvdate=$reccDates->fetch(PDO::FETCH_ASSOC))
              { 
                $rdt = strtotime($lvdate["recstartdate"]);
                $rdate1 = date("d-M-y", $rdt); 

                  $redt = strtotime($lvdate["recenddate"]);
                  $redate1 = date("d-M-y", $redt); 

               echo '<td> <input type="text" id="sdate" value='.$rdate1.'></td>';
               echo '<td><b>Recommended End date</b></td>';
               echo '<td> <input type="text" id="edate" value='.$redate1.'></td>';
               echo '<td id="datecomot">'.$lvobj->numdays($lvdate['recstartdate'], $lvdate['recenddate']). ' days';
               echo  '</td>';
               echo '<td id="datedif"> </td>';
             }
                   echo '</tr>';
                    echo '</table>';
                    echo '<table class="table">';
                      echo '<tr>';
                        echo '<td>Comment</td>';
                        echo '<td><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>';
                      echo '</tr>';
                    echo '</table>';                    
                    
                    //role
                    echo '<input type="hidden" id="role" value="VC">';                  
                    
                    //stage 
                    echo '<input type="hidden" id="stage" value="6">';

                    echo '<input type="hidden" id="appno" value="'.$appno.'">';
                     
                    echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">';

                    echo '<table class="table">';
                      echo '<tr>';
                        echo '<td colspan="2"><label>Recommendation  </label>';

                          echo '<select id="reco" required>';
                            echo '<option>Approval Options</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>'; 
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>'; 
  }
?> 
      </td>
     <td colspan="3">
        <button id="btn-save" class="btn">Save</button>
        <button class="btn">
          <a style="font-size: 14px;"  href="leaveview.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Back</a>
        </button>
      </td>
      </tr>
  </table>  
</div>
</div>
<div id="error" style="color: white;"></div>
  
</div>

<!----MODAL----->
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><label>Summary of Leave History for <?php echo $historyname; ?></label></h4>
        </div>
        <div class="modal-body">
          <?php 
                  
              $hstmt = $lvobj->getLeaveHistorySummaryByType($approvedArr['staffid']);
              $hnum = $hstmt->rowCount();
              if ($hnum > 0) { 
           ?>
            <div class="table-responsive">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th width="30%">Leave Type</th>  
                               <th width="30%">Number of Days</th>
                               <th width="30%">View</th>  
                          </tr> 
            <?php //////////////////////////////////////////////////////////////Leave History/////////////////////////////////////////////
                  $newAr = array();             
                  while($staffhistory=$hstmt->fetch(PDO::FETCH_ASSOC))
                  {                    

                   ?>
                    <tr>
                      <td id = "lvtype"><?php echo ucfirst($staffhistory['leavetype']); ?></td>
                      <input type="hidden" value="<?php echo $staffhistory['leavetype']; ?>" >
                      <td><?php echo $staffhistory['totalday']; ?></td>
                      <td>
                        <input type="button" name="view" value="Full Details..." id="<?php echo $staffhistory["staffid"]; ?>" class="btn btn-xs view_history" />
                      </td>

                    </tr>

          <?php  }//end of while ?>
                
              </table>
        </div><!--table responsive--->
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
</div>

  <?php }//end of if 
    else { ?>

          <p>No records yet for staff history.</p>
          <!-- <button type="button" class="btn btn-md" data-toggle="modal" data-target="#myModal2" data-backdrop="false">Full Details...</button> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
</div>
<?php } //end of else?>


<!-- Modal -->
 <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-center modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Leave History Details</label></h4>
        </div>
        <div class="modal-body" id="leavehistory">
           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->

</div><!--End of wrapper-->


<script type="text/javascript">
 $(document).ready(function(){
      
      $('.view_history').click(function(){  
           var staffid = $(this).attr("id");  
           var ltype = $(this).closest('tr').find('#lvtype').text().toLowerCase();
           console.log(ltype +' '+ staffid);
           $.ajax({  
                url:"leavehistory.php",  
                method:"post",  
                data:{staffid:staffid, ltype:ltype},  
                success:function(data){  
                     $('#leavehistory').html(data);  
                     $('#myModal2').modal("show");  
                }  
           });  
      });


    $("#edate").change(function(ev){

      ev.preventDefault();

      var sdate = $("#sdate").val();
      var edate = $("#edate").val();

      $.ajax({
        type: "POST",
        url: "datedif.php",
        data: {
            sdate:sdate,
            edate:edate
        },
        dataType: "text",
            success: function(res) {             
               
                $('#datecomot').hide();
                $('#datedif').show();
                $('#datedif').html(res);
              },
            error: function(data) {
                $("#message").html(data);
                $("p").addClass("alert alert-danger");
            },
      });
    
      //alert("The text has been changed.");
  });
          
  $('.goback').click(function() {
       history.back();
   });   

  $('#btn-save').click(function(){
          
      var appno = $('#appno').val();
      var staffid = $('#staffid').val();
      var sdate = $('#sdate').val();
      var edate = $('#edate').val();
      var remarks = $('#remarks').val();
      var status = $('#reco').val();
      var role = $('#role').val();
      var stage = $('#stage').val();

      var encappno = window.btoa(staffid);

      var url = "leaveview.php?id="+encappno;            

      if ((appno == '') || (staffid == '') || (sdate == '') || (edate == '') || (remarks == '') || (status == ''))
      {
         alert("All fields are necessasry");
      }
      else {

            if (status == 'Approved') {

              var approvedArr = <?php echo json_encode($approvedArr, JSON_PRETTY_PRINT) ?>;
              console.log(approvedArr);
              
               $('#error').load('leaveapprove.php', {
                  approvedArr: approvedArr,
                  sdate: sdate,
                  edate: edate,
                  remarks: remarks,
                  status: status,
                  role: role,
                  stage: stage
                }, 
               function(){
                 $(location).attr('href', url);
              });    
            }//end of if 
                  
           else {        
              
              $('#error').load('leaverec.php', {
                  appno: appno,
                  staffid:staffid,
                  sdate: sdate,
                  edate: edate,
                  remarks: remarks,
                  status: status,
                  role: role,
                  stage: stage
               }, 
              function(){
                $(location).attr('href', url);
              });
          }//end of else
      }//end of main else
  });
});
</script>
</body>
</html>