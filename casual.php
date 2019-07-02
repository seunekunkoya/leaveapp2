<?php
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Insert data into transaction table
File:      casual.php
For every appno entrying this file, the transactionid increases by 1.
*/

// $staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid']))) ?implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid']))) : $_GET[''];

include "include/config.php";


$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

$level = $_SESSION['staffinfo']['level'];
$session = $lvobj->addSlash($lvobj->getSession());

$stmt = $lvobj->getOfficers();
      
/////////////////////////////////////////////////////////////////////////////////       

  $leavetype = 'casual';

  $leavedaysgone = (int)$lvobj->leavedaysgone($staffid, $session, $leavetype);
  $leaveallowed = (int)$lvobj->leavedaysallowed($staffid, $leavetype);

  $dayspermissible = (int)$leaveallowed - (int)$leavedaysgone;

  echo $leaveallowed;

    #check leavetype
    $chkLeave = $lvobj->isLeaveAppExist($staffid, $leavetype);

    $numChkLeave = $chkLeave->rowCount();
?>
<!DOCTYPE html>
<head>
<title>Leave Application Form</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
  <script src="js/datepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/casual.css">

</head>
<body>

<div>
  <?php
      echo "Level = ".$level."<br>";
  ?>
</div>

<div id="space" class="row">
    <div class="col-md-6 col-md-offset-2">
      <form class="form-horizontal" role="form" action="" method="POST">
        <fieldset>

          <!-- Form Name -->
          <legend>Casual Leave Application Form</legend>
      <div>
        
<?php
  if( $numChkLeave > 0 )
  {
      echo '<h1>You have a Casual Leave application in progress</h1>';
      echo '<a class="btn btn-md btn-default" href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'">Back</a>';

  } 
  else
  {

  ?>  

         <input type="hidden" id="staffid" name="staffId" value="<?php echo $staffid; ?>">
      </div>
          
          <!-- Date Entry-->
          <div class="form-group">
                <label class="col-sm-3 control-label" for="textinput" id="da1">Days Entitled</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="da" id="da" value="<?php echo $leaveallowed; ?>" disabled>
                </div>

                <label class="col-sm-3 control-label" for="textinput" id="dg1">Days Already Taken</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="dg" id="dg" value="<?php echo $leavedaysgone; ?>" disabled>
                </div>

                <label class="col-sm-3 control-label" for="textinput" id="dp1">Days Permissible</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="dp" id="dp"  value="<?php echo $dayspermissible; ?>" disabled>
                </div>

          </div>

          <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9" id = "message"></div>
          </div>
          
          
          <!-- Date Entry-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Proposed Start Date</label>
            <div class="col-sm-2">
              <input type="text" class="form-control" name="sdate" id="sdate" required>
            </div>

            <label class="col-sm-3 control-label" for="textinput">Proposed End Date</label>
            <div class="col-sm-2">
              <input type="text" class="form-control edate" name="edate" id="edate" required>
            </div>
            <label class="col-sm-2 control-label" id="datedif2"></label>
            <!-- <p class = "col-sm-2" id="datedif"> </p> -->
          </div>

          <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9" id = "datedif" ></div>
          </div>

          <!-- Reason for Leave-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Reason/Comment </label>
            <div class="col-sm-9">
              <textarea class="form-control input-sm" name="reason" id="reason" rows="3" cols="40" placeholder="Reasons for Leave" required></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Destination Address</label>
            <div class="col-sm-9">
              <input type="text" class="form-control input-sm" name="location" id="location">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Phone Number <br><small>While on Leave</small></label>
            <div class="col-sm-6">
              <input type="text" class="form-control input-sm" name="phone" id="phone">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Officer 1</label>
            <div class="col-sm-9">
            <?php 
            
              $select = '<select name="officer1" id="officer1" class="form-control" required>';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                       $result[] = $row; 
                        $select .= '<option value = "'.$row['staffid'].'">'.$row['sname'].' '.$row['fname'].'</option>';
                     }//end of while statement 
                       $select .= '</select>';
                       echo $select;      
            ?>
      </div>
  </div>
     
