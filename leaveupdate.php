<?php
include 'config/database.php';
include 'leavefunction.php';
		
if (isset($_GET['id']))
	{
		$staffid = $_GET['id'];
		$hodid = "cu/05/89";

		$name = getname($staffid);
		
		
    try {
    	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $qryh = "SELECT leavetype, reason, startdate, enddate, hodrec, hodcom
				FROM leavetrack
				JOIN leaveapp ON leavetrack.staffid = leaveapp.staffid
				WHERE leavetrack.staffid = '$staffid'
				LIMIT 0,1"; 
		$stmth = $con->prepare($qryh);
		$stmth->bindParam(1, $staffid);
		$stmth->execute();

		$row=$stmth->fetch(PDO::FETCH_ASSOC);
	
		
		$leavetype = $row['leavetype'];
		$reason = $row['reason'];
		$startd = date('j F, Y', strtotime($row['startdate']));
		$endd = date('j F, Y', strtotime($row['enddate']));

		//$date = date('j F, Y', strtotime($row['date']));
		
    }//end of try
    catch(PDOException $e){
    	 echo "Error: " . $e->getMessage();
    }//end of catch

}//End of GET

//Begin update of leavetrack below
 if(isset($_POST['submit']))
 {
 	

 	$com = htmlspecialchars(strip_tags($_POST['comm']));
	$rec = htmlspecialchars(strip_tags($_POST['rec']));
	$hoddate = date('Y-m-d');
	//echo $com."<br>";
	//echo $rec;

	try{

        $qryu = "UPDATE leavetrack
                 SET hodcom=:com, hodrec=:rec, hodid=:hodid, hoddate =:hoddate
                 WHERE staffid = :staffid";

        // prepare query for excecution
        $stmtu = $con->prepare($qryu);

        // bind the parameters
        $stmtu->bindParam(':hodid', $hodid);
        $stmtu->bindParam(':com', $com);
        $stmtu->bindParam(':rec', $rec);
        $stmtu->bindParam(':hoddate', $hoddate);
        $stmtu->bindParam(':staffid', $staffid);

        // Execute the query
        if($stmtu->execute()){
            echo "<div class='alert alert-success'>Recommendation submited.</div>";
        }else{
            echo "<div class='alert alert-danger'>Unable to give recommendations at the moment. Please try again</div>";
        }

    }

    // show errors
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }

 }//end of update
?>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> -->
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous">
</script>

<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
  #space{
    padding-top: 100px;
  }
</style>

<div></div>
  <div id="space" class="row">
    <div class="col-md-6 col-md-offset-2">
      <form class="form-horizontal" role="form" action="" method="POST">
        <fieldset>

          <!-- Form Name -->
          <legend>Leave Update</legend>
      <div>
        <p id="message"></p>
      </div>
          <!-- Staff Name-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Staff Name</label>
            <div class="col-sm-9">
             <input type="text" placeholder="Staff Name" class="form-control">
            </div>
          </div>

          <!-- Reason for Leave-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Leave Reason</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="reason" id="reason" rows="5" cols="40" placeholder="Reasons for Leave" required></textarea>
            </div>
          </div>
          
          <!-- Leave Type-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Leave Type</label>
            <div class="col-sm-9">
               <select name="leavetype" class="form-control" id="leavetype" required>
                          <option value = ''></option>
                          <option value = 'casual'>Casual</option>
                          <option value = 'annual'>Annual</option>
                          <option value = 'medical'>Medical</option>
                          <option value = 'study'>Study</option>
                          <option value = 'post_doc'>Post Doctoral</option>
              </select>
            </div>
            </div>

            <!-- Comment-->
          <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Comment</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="reason" id="reason" rows="5" cols="40" placeholder="Comment here" required></textarea>
            </div>
          </div>

            <!--Recommendation-->
               <div class="form-group">
            <label class="col-sm-3 control-label" for="textinput">Recommendation</label>
            <div class="col-sm-9">
              <select name="recommendation" class="form-control" required>
                          <option value = ''></option>
                          <option value = 'Approved'>Approved</option>
              </select>
            </div>
          </div>
    </fieldset>
      </form>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary" id="apply">Send</button>
              </div>
            </div>
          </div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->


<script type="text/javascript">
  $(function(){
  
});
</script>

  