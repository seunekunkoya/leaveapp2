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
        #A QUICK QUERY TO CHECK IF A SUPERVISOR HAS ACTED ON AN APPLICATION
        $chkstmt1 = $lvobj->checkSupervisor1($appno, $staffid);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $stmtApprovedDates = $lvobj->getApprovedDates($appno);
        
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave details of the $this staff
        $stmtleave = $lvobj->leaveDetails($appno);
        //$stmtapp = $con->prepare($queryleave);      
        $num = $stmtleave->rowCount();      
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to select leave progress of staff
        $stmtr = $lvobj->leaveProgress($appno);
        $numtr = $stmtr->rowCount();  

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        $recstmt = $lvobj->getRecHr();    
        $recnum = $recstmt->rowCount();

        
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
  <meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
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
<!---------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="col-sm-4">
  <h4 id="title"><b>Release Applicant</b></h4>
  
<!----------------------------------------------------------------------------------------------------------------------------------------------------->
<h5><span class="sub-title">

<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 if ($_SESSION['staffid'] == $_SESSION['staffinfo']['hro'] ) {
   //echo '<b>Make Recommendation</b>';
   echo "</span>";
        echo "</h5>";

        echo '<div class="row">'; 
            echo '<table class="table">';
             echo '<tr>';
               //echo '<td><b>Recommended Start date</b></td>';
         
            while($approvedDates = $stmtApprovedDates->fetch(PDO::FETCH_ASSOC))
             {   
                  
                  $sdate = date_create($approvedDates["recstartdate"]);
                  //echo date_format($sdate, "d-M-Y");

                  $edate = date_create($approvedDates["recenddate"]);
                  //echo date_format($edate, "d-M-Y");
         
                  echo '<td><b>Approved Start date</b></td>';
                  echo '<td> <input type="text" id="sdate" value="'.date_format($sdate, "d-M-Y").'" disabled></td>';
                  echo '<td><b>Approved End date</td></b>';
                  echo '<td> <input type="text" id="edate" value="'.date_format($edate, "d-M-Y").'" disabled></td>';
                  echo '';
                  echo '<td id="datecomot">'.$lvobj->numdays($approvedDates['recstartdate'], $approvedDates['recenddate']). ' days';
                  echo  '</td>';
                  echo '<td id="datedif"> </td>';
                               
                  echo '</tr>';
                    echo '</table>';
                    echo '<table class="table">';
                      echo '<tr>';
                        echo '<td><b>Comment</b></td>';
                        echo '<td><textarea class="form-control" id="remarks" rows="2" cols="80" readonly>'.$approvedDates["remarks"].'</textarea></td>';
                      echo '</tr>';
                    echo '</table>';                    
            }//end of while  


                    //role
                    echo '<input type="hidden" id="role" value="HR">';
                    //stage 
                    echo '<input type="hidden" id="stage" value="4">';

                        echo '<td><label>Release Options</label> ';
                        echo '<input type="hidden" id="appno" value="'.$appno.'">';
                         echo '<input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffinfo']['staffid'].'">';
                          echo ' <select id="reco">';
                            echo '<option>Select Release</option>';         
                              
                                  if ($recnum > 0) { //if starts here
                                      
                                      while($rowrec=$recstmt->fetch(PDO::FETCH_ASSOC))
                                       {                                                    
                                          echo '<option value = "'.$rowrec["recctitle"].'">'.$rowrec["recctitle"].'</option>'; 
                                      }// end of while statement
                                  }//end of if statement  
                            
                          echo '</select>'; 

}
  
?>
        <!--------------------------cut from here---------------------------------------------------->    
  </td>
       <td>

        <!-- <button id="btn-save" class="btn">Release</button> -->
      </td>
      <td>
        <button id="cancelBtn"> <a style="font-size: 14px;" href="approvedleaveview.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Back</a></button> 
      </td>
      </tr> 
  </table> 
  <div id="releaseOption">
    
  </div><!-- 
  <table class="table release"><tr> <td>Number of days</td> <td> <input type = "text" class="releaseStyle" id="numd"> </td> </tr> <tr> <td> Starting From </td> <td><input type = "text" class="releaseStyle" id="startFr"></td></tr><tr><td>To Resume</td><td><input type = "text" class="releaseStyle" id="toRes"></td></tr> <tr><td><button id="btn-save" class="btn" id="saveRel">Save</button></td><td><button id="btn-save" class="btn">Cance</button></td></tr></table> -->
</div>
</div>
<div id="error"></div>

</div>

<!-- Modal1 -->
 <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-center">
    
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Notice</label> <span class="glyphicon glyphicon-alert ml-3"></span></h4>
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

