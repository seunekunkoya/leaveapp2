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

  $appno  = base64_decode($_GET['appno']);

try {

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave details of the $this staff
        $stmtleave = $lvobj->getDetailsByCategory($appno, $cat);
        
        $num = $stmtleave->rowCount();

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style>
    .wrapper{
      padding-left: 300px;
    }
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1000px;}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }

  .adiff {
  position: absolute;
  top: 237px;
  right: -90px;
  width: 100px;
  height: 40px;
  padding: 3px;
  margin-left: 10px;  
}
  
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>

<div class="wrapper">
<?php
  if ($num > 0) { 
        while($staffdet = $stmtleave->fetch(PDO::FETCH_ASSOC))
        {
    ?>
<div class="container-fluid">
<div class="row">
<div class="col-md-8">
        <h3><b>Applicant Details</b></h3>
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
        <?php echo $lvobj->getname($staffdet['staffid']);  ?>
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
</div>
</div>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
<!------------------------------------------------New Content---------------------------------------------------------------------------------------------->
<h4 class="card-title"><b>Leave Approval Details</b></h4>

               <table class="table table-bordered table-condensed">
                      <tbody>                        
                         <tr>
                            <td>Leave Type:</td>
                            <td><?php echo $staffdet['leavetype']; ?></td>
                         </tr>

                         <tr>
                            <td>Approved Start Date:</td>
                              <td>
                                   <?php
                                     $stdate = date_create($staffdet['startdate']);
                                     echo date_format($stdate, "d-M-Y");
                                   ?>         
                              </td>
                          </tr>
                          
                          <tr>
                            <td>Approved End Date</td>
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

<h4><b>Leave History for Current Year</b></h4>
<table class="table table-bordered table-condensed">
  <tr>
    <th style="width: 50%;">Casual leave days taken</th>
    <td>4</td>
    <th>Number to be deducted</th>
    <td>4</td>
  </tr>

  <tr>
    <th>Totals Days statusmmended for Annual Leave</th>
    <td>4</td>
    <th>Leave Days Entitled</th>
    <td>4</td>
  </tr>
</table>

<!---------------------------------------------------------------------------------------------------------------------------------------------------->
</div><!---End of Side bar--->
<!----------------------------------------------------------------------------------------------------------------------------------------------->
<div class="col-sm-5">
       <h4 id="title"><b>Resumption Confirmation Form</b></h4>       

      <hr style="margin: 0px 0 0px;">
      

    <table class="table table-condensed">  
    <tr>
      <td>Expected Resumption Date</td>
        <?php  
            $resdt = date_create($lvobj->resumptionday($staffdet['enddate']));

        ?>
      <td><input type="text" class="form-control" name="rdate" id="rdate" value = "<?php echo date_format($resdt, "d-M-Y");   ?>" disabled>
        </td>
    </tr> 
    
    <tr>
      <td>Resumption Date</td>
        <?php $dayresumed = date_create($staffdet['timeviewed']);  ?>
      <td><input type="text" class="form-control" name="rdate" id="rdate" value = "<?php echo date_format($dayresumed, "d-M-Y"); ?>" disabled>
        <small><b>Entered by Staff</b></small></td>
    </tr> 

    <td>Remarks</td>
        
      <td><textarea class="form-control" id="remarks" rows="2" cols="80" required></textarea></td>
    </tr> 
      <tr>
      
      <td>
        <?php echo '<input type="hidden" id="appno" value="'.$staffdet['appno'].'">'; ?>
        <?php echo '<input type="hidden" id="role" value="Hod">'; ?>
        <?php echo '<input type="hidden" id="stage" value="2">'; ?>
        <?php //echo '<input type="hidden" id="status" value="Resumed">'; ?>
        <?php echo '<input type="hidden" id="sdate" value="'.$staffdet['recstartdate'].'">'; ?>
        <?php echo '<input type="hidden" id="edate" value="'.$staffdet['recenddate'].'">'; ?>
        <?php echo '<input type="hidden" id="staffid" value="'.$staffid.'">'; ?>
      </td>
      <td>
        <button id="btn-save" class="btn">Confirm</button>
        <button id="btn-notconfirmed" class="btn">Not Confirmed</button>
        <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Cancel</a>
        </button>
      </td>
      </tr>
  </table>  
</div>
</div>

  <?php  } // end of while loop 
       }//end of if statement
       else
       {
        echo "Staff Resumed Already";
       }
  ?>
<div id="message"></div>
  
</div>

<!-- Modal1 -->
 <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-top modal-xs">
    
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Notice</label></h4>
        </div>
        <div class="modal-body" id="leavehistory">
            <div id="modalContent"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->

</div><!--End of div content-->
 <script type="text/javascript">

    $(document).ready(function(){
     
      $('#btn-save').click(function(){
        //alert('button clicked');
        var appno = $('#appno').val();
        var staffid = $('#staffid').val();
        var sdate = $('#sdate').val();
        var edate = $('#edate').val();
        var remarks = $('#remarks').val();
        var status = 'Resumption Confirmed';
        var role = $('#role').val();
        var stage = $('#stage').val();
        var rdate = $('#rdate').val();

        var encappno = window.btoa(staffid);

        var url = "leavedashboard.php?id="+encappno;            

          if (remarks == '')
          {
                
                $('#modalContent').html('<h4>Remarks cannot be blank</h4>');
                $('#myModal1').modal({backdrop: false, keyboard: false});
           }
          else {
            
              $('#message').load('resumptionconfirmed.php', {
                  appno: appno,
                  staffid:staffid,
                  sdate: sdate,
                  edate: edate,
                  remarks: remarks,
                  status: status,
                  role: role,
                  stage: stage,
                  rdate: rdate
              }, 
              function(){
                //alert("Confirmation Sent");
                $(location).attr('href', url);
              });
            }
       });

      $('#btn-notconfirmed').click(function(){

        var appno = $('#appno').val();
        var staffid = $('#staffid').val();
        var sdate = $('#sdate').val();
        var edate = $('#edate').val();
        var remarks = $('#remarks').val();
        var status = 'Resumption Not Confirmed';
        var role = $('#role').val();
        var stage = $('#stage').val();
        var rdate = $('#rdate').val();

        var encappno = window.btoa(staffid);

        var url = "leavedashboard.php?id="+encappno;

        if (remarks == '')
        {
           //alert("Date cannot be blank");
           $('#modalContent').html('<h4>Remarks cannot be blank</h4>');
           $('#myModal1').modal({backdrop: false, keyboard: false});
        }
        else {
          //alert(reason + edate + sdate + status);
         $('#message').load('resumptionconfirmed.php', {
                appno: appno,
                staffid:staffid,
                sdate: sdate,
                edate: edate,
                remarks: remarks,
                status: status,
                role: role,
                stage: stage,
                rdate: rdate
            }, 
          function(){
          //alert("Date Saved");
               $(location).attr('href', url);
           });
        }//end of else                 
      });//end of btn not confirmed
  });

</script>
</body>
</html>