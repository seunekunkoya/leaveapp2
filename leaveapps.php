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


$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

$level = $_SESSION['staffinfo']['level'];
echo $lvobj->addSlash($lvobj->getSession());

//$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //officer query
        $qry = "SELECT staffid, sname, fname FROM stafflst";
        $stmt = $con->prepare($qry);
        $stmt->execute();
       //$staff = $stmt->fetch(PDO::FETCH_ASSOC);
 /////////////////////////////////////////////////////////////////////////////////       
        #leavetype query
        $ltypeqry = "SELECT sn,type FROM leavetypes";
        $stmt1 = $con->prepare($ltypeqry);
        $stmt1->execute();

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

<!-------------------------------------------------Include the above in your HEAD tag--------------------------------------------------------------->
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

    #leavehistory{ z-index: 3000; }

    h5.stylo{
      line-height: 2;
    }

    .modal a {
      text-decoration: none;
      color: black;
    }

    a.btn-style {
        width: 50px;
        height: 25px;
        font-size: 12px;
        text-decoration: none;
        border: 1px solid #474849;
        margin: 0px 5px 0px 5px;
        padding: 3px;
        background-color: #f9fbff;
        color: black;
        border-radius: 5px;
    }
    a.btn-style:hover {background-color: #e7e7e7;}


</style>

</head>
<body>

<div id="dataModal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title">Message</h4>  
                </div>  
                <div class="modal-body" id="leavedetail">  
                  <p>Days not Permissible</p>
                </div>  
                <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>

<div>
  <?php
      echo "Level = ".$level."<br>";
      //echo " Allowed leave days of level = ".leavedays($level);
      //echo "Leave days in the Session = ".(int)casualleavedaysgone($staffid, '2018/2019');
  ?>
</div>

<div id="space" class="row">
    <div class="col-md-6 col-md-offset-2">
      <form class="form-horizontal" role="form" action="" method="POST">
        <fieldset>

          <!-- Form Name -->
          <legend>Leave Application Form</legend>
      <div>
        <!-- <p id="message">    </p> -->
         <input type="hidden" id="staffid" name="staffId" value="<?php echo $staffid; ?>">
      </div>
          <!-- Leave Category-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Leave Category</label>
            <div class="col-sm-9">
          <?php 
            
                $select = '<select name="leavetype" id="leavetype" class="form-control" required>';
                $select .= '<option value = "">Select Leave Type</option>';

                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                  //$result[] = $row; 
                $select .= '<option value = "'.$row1['type'].'">'.ucfirst($row1['type']).'</option>';
                     }//end of while statement 
                $select .= '</select>';
                echo $select;                     
          ?>
            </div>
          </div>


          <!-- Date Entry-->
          <div class="form-group">
                <label class="col-sm-3 control-label" for="textinput" id="da1">Days Entitled</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="da" id="da" disabled>
                </div>

                <label class="col-sm-3 control-label" for="textinput" id="dg1">Days Already Taken</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="dg" id="dg" disabled>
                </div>

                <label class="col-sm-3 control-label" for="textinput" id="dp1">Days Permissible</label>
                <div class="col-sm-1">
                   <input type="text" class="form-control" name="dp" id="dp" disabled>
                </div>

          </div>

          <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9" id = "message" ></div>
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
                 <button type="submit" class="btn btn-md" id="apply">Submit</button>
              </div>
            </div>
          </div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<!-- Modal2 -->
 <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-center modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Maternity Details Form</label></h4>
        </div>
        <div class="modal-body" id="leavehistory">
            <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">EDD</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="sdate" id="edd" required>
            </div>
          </div>
            <div class="form-group">
              <label for="psw"><span></span>&nbsp; </label>
              <label for="psw"><span></span>&nbsp; </label>              
            </div>
              <button type="submit" class="btn btn-block" id="eddform"> Submit</button>
      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->


 
</body>