<div class="form-group">
    <label class="col-sm-3 control-label" for="textinput">Officer 2</label>
            <div class="col-sm-9">
        <select name="officer2" id="officer2" class="form-control" required>

      <?php
      foreach ($result as $staff)    
      {
             echo  '<option value = "'.$staff['staffid'].'">'.$staff['sname'].' '.$staff['fname'].'</option>';
      }
            ?>
                </select>                             
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Officer 3</label>
            <div class="col-sm-9">
              <select name="officer3" id="officer3" class="form-control" required>
    <?php
                 foreach ($result as $staff)    
      
      { 
                echo  '<option value = "'.$staff['staffid'].'">'.$staff['sname'].' '.$staff['fname'].'</option>';
            }
    ?>
                        
              </select>
            </div>
          </div>
    </fieldset>
      </form>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                
                <a class="btn btn-md btn-default" href='leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>'>Cancel</a>
                 <button type="submit" class="btn btn-md" id="apply">Submit</button><span class="loading"></span>
                 <button type="submit" class="btn btn-md" id="casual_apply">Submit</button>
                
              </div>
            </div>
          </div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->


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
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->

<!-- Modal content-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-center modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Notice</label></h4>
        </div>
        <div class="modal-body" id="leavehistory">
            <div id="deductiblecontent"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="yesbtn"><b>YES</b></button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><b>NO</b></button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->
<?php } //end of else chkleave type?>
</body>

