<?php
# Check for department of the viewing personnel
		//get staff name
		//get staff department
		//link staff to supervisors

        //After testing for department
        //This page gets leave application of staff based on department
        /*************************************************************************/

include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

print_r($staffdetails);

$level = $_SESSION['staffinfo']['level'];

$id = base64_decode($_GET['id']);
$cat = $_SESSION['staffinfo']['category'];
		
	if(isset($_GET['id']))
	{
		$id  = base64_decode($_GET['id']);
		//$unitprgid = getunitprgid($id);	//$unitprgid is the variable for unit or programs depending on the category of staff.
		$statusStmt = $lvobj->getStatus($id, $cat);
		$statusStmt->execute();
		$num = $statusStmt->rowCount();		
	}
	else{
		$lvobj->redirect("leavedashboard.php?id=".base64_encode($id)."");
	}
	//end of id	
?>



<html>
<head>
	<title>View leave Page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="js/table2excel.js"></script>
<body>
<div class="container">

		<div class="row hed">
			<div class="col-md-2"></div>
			<h3 style="text-align: center;">Application Status View for <?php echo $lvobj->getname($staffid);?></h3>
		</div>	
		<!-- End of title  -->
		
	<div class="row btnbtn">
        <?php 
            echo '<button class="btn" id="export">Export to Excel</button>';
            echo '<a href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'"><button class="btn">Dashboard</button></a>';
        ?>
    </div>
	<div class="row">
            <table class="table-sm tbl" id="status">
					<tr>
						<th> No</th>
						<th> App No</th>
						<th> Track No</th>
						<th> Leave Type</th>
						<th> Reason</th>
						<th> Start Date</th>
						<th> End Date</th>
						<th> Days</th>
						<th> Date Applied</th>
						<th> Staff Name</th>
						<th> Role</th>
						<th> Remark</th>
						<th> Status</th>
					</tr>

			<?php 
			if($num > 0 ){
					$n = 1;
				while($row = $statusStmt->fetch(PDO::FETCH_ASSOC))         
		        {
		           echo "<tr>";
		                  echo "<td>".$n++."</td>";
		                  echo "<td>".$row['appno']."</td>";
		                  echo "<td class='tdat'>".$row['transactionid']."</td>";
		                  echo "<td>".$row['leavetype']."</td>";
		                  echo "<td>".$row['reason']."</td>";
		                  echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
		                  echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
		                  echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
		                  echo "<td>".date('j M, Y - h:i:s', strtotime($row['datecreated']))."</td>";
		                  echo "<td>".$lvobj->getname($row['tstaffid'])."</td>";
		                  echo "<td>".$row['role']."</td>";
		                  echo "<td>".$row['remarks']."</td>";
		                  echo "<td>".$row['status']."</td>";	                  
		                  //echo "<td>".$row['tstaffid']."</td>";
		                  
		           echo "</tr>";
		             }//end of while loop
                }//end of if statement for printing results into tables 
			else {
					echo "<tr>";
					echo "<td colspan=\"13\"> No application in progress yet.</td>";
					echo "</tr>";
				}
			//}
				
			?> 
			 
			</table>
		</div>
		<!-- End of table list
		<p style="text-align: center;"><b> Rec = Reccommended </b></p> -->
		<div>&nbsp;</div>
  		<p style="text-align: right;">
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>"><button class="btn">Dashboard</button></a>
 		 </p>
</div>
		
</div>
<script>
  $(document).ready(function () {
     $('#export').click(function(){
        $("#status").table2excel({
            filename: "status.xls"
        });
      });
  });      
</script>
</body>
</html>