<?php
include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

//print_r($staffdetails);

$level = $_SESSION['staffinfo']['level'];

$id = base64_decode($_GET['id']);
$cat = $_SESSION['staffinfo']['category'];

  $dept = $_SESSION['staffinfo']['dept'];
  $kol = $_SESSION['staffinfo']['kol'];
  $cat = $_SESSION['staffinfo']['category'];
  $hodid = $_SESSION['staffinfo']['hod'];
  $deanid = $_SESSION['staffinfo']['dean'];
  $hro = $_SESSION['staffinfo']['hro'];
  $rego = $_SESSION['staffinfo']['rego'];
  $vco = $_SESSION['staffinfo']['vco'];


  //$appno  = base64_decode($_GET['appno']); //? base64_decode($_GET['appno']): header("Location:logout.php") ;

try {

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave details of the $this staff
        $stmtleave = $lvobj->getDetailsByStaffid($staffid, $cat);
        
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
  <title>Leave Resume</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="js/datepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/leavedash1.css">

  <script>
  $( function() {
    $( "#rdate" ).datepicker({dateFormat: 'd-M-yy'});
  } );
  </script>
  
</head>
<body>
<?php
  if ($num > 0) { 
        while($staffdet = $stmtleave->fetch(PDO::FETCH_ASSOC))
        {
    ?>
    <div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-8">
        <h3>Applicant Details</h3>
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
<h4 class="card-title"><b>Application Details</b></h4>

               <table class="table table-bordered table-condensed">
                      <tbody>                        
                         <tr>
                            <td>Leave Type:</td>
                            <td><?php echo $staffdet['leavetype']; ?></td>
                         </tr>

                         <tr>
                            <td>Leave Start Date:</td>
                              <td>
                                   <?php
                                     $stdate = date_create($staffdet['startdate']);
                                     echo date_format($stdate, "d-M-Y");
                                   ?>         
                              </td>
                          </tr>
                          
                          <tr>
                            <td>Leave End Date</td>
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
<!-- 
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
</table> -->

<!---------------------------------------------------------------------------------------------------------------------------------------------------->
</div><!---End of Side bar--->
<!----------------------------------------------------------------------------------------------------------------------------------------------->
<div class="col-sm-5">
       <h4 id="title"><b>Resumption Form</b></h4>       

      <hr style="margin: 0px 0 0px;">
      

    <table class="table table-condensed">  
    <tr>
      <td>Enter Resumption Date</td>
      <td><input type="text" class="form-control" name="rdate" id="rdate" required></td>
    </tr> 
      <tr>
      
      <td>
        <?php echo '<input type="hidden" id="appno" value="'.$staffdet['appno'].'">'; ?>
        <?php echo '<input type="hidden" id="role" value="Applicant">'; ?>
        <?php echo '<input type="hidden" id="stage" value="1">'; ?>
        <?php echo '<input type="hidden" id="status" value="Resumed">'; ?>
        <?php echo '<input type="hidden" id="sdate" value="'.$staffdet['recstartdate'].'">'; ?>
        <?php echo '<input type="hidden" id="edate" value="'.$staffdet['recenddate'].'">'; ?>
        <?php echo '<input type="hidden" id="staffid" value="'.$staffid.'">'; ?>
      </td>
      <td>
        <button id="btn-save" class="btn">Save</button>
        <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Cancel</a>
        </button>
      </td>
      </tr>
  </table>  
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


</div>

  <?php  } // end of while loop 
       }//end of if statement
  ?>
<div id="message"></div>
  
</div>
</div><!--End of wrapper-->

 <script type="text/javascript">

    $(document).ready(function(){
     
      $('#btn-save').click(function(){
        //alert('button clicked');
           
        var appno = $('#appno').val();
        var staffid = $('#staffid').val();
        var sdate = $('#sdate').val();
        var edate = $('#edate').val();
        var remarks = $('#remarks').val();
        var status = $('#status').val();
        var role = $('#role').val();
        var stage = $('#stage').val();
        var rdate = $('#rdate').val();

        var encappno = window.btoa(staffid);

        var url = "leavedashboard.php?id="+encappno;            

          if (rdate == '')
          {
                //alert("Date cannot be blank");
                $('#modalContent').html('<h4>Date cannot be blank</h4>');
                $('#myModal1').modal({backdrop: false, keyboard: false});
          }
          else {
            $('#message').load('resumeleave.php', {
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
               //console.log("Date Saved");
               $(location).attr('href', url);
          });
        }
       //alert(reason + edate + sdate + status);
         
        });
    });

</script>
</body>
</html>