<script type="text/javascript">
$(function(){

  $("#eddform").click(function(){
      edd = $('#edd').val();
      //alert(edd); 

        $.ajax({
          type: "POST",
          url: "edd.php",
          data: {
              edd:edd
          },
          dataType: "text",
              success: function(outp)
              {
        //      console.log(outp);
                var res = outp.split('|');
                var edda = res[0];
                var eddb = res[1];
                var eddd = res[2];
                      
                $('#sdate').val(eddb);
                $('#edate').val(edda);
                $("#datedif3").html(eddd);

                //enables the form fields
                $( "#sdate" ).prop( "disabled", true);
                $( "#edate" ).prop( "disabled", true );

                $('#myModal2').modal("hide");        
              },
        });  
    });

  $(".edate").change(function(ev){

      ev.preventDefault();

      var sdate = $("#sdate").val();
      var edate = $("#edate").val();
      var leavetype = $("#leavetype").val();

      if (leavetype == "" || sdate == "") {
        $('#modalContent').html('<h5 class="stylo">Check if leave category or start date is not empty</h5>');
        $('#myModal1').modal({backdrop: 'static', keyboard: false});
        //alert("Check if leave category or start date is not empty");
      }
      else {
                $.ajax({
                    type: "POST",
                    url: "datedif.php",
                    data: {
                        sdate:sdate,
                        edate:edate
                    },
                    dataType: "text",
                        success: function(res) {
                            $("#datedif3").html(res);
                          },
                        error: function(data) {
                            $("#message").html(res);
                            $("p").addClass("alert alert-danger");
                        },
                  });
      }     
    
      //alert("The text has been changed.");
  });

$('select#leavetype').change(function(e){

    var leavetype = $(this).val();
    var staffid = $('#staffid').val();

    $.ajax({
            type: "POST",
            url: "checkleavetype.php",
            data: {leavetype:leavetype, staffid:staffid},
            dataType: "json",
                success: function(result) {
                
                  //console.log(result);
                   if(result.status == 'TRUE')
                    {
                      $('#modalContent').html('<h5 class="stylo">You currently have an application for ' +leavetype+ ' leave in progress. Click check status to view the status of your application or click back to select another leave category.</h5>');
                      $('.modal-footer').html('<button class="btn btn-default pull-left"><a href="leavestatus.php?id=<?php echo base64_encode($_SESSION['staffid']); ?>">Check Status</a></button> <button class="btn btn-default" data-dismiss="modal">Back</button>')
                      $('#myModal1').modal({backdrop: false, keyboard: false})

                       $( "#sdate" ).prop( "disabled", true );
                       $( "#edate" ).prop( "disabled", true );
                       $( "#reason" ).prop( "disabled", true );//disables the form fields
                       $( "#location" ).prop( "disabled", true );
                       $( "#phone" ).prop( "disabled", true );
                       $( "#officer1" ).prop( "disabled", true );
                       $( "#officer2" ).prop( "disabled", true );
                       $( "#officer3" ).prop( "disabled", true );
                       $( "#apply" ).prop( "disabled", true );
                       $('select#leavetype').val('');
//                     window.location.replace("leaveapptest.php");
                    }
                  }
        });
        //clear initial values entered from form
        $('#sdate').val("");
        $('#edate').val("");
  
            if(leavetype == 'maternity')
            {
              $('#myModal2').modal("show");
            }           
                    $( "#reason" ).prop( "disabled", false );//enables the form fields
                    $( "#sdate" ).prop( "disabled", false);
                    $( "#edate" ).prop( "disabled", false );
                    $( "#location" ).prop( "disabled", false );
                    $( "#phone" ).prop( "disabled", false );
                    $( "#officer1" ).prop( "disabled",false );
                    $( "#officer2" ).prop( "disabled", false );
                    $( "#officer3" ).prop( "disabled", false );
                    $( "#apply" ).prop( "disabled", false );

              $.ajax({
                  type: "POST",
                  url: "leavedays.php",
                  daTatype: "json",
                  data: {leavetype:leavetype, staffid:staffid},
                  success: function(outp){
                      console.log(outp);
                      var res = outp.split('|');
                      var da = res[0];
                      var dg = res[1];
                      var dp = res[2];
                      $('#dp').val(dp);
                      $('#da').val(da);
                      $('#dg').val(dg);
            },
        });
//      }//end of else
    });

  //$( "#sdate" ).datepicker( "input", "dateFormat", "d-M-yy");
    $("#edate").change(function(ev){
      console.log($(this).val());

      ev.preventDefault();
      var sdate = $("#sdate").val();
      var edate = $("#edate").val();
      var leavetype = $("#leavetype").val();

      if (leavetype == "" || sdate == "") {
         $('#modalContent').html('<h5 class="stylo">Check if leave category or start date is not empty</h5>');
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
                        //alert(result.reason);

                        $('#modalContent').html('<h5 class="stylo">'+result.reason+'. Please select another set of dates.</h5>');
                        $('#myModal1').modal({backdrop: false, keyboard: false});

                        $( "#reason" ).prop( "disabled", true );//disables the form fields
                        $( "#location" ).prop( "disabled", true );
                        $( "#phone" ).prop( "disabled", true );
                        $( "#officer1" ).prop( "disabled", true );
                        $( "#officer2" ).prop( "disabled", true );
                        $( "#officer3" ).prop( "disabled", true );
                        $( "#apply" ).prop( "disabled", true );
                    }
                    if(result.status == 'neg')
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

                    if(result.status == 'ok'){
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

  $('#phone').change(function(){
      var phoneno = /^\d{11}$/;
      $( "#apply" ).prop( "disabled", false );
    if(!($(this).val().match(phoneno)))
    {
      $('#modalContent').html('<h5 class="stylo">Enter 11 digit phone number without space.</h5>');
      $('#myModal1').modal({backdrop: false, keyboard: false});
      $( "#apply" ).prop( "disabled", true );
    }
  });
    
  $("#apply").on('click', function(e){
    //alert("clicked");
    $('#apply').html("Loading....");
    
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
              officer3:officer3
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
                if (response == 'BEYOND LIMIT') 
                {
                  //alert("Beyond Limit");  
                  $('#leavedetail').html("<p>Leave Days Chosen is beyond the allowed limit</p>");  
                  $('#dataModal').modal("show"); 
                  $('#apply').html("Submit");
                }
            },
            error: function(){
              alert("error");
            }
        });
	  }//end of if else
  });
  
});
</script>
</html>