<script>
  $('#yesbtn').click(function(){
    console.log('YES');
    $('#myModal').modal('hide');

    $( "#reason" ).prop( "disabled", false );//enables the form fields
    $( "#location" ).prop( "disabled", false );
    $( "#phone" ).prop( "disabled", false );
    $( "#officer1" ).prop( "disabled",false );
    $( "#officer2" ).prop( "disabled", false );
    $( "#officer3" ).prop( "disabled", false );
    $( "#apply" ).prop( "disabled", false );

    $('#apply').hide();
    $('#casual_apply').show();

  });

  $('#apply').click(function(){
    console.log('Submitted');
  });

  $('#casual_apply').click(function(){
    console.log('Casual Submitted');
    var leavetype = 'casual';
    var reason = $("#reason").val();
    var sdate = $("#sdate").val();
    var edate = $("#edate").val();
    var location = $("#location").val();
    var phone = $('#phone').val();
    var officer1 = $("#officer1").val();
    var officer2 = $("#officer2").val();
    var officer3 = $("#officer3").val();
    var staffid = $('#staffid').val();
    var deduct = true;

    //hurl = 'redrect.php';
  if ((leavetype == '') || (reason == '') || (sdate == '') || (edate == '') || (location == '') || (phone == '') || (officer1 == '') || (officer2 == '') || (officer3 == ''))
  {
    $('#modalContent').html('<h5 class="stylo">All fields are required</h5>');
    $('#myModal1').modal({backdrop: false, keyboard: false});
    $('#apply').html("Submit");
    
  }
  else {
        
//AJAX code to send data to php file.
    $.ajax({
            method: "POST",
            url:   "leaveappinsert.php",
            datatype: "text",
            data: {
              leavetype:leavetype,
              reason:reason,
              sdate:sdate,
              edate:edate,
              location:location,
              phone:phone,
              officer1:officer1,
              officer2:officer2,
              officer3:officer3,
              deduct:deduct
            },
            success: function(response) {
              console.log(response);
                if (response == 'SUCCESS')   {
                  window.location.replace("leavedashboard.php?id="+btoa(staffid));
                }

                if (response == 'FAIL') {
                  $('#modalContent').html('<h3 class="stylo">TRY AGAIN</h3>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  $('#apply').html("Submit");
                }

                if (response == 'EMPTY FORM') {
                  $('#modalContent').html('<h5 class="stylo">One part of the form is not filled</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  $('#apply').html("Submit");
                }

                if (response == 'DBASE ERROR') {
                  $('#modalContent').html('<h5 class="stylo">Try again later</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  $('#apply').html("Submit");
                }
                
            },
            error: function(){
              alert("error");
            }
        });
    }//end of if else
  });

  $("#edate").change(function(ev){
      console.log($(this).val());

      ev.preventDefault();
      var sdate = $("#sdate").val();
      var edate = $("#edate").val();
      var leavetype = 'casual';

      if (leavetype == "" || sdate == "") {
         $('#').html('<h5 class="stylo">Check if leave category or start date is not empty</h5>');
         $('#myModal1').modal({backdrop: false, keyboard: false});
         //alert("Check if leave category or start date is not empty");
      }
      else {

           $.ajax({
                type: "POST",
                url: "datediff.php",
                data: {
                    sdate:sdate,
                    edate:edate,
                    leavetype: leavetype
                },
                dataType: "json",
                success: function(result) {
                    console.log(result);
                    if(result.status == 'err')
                    {
                        var ndays ="Days : " + result.daysapplied;
                        $('#datedif2').html(ndays);
                        
                        $('#deductiblecontent').html('<h5 class="stylo">'+result.reason+'. You can deduct <b>'+result.deduct+' days</b> from your annual leave days to meet up with the days you are requesting. If you wish that it should be deducted  click <b>YES</b> below and if not click <b>NO</b> </h5>');
                        $('#myModal').modal({backdrop: false, keyboard: false});

                        $( "#reason" ).prop( "disabled", true );//disables the form fields
                        $( "#location" ).prop( "disabled", true );
                        $( "#phone" ).prop( "disabled", true );
                        $( "#officer1" ).prop( "disabled", true );
                        $( "#officer2" ).prop( "disabled", true );
                        $( "#officer3" ).prop( "disabled", true );
                        $( "#apply" ).prop( "disabled", true );
                    }
                    else if(result.status == 'neg')
                    {
                        //alert(result.reason);
                        $('#modalContent').html('<h5 class="stylo">'+result.reason+'. Please select another end date.</h5>');
                        $('#myModal1').modal({backdrop: false, keyboard: false});

                        $( "#reason" ).prop( "disabled", true );//disables the form fields
                        $( "#location" ).prop( "disabled", true );
                        $( "#phone" ).prop( "disabled", true );
                        $( "#officer1" ).prop( "disabled", true );
                        $( "#officer2" ).prop( "disabled", true );
                        $( "#officer3" ).prop( "disabled", true );
                        $( "#apply" ).prop( "disabled", true );
                    }

                    else if(result.status == 'na')
                    {
                        //alert(result.reason);
                        $('#modalContent').html('<h5 class="stylo">'+result.reason+'</h5>');
                        $('#myModal1').modal({backdrop: false, keyboard: false});

                        $( "#reason" ).prop( "disabled", true );//disables the form fields
                        $( "#location" ).prop( "disabled", true );
                        $( "#phone" ).prop( "disabled", true );
                        $( "#officer1" ).prop( "disabled", true );
                        $( "#officer2" ).prop( "disabled", true );
                        $( "#officer3" ).prop( "disabled", true );
                        $( "#apply" ).prop( "disabled", true );
                    }

                    else {
                      var ndays ="Days : " + result.daysapplied;
                       $('#datedif2').html(ndays);
                       console.log(ndays);
                       //$("#datedif").css({"border-left": "5px solid grey", "background-color": "lightgrey", "border-radius": "5px"});

                          $( "#reason" ).prop( "disabled", false );//enables the form fields
                          $( "#location" ).prop( "disabled", false );
                          $( "#phone" ).prop( "disabled", false );
                          $( "#officer1" ).prop( "disabled",false );
                          $( "#officer2" ).prop( "disabled", false );
                          $( "#officer3" ).prop( "disabled", false );
                          $( "#apply" ).prop( "disabled", false );
                    }
                 },
                error: function(data) {
                    $("#message").html(data);
                    $("p").addClass("alert alert-danger");
                },
            });
           
      }//end of leavetype test
           
  });
</script>

</html>