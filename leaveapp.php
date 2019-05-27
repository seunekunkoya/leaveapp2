<?php

/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Insert data into transaction table
File:      leavetrack.php
For every appno entrying this file, the transactionid increases by 1.
*/

// $staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid']))) ?implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid']))) : $_GET[''];


include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];

//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

print_r($staffdetails);

$level = $_SESSION['staffinfo']['level'];

$ltype = base64_decode($_GET['ltype']);


      //officer query
        $qry = "SELECT staffid, sname, fname FROM stafflst";
        $stmt = $con->prepare($qry);
        $stmt->execute();
       //$staff = $stmt->fetch(PDO::FETCH_ASSOC);
 /////////////////////////////////////////////////////////////////////////////////       
        #leavetype query
        $ltypeqry = "SELECT type FROM leavetypes";
        $stmt1 = $con->prepare($ltypeqry);
        $stmt1->execute();
?>
<html>
<head>
<title>Leave Application Form</title>

  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/datepicker.js"></script>

<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
        #space{
          padding-top: 100px;
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

      .dialog{
      	display: none;
      }
</style>

</head>
<body>

<div>
  <?php
      echo "Level = ".$level."<br>";
  //    echo " Allowed leave days of level = ".leavedays($level);

  ?>
</div>

  <div id="space" class="row">
    <div class="col-md-6 col-md-offset-2">
      <form class="form-horizontal" role="form" action="" method="POST">
        <fieldset>

          <!-- Form Name -->
          <legend>Leave Application Form</legend>
      <div>
        <p id="message">    </p>
         <input type="hidden" id="staffid" name="staffId" value="<?php echo $staffid; ?>">
      </div>
          <!-- Leave Category-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Leave Category</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="leavetype" id="leavetype" value="<?php echo $ltype ?>" disabled>
          <?php 
            /*
                $select = '<select name="leavetype" id="leavetype" class="form-control" required>';
                $select .= '<option value = "">Select Leave Type</option>';

                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                  //$result[] = $row; 
                $select .= '<option value = "'.$row1['type'].'">'.ucfirst($row1['type']).'</option>';
                     }//end of while statement 
                $select .= '</select>';
                echo $select;
            */
                       
          ?>

            </div>
          </div>

          <!-- Reason for Leave-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Reason/Comment</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="reason" id="reason" rows="3" cols="40" placeholder="Reasons for Leave" required></textarea>
            </div>
          </div>
          
          <!-- Date Entry-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Proposed Start Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="sdate" id="sdate" required>
            </div>

            <label class="col-sm-3 control-label" for="textinput">Proposed End Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="edate" id="edate" required>
            </div>
            <p id="datedif"> </p>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Destination Address</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="location" id="location">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Phone Number <br><small>While on Leave</small></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="phone" id="phone">
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
                <a class="btn btn-default" href='leaveapptest.php'>Select another leave category</a>
                <a class="btn btn-default" href='leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>'>Cancel</a>
                 <button type="submit" class="btn" id="apply">Submit</button>
              </div>
            </div>
          </div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div id="dataModal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title">Message</h4>  
                </div>  
                <div class="modal-body" id="leavedetail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>  

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
 
</body>


<script type="text/javascript">
  
$(function(){

  //$( "#sdate" ).datepicker( "input", "dateFormat", "d-M-yy");
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
                //alert(data);
                //$("#message").html(data);
                
                $("#datedif").addClass("adiff");
                $('p#datedif').html(res);
              },
            error: function(data) {
                $("#message").html(data);
                $("p").addClass("alert alert-danger");
            },
      });
    
      //alert("The text has been changed.");
    
    });
    
  $("#apply").on('click', function(e){
    
    e.preventDefault();

    var leavetype = $("#leavetype").val();
    var reason = $("#reason").val();
    var sdate = $("#sdate").val();
    var edate = $("#edate").val();
    var location = $("#location").val();
    var phone = $('#phone').val();
    var officer1 = $("#officer1").val();
    var officer2 = $("#officer2").val();
    var officer3 = $("#officer3").val();

    var staffid = $('#staffid').val();

    //hurl = 'redrect.php';
	if ((leavetype == '') || (reason == '') || (sdate == '') || (edate == '') || (location == '') || (phone == '') || (officer1 == '') || (officer2 == '') || (officer3 == ''))
	{
    $('#modalContent').html('<h5 class="stylo">All fields are required.</h5>');
    $('#myModal1').modal({backdrop: false, keyboard: false});
		//alert("All fields are required.");
	}
  else {
        
// AJAX code to send data to php file.
    $.ajax({
            type: "POST",
            url:   "leaveappinsert.php",
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
            },
            dataType: "text",
            success: function(response) {
              	if (response == 'SUCCESS') {
              		//alert("Application Successful. You will be redirected to Dashboard");
              		window.location.replace("leavedashboard.php?id="+btoa(staffid));
              	}

              	if (response == 'FAIL') {
                  $('#modalContent').html('<h5 class="stylo">Try Again</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
              		//alert("Try Again");
              	}

              	if (response == 'EMPTY FORM') {
                  $('#modalContent').html('<h5 class="stylo">One part of the form is not filled</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  //alert("One part of the form is not filled");
              	}

                if (response == 'DBASE ERROR') {
                  $('#modalContent').html('<h5 class="stylo">Try again later</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  //alert("Try again later");
                }
                if (response == 'CHECK') 
                {
                  $('#modalContent').html('<h5 class="stylo">Try again later</h5>');
                  $('#myModal1').modal({backdrop: 'static', keyboard: false});
                  //alert("Try again later");  
                }
            },
            error: function(data) {
                $("#message").html(data);
                $("p").addClass("alert alert-danger");
            },
        });
	}//end of if else

  });

  
});
</script>

</html>