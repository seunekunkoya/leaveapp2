<?php

  include "../include/config.php";
  $staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));
  if(isset($_GET['newcoursecode']))
  {
		$coursecode=base64_decode($_GET['newcoursecode']);
        $selectedcoursecode=base64_decode($_GET['newselectedcoursecode']);
	    $lecturedate=base64_decode($_GET['newlecturedate']);
	    $permittype=base64_decode($_GET['newpermittype']);
	    $weekid=base64_decode($_GET['newweekid']);
        $weekname=base64_decode($_GET['newweekname']);
        $permitid=base64_decode($_GET['newpermitid']);
        $staffdetails = get_user($staffid);

        foreach($staffdetails as $stddetails)
        {
         $dept=$stddetails['deptunit'];
        }
?>

		<!DOCTYPE html>
			<html lang="en">
				<head>
					<title>Permit Application</title>

					<!-- BEGIN META -->
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<meta name="keywords" content="your,keywords">
					<meta name="description" content="Short explanation about this website">
					<!-- END META -->

					<!-- BEGIN STYLESHEETS -->
					<link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
					<link type="text/css" rel="stylesheet" href="../assets/css/theme-default/bootstrap.css?1422792965" />
					<link type="text/css" rel="stylesheet" href="../assets/css/theme-default/materialadmin.css?1425466319" />
					<link type="text/css" rel="stylesheet" href="../assets/css/theme-default/font-awesome.min.css?1422529194" />
					<link type="text/css" rel="stylesheet" href="../assets/css/theme-default/material-design-iconic-font.min.css?1421434286" />
			         <link rel="stylesheet" type="text/css" href="../css/upload.css">
					<!-- END STYLESHEETS -->
					<style>
					textarea
					{
				     width: 350px;
				     height: 70px;
				    }
				    #permitapp,#viewapp
				    {
					  left: 40%;
					  margin: 5px;
					  position: relative;
				    }
				    #labeldesign
				    {
				         width: 15%;
				         margin-left: 3px;
				         float: left;
				         text-align: left;
				         font-weight: bolder;
				         font-size: 15px;
				         color: #000;
				         opacity: 1;
				    }
			   </style>
			       <link type="text/css" href="../assets/jquery/themes/base/ui.base.css" rel="stylesheet" />
			      <link type="text/css" href="../assets/jquery/themes/base/ui.theme.css" rel="stylesheet" />
			      <script language="javascript" src="../assets/jquery/jquery.js" ></script>
			      <script language="javascript" src="../assets/js/jquery.form.min.js"></script>
			      <script language="javascript" src="../assets/jquery/ui/ui.core.js"></script>
			      <script language="javascript" src="../assets/jquery/ui/ui.datepicker.js"></script>
				</head>
				<body>

					<!-- BEGIN HEADER-->


					<div id="base">
						<div id="content">
							<section>
								<div class="section-body contain-sm">
			                      	    <h2 class="text-center">Lecture Attendance <?php echo ucfirst($permittype); ?> -Permit Application</h2>

										<div class="card">
										<div class="card-body">
											<form  id="form"  class="form-horizontal"  method= "POST" action=""  role="form" enctype="multipart/form-data" autocomplete="off">
									            	<p id="msg" class="text-danger">

									            	</p>
                                                    <input type="hidden" name="dept"  id="dept" value="<?php echo $dept;?>"  >
									            	<input type="hidden"  name="permit" id="permit" class="form-control permit" value="<?php echo $permittype; ?>" required disabled>
									            	<input type="hidden" name="coursecode"  id="coursecode" class="form-control"  value="<?php echo $coursecode; ?>"required disabled>
									            	<input type="hidden" name="weekname"  id="weekname" class="form-control"  value="<?php echo $weekname; ?>"required disabled>
			                                        <input type="hidden"  name="weekid" id="weekid" class="form-control weekid" value="<?php echo $weekid; ?>" required disabled>
			                                        <input type="hidden"  name="permittype" id="permittype" class="form-control permittype" value="<?php echo $permitid; ?>" required disabled>
													<div class="form-group">
											 			<label for="coursecode" id="labeldesign" class="col-sm-2 control-label">Course</label>
														<div class="col-sm-7">
							                				<input type="text" name="selectedcoursecode"  id="selectedcoursecode" class="form-control"  value="<?php echo $selectedcoursecode; ?>"required disabled>
							                        </div>

							              			</div>
							              			<div class ="form-group">
							              				<label for="lecturedate" id="labeldesign" class="col-sm-2 col-form-label">Lecture Date</label>
			                                               <div class="col-sm-2">
			                                                  <input type="text"  name="lecturedate" id="lecturedate" class="form-control lecturedate"value="<?php echo $lecturedate; ?>" required disabled>
			                                               </div>

			                                               <div class="col-sm-2">
			                                                  <p style="color:blue" ><?php echo $weekname;?></p>
			                                               </div>
							              			</div>


													<div class="form-group">
															<label for="Reason" id="labeldesign" class="col-sm-2 control-label">Reason</label>
														<div class="col-sm-6">
							                   				  <textarea name="reason" id="reason" required>
							                   				  </textarea>
							           					</div>
													</div>
												      	 <input type="button" name="permitapp"  id="permitapp" value="Save Application">
			                                              	<input type="button" name="viewapp"  id="viewapp" value="View" disabled>
									       	    </form>

			                            </div>

						            </div>
			                    </div>
			                </section>
			            </div>
			        </div>
						<!--end #content-->
						<!-- END CONTENT -->
						<!-- BEGIN MENUBAR-->


						<!--end #base-->
					<!-- END BASE -->
					<!-- BEGIN JAVASCRIPT -->
					<script src="../assets/js/libs/jquery/jquery-1.11.2.min.js"></script>
					<script src="../assets/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
					<script src="../assets/js/libs/bootstrap/bootstrap.min.js"></script>
					<script src="../assets/js/libs/spin.js/spin.min.js"></script>
					<script src="../assets/js/libs/autosize/jquery.autosize.min.js"></script>
					<script src="../assets/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
					<script src="../assets/js/core/source/App.js"></script>
					<script src="../assets/js/core/source/AppNavigation.js"></script>
					<script src="../assets/js/core/source/AppOffcanvas.js"></script>
					<script src="../assets/js/core/source/AppCard.js"></script>
					<script src="../assets/js/core/source/AppForm.js"></script>
					<script src="../assets/js/core/source/AppNavSearch.js"></script>
					<script src="../assets/js/core/source/AppVendor.js"></script>
					<script src="../assets/js/core/demo/Demo.js"></script>


					<!-- END JAVASCRIPT -->
				</body>
			</html>
			<script type="text/javascript">
				$(document).ready(function (e)
				{
				 	$('#viewapp').on('click', function ()
				 	{
			           var coursecode=$("#coursecode").val();
			           var lecturedate=$("#lecturedate").val();
			           var dept =$("#dept").val();
			           var selectedcoursecode =  $("#selectedcoursecode").val();
			           var newcoursecode = btoa(unescape(encodeURIComponent(coursecode)));
			           var newselectedcoursecode = btoa(unescape(encodeURIComponent(selectedcoursecode)));
			           var newlecturedate = btoa(unescape(encodeURIComponent(lecturedate)));
			           var newdepartment =   btoa(unescape(encodeURIComponent(dept)));
			           window.location.href="staffappreview.php?coursecode="+ newcoursecode +"&lecturedate=" + newlecturedate + "&dept=" + newdepartment + "&newselectedcoursecode="+ newselectedcoursecode;

			       });
			         $('#permitapp').on('click', function ()
			        {
			               var coursecode=$("#coursecode").val();
			               var weekid= $("#weekid").val();
			               var lecturedate=$("#lecturedate").val();
			               var permittype=$("#permittype").val();
			               var reason=$("#reason").val();
			               var dept =$("#dept").val(); 
							$.ajax({
										url:"ajaxpermitapplication.php",
										type:'POST',
										dataType: 'JSON',
										data:{coursecode:coursecode,permittype:permittype,lecturedate:lecturedate,reason:reason, dept:dept,weekid:weekid, dataname:'saveapplication'},
								        success: function(savestaffapplication)
			                         {     //alert(response);
						            	 if(savestaffapplication.recordcount)
						                {
						                    $('#msg').html('<div class="alert alert-info">'+ savestaffapplication.recordcount+'</div>');
						                    $("#viewapp").removeAttr('disabled','disabled');

						                }
						                else
						                {
				                              if(savestaffapplication.successfull)
						                   {
						            	    	$('#msg').html('<div class="alert alert-info">'+ savestaffapplication.successfull+'</div>');
						            	    	$("#viewapp").removeAttr('disabled','disabled');
						                   }
						                   else
						                   {
			                                  $('#msg').html('<div class="alert alert-info">'+ savestaffapplication.unsuccessfull+'</div>');
						                   }
						                }



					                },
					               error: function()
						            {
						            	alert('error in script');
						            }
					       });

					 });



				 });
			</script>
<?php
	 }
	 else
	 {
	 	echo "You are not permitted to view this page";
	 }