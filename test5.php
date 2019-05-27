<?php 
  include 'config/database.php';
  include 'leavefunction.php';

  checksession();

  $hro = $_SESSION['staffdetails']['hro'];
  $rego = $_SESSION['staffdetails']['rego'];
  $vco = $_SESSION['staffdetails']['vco'];
  
  		$cursession = '2018/2019';

		$query = "SELECT *
          FROM leaveschedule
          WHERE session = '$cursession'";

        $stmt = $con->prepare($query);
        $stmt->execute();  

        $num = $stmt->rowCount();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/sc-2.0.0/datatables.min.css"/>
 	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/sc-2.0.0/datatables.min.js"></script>
 	
 	<script type="text/javascript">
	$(document).ready(function() {
    		$('#example').DataTable( {
        		"scrollY": 200,
        		"scrollX": true,
        		"searching": false,
        		"ordering": false,
        		"paging": false,
        		"info": false
    		} );
	} );
</script>
<style type="text/css">
	div.dataTables_wrapper {
        width: 1100px;
        margin: 0 auto;
    }

    .container .head{
    	margin-left: 300px;
    	padding-bottom: 20px;
    }
    .pad{
    	padding-top: 10px;
    	padding-bottom: 10px;
    }
    .btn_style{
    	margin-top: 32px;
    	border-radius: 4px;
    	width: 50%;
  		height: 30px;
    }
    .hr_send_btn{
    	margin-top: 10px;
    	border-radius: 4px;
    	width: 50%;
  		height: 40px;
  		font-weight: bold;
  		font-size: 15px;

    }
    table#table_style{
    	margin-left: 60px;
    	margin-top: 20px;
    }
    textarea {
	  width: 100%;
	  height: 50px;
	  padding: 12px;
	  box-sizing: border-box;
	  border: 2px solid #ccc;
	  border-radius: 4px;
	  background-color: #f8f8f8;
	  resize: none;
	}
	select#reccom {
	  width: 100%;
	  padding: 15px 10px;
	  border-radius: 4px;
	  background-color: #f1f1f1;
	}
    
</style>
</head>
<body>
<div class="container">
<h2 class="head">
  <?php echo $cursession; ?> Annual Leave Schedule
</h2>

<table id="example" class="display nowrap" style="width:100%">
		
	<thead>
		<tr>
            <th> No</th>
            <th> Staff Name</th>
            <th> Title</th>
            <th> Staffid</th>
            <th> Post</th>
            <th> Proram/ Unit</th>
            <th> Level</th>
            <th> Resumption Date</th>
            <th> Days Worked in the Year</th>
            <th> Days Entitled</th>
          	<th> Days Already Taken</th>
            <th> Days Permissible</th>
            <th> Leave Bonus</th>
            <th> Bank Account</th>
            <th> Bank Name</th>
        </tr>
    </thead>
    <?php 
    if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                    $empdate = strtotime($row['empdate']);
                   //extract row this truns array keys into variables
                   //extract($row);
                   //create new row per record
                   echo "<tr>";
                      echo "<td>".$n++."</td>";     
                      echo "<td>".$row['staffname']."</td>";//$staffid = getname($row['staffid'])
                      echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".$row['post']."</td>";
                      echo "<td>".$row['progunit']."</td>";
                      echo "<td>".$row['level']."</td>";
                      echo "<td>".date('j M, Y', $empdate)."</td>";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                     
                      $today = date('Y-m-d');
                    
                        if((int)numdays($row['empdate'], $today) > 365)
                        {
                          echo "<td>365</td>";
                        }
                        else
                        {
                          echo "<td>".
                                   numdays($row['empdate'], $today).
                                "</td>";
                        }
                         
                      echo "<td>".$row['daysentitled']."</td>";
                      
                      echo "<td>".$row['daysgone']."</td>";
                      echo "<td>".$row['dayspermissible']."</td>";
                      echo "<td>". $row['leavebonus']."</td>";
                      echo "<td>".$row['bankacctno']."</td>";
                      echo "<td>".$row['bankname']."</td>";
                      //echo "<td>";
                      //     //view a single record
                      // echo '<button><a href="leavedash.php?edit='.$row['staffid'].'" class="btn btn-sm m-r-0em">Edit</a></button>';
                      // echo "</td>";
                      // echo "<td><button>Remove</button></td>";
                  echo "</tr>";

                 }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
                    echo "<td colspan=\"14\"> No Staff Applied for leave yet</td>";
          echo "</tr>";
        }
    ?>
    </tbody>
</table>
</div><!--End of container -->

<div class="container pad">
	<div class="row pt-5">
		<div class="col-sm-6"><button class="btn_schedule">Print Schedule</button></div>
		<div class="col-sm-3">
			
		</div>
		<div class="col-lg-3">
			<table id="table_style">
				<tr>
					<th>Total Leave Bonus</th>
					<td>1000000</td>
				</tr>
			</table>
		</div>
		
	</div>
</div>

<div class="container">
	<?php if(!$hro)	{ ?>

	<div class="row">
		<div class="col-sm-3">
			
		</div>
		<div class="col-sm-8">
		  <div class="row">
		  	<div class="col-sm-4">
		  		<label>Registrar's Comment</label>
		  		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod </p>
		  	</div>
		  	<div class="col-sm-3">
		  		<label>Recommendation</label>
		  		<p>Lorem ipsum. </p>
		  	</div>
		  	
		  </div>
		</div>
		<div class="col-sm-2">
			
		</div>
	</div>

	<hr>
	<?php 	} //end of if ?>
	<!-- -------------------------------------------------------------------------------------------------------- -->
	<div class="row">
		<?php 
				if($hro)
				{
					//echo "It is HR";
		          $send_btn = '
					<div class="col-sm-4"> </div>
					<div class="col-sm-8">
				  		<div class="row">
				  			<div class="col-sm-12">	  			
				  				<button class="hr_send_btn">Send Schedule Notification</button>
				  			</div>
				  		</div>
					</div>
					<div class="col-sm-1"> </div>
			  		';

			  	  echo $send_btn;
			    }

			    else {

		?>
				<div class="col-sm-3"> </div>
				<div class="col-sm-8">
				  <div class="row">
				  	<div class="col-sm-4">
				  		<label>Comment</label>
				  		<textarea></textarea>
				  	</div>
				  	<div class="col-sm-3">
				  		<label>Recommendation</label>
				  		<select id="reccom">
				  			<option>Recommendation</option>
				  		</select>
				  	</div>
				  	  	<div class="col-sm-3">
				  		<button class="btn_style">Send</button>
				  		</div>
				  </div>
				</div>

				<div class="col-sm-1">
					
				</div>
	<?php } //end of else?>
	</div>
	
</div>
    
</body>
</html>