<!-- Modal1 -->
 <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-center modal-lg">
    
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



 <script type="text/javascript">

    $(document).ready(function(){

            $('select#reco').change(function(){

            var appno = $('#appno').val();
            var staffid = $('#staffid').val();
            var sdate = $('#sdate').val();
            var edate = $('#edate').val();
            var remarks = $('#remarks').val();
            var status = $(this).val();
            var role = $('#role').val();
            var stage = $('#stage').val();

           // console.log(appno+staffid+sdate+edate+remarks+status+role);

            var encappno = window.btoa(staffid);
            var appnum = window.btoa(appno);

            var url = "leavedashboard.php?id="+encappno;            

            if ((appno == '') || (staffid == '') || (sdate == '') || (edate == '') || (remarks == '') || (status == '') )
            {
                 // alert("There is a missing field somewhere.");
              $('#modalContent').html('<h3 class="stylo">There is a missing field somewhere.</h3>');
              $('#myModal1').modal({backdrop: false, keyboard: false});
            }

            else {
              if(status == 'Released'){

                    $('#cancelBtn').hide();

                    //alert(reco);
                    $('#releaseOption').html(
                      ' <table class="table release"><tr> <td>Number of days</td> <td> <input type = "text" id="numd" class="releaseStyle"> </td> </tr> <tr> <td> Starting From </td><td><input type = "date" class="releaseStyle" id="startFr"></td></tr><tr><td>To Resume</td><td><input type = "text" class="releaseStyle" id="toRes"></td></tr> <tr><td><button class="btn" id="saveRel">Save</button></td><td><button> <a style="font-size: 14px;" href="approvedleaveview.php?id=<?php echo base64_encode($_SESSION['staffid']); ?>">Cancel</a></button></td></tr></table>'
                      );

                     $("#numd").focus(function(){
                          $(this).val('');
                          $( "#startFr" ).prop( "disabled", false );
                          $( "#toRes" ).prop( "disabled", false );
                        });

                     $('#numd').change(function(){
                        var numd = $(this).val();

                        $.ajax({
                              type: "POST",
                              url: "checkdays.php",
                              data: {numd: numd, sdate: sdate, edate: edate},
                              dataType: "json",
                                success: function(result) {
                                  if(result.numGreater){
                                    $('#modalContent').html('<h5 class="stylo">Number of days greater than approved days. Click <a data-dismiss="modal" class ="btn-style" href="#">here</a> to re-enter the number of days. </h5>');
                                    $('#myModal1').modal({backdrop: false, keyboard: false});
                                    //$('#myModal1').modal("show");
                                    $("#numd").val('');//clears the input value
                                    $( "#startFr" ).prop( "disabled", true );
                                    $( "#toRes" ).prop( "disabled", true );
                                    console.log(result.numGreater);
                                  }
                                                                   
                              }
                          });//ajax ends
                     });//end of numd

                    $('#startFr').change(function(){
                        var startFr = $(this).val();
                        var numD = $('#numd').val();

                        $.ajax({
                              type: "POST",
                              url: "addToDate.php",
                              data: {startFr:startFr, numD:numD},
                              dataType: "json",
                                  success: function(result) {
                                    var newDate = result.newdate;
                                    $('#toRes').val(newDate)
                                    //console.log(result.newdate);                                 
                                    }
                          });//ajax ends
                     });//end of startFr

                    $('#saveRel').click(function(){                            

                            var numD = $('#numd').val();
                            var startFr = $('#startFr').val();
                            var toRes = $('#toRes').val();

                            //console.log(sdate+' '+edate+ 'Release '+ toRes +' '+startFr);

                            /*
                            $.ajax({
                              type: "POST",
                              url: "newreleasestaff.php",
                              dataType: "json",
                              data: {
                                  appno: appno,
                                  staffid:staffid,
                                  sdate: sdate,
                                  edate: edate,
                                  remarks: remarks,
                                  status: status,
                                  role: role,
                                  stage: stage,
                                  numD: numD,
                                  startFr: startFr,
                                  toRes: toRes
                              },
                                success: function(result) {

                                  if(result.insert)
                                  {
                                    $(location).attr('href', url);
                                  }
                                  if(result.error)
                                  {
                                    $('#modalContent').html('<p>Resource Not Available. Please try again later</p>');
                                    $('#myModal1').modal({backdrop: false, keyboard: false});
                                  }
                                          
                                }
                          });//ajax ends
                          */

                           $('#error').load('newreleasestaff.php', {
                                  appno: appno,
                                  staffid:staffid,
                                  sdate: sdate,
                                  edate: edate,
                                  remarks: remarks,
                                  status: status,
                                  role: role,
                                  stage: stage,
                                  numD: numD,
                                  startFr: startFr,
                                  toRes: toRes
                           }, 
                           function(){
                              //console.log("Approval Sent");
                             $(location).attr('href', url);
                          });

                    }); 
              }//end of if released

              if(status == 'Not Released' || status == 'Not Yet Released')
              {
                //alert("Not yet Released");
                $('#modalContent').html('<p>Not yet Released</p>');
                $('#myModal1').modal({backdrop: false, keyboard: false});
              }

              /*
                $('#error').load('releasestaff.php', {
                          appno: appno,
                          staffid:staffid,
                          sdate: sdate,
                          edate: edate,
                          remarks: remarks,
                          reco: reco,
                          role: role,
                          stage: stage
                     }, 
                     function(){
                          alert("Approval Sent");
                          $(location).attr('href', url);
                    });  
                */
            }       
        });          
    });
    
</script>
</body>
</html>
