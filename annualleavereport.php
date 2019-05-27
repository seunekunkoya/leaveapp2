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

  $hro = $_SESSION['staffinfo']['hro'];
  $rego = $_SESSION['staffinfo']['rego'];
  $vco = $_SESSION['staffinfo']['vco'];
  
  $cursession = '2018/2019';

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /*Query to check if the schedule for the current session exists*/
  $stmt = $lvobj->isExistSchedule($cursession);
  $nm = $stmt->rowCount();
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html>
<head>
  <title>Leave Schedule</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="js/datepicker.js"></script>
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

.modal-center {
       position: absolute;
       top: 250px;
       right: 0px;
       bottom: 0;
       left: 0;
       z-index: 10040;
       overflow: auto;
       overflow-y: auto;
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

<body>
<div class="container">
    <div class="row hed" >
      <div class="col-md-4"></div>
        <h3 class="h3">
          <?php echo $cursession; ?> Annual Leave Schedule
        </h3>
    </div>  
    <!-- End of title  -->
    
    <div class="row">

      <div class="col-md-2"></div>      
           <table class="table table-bordered table-condensed">
<?php 

    try 
      {
        #Query to select leave details of the $this staff
        
        $stmt = $lvobj->getAnnualLeaveSchdule();
        $num = $stmt->rowCount();
        
       }//end of try
       catch(PDOException $e){
         echo "Error: " . $e->getMessage();
       }//end of catch

       #Table begins below

          echo "<tr align ='center'>";
            echo "<th> No</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Title</th>";
            echo "<th> Staffid</th>";
            echo "<th> Post</th>";
            echo "<th> Program/ Unit</th>";
            echo "<th> Salary Level</th>";
            echo "<th> Resumption Date</th>";
            echo "<th> Days Worked in the Year</th>";
            echo "<th> Days Entitled</th>";
            echo "<th> Days Already Taken</th>";
            echo "<th> Days Permissible</th>";
            echo "<th> Leave Bonus</th>";
            echo "<th> Bank Account</th>";
            echo "<th> Bank Name</th>";
            //echo "<th> Action</th>";
         echo "</tr>";
          $schedule = array();
        if ($num > 0) { //if starts here
          $n = 1;
          $totalbonus = 0;
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {             
                  $schedule [] = $row;
                  $empdate = strtotime($row['employmentdate']);
                   //extract row this truns array keys into variables
                   //extract($row);
                   //create new row per record
                   echo "<tr>";
                      echo "<td>".$n++."</td>";     
                      echo "<td>".$row['staffname']."</td>";//$staffid = getname($row['staffid'])
                      echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".$row['post']."</td>";
                      echo "<td>".$row['dept']."</td>";
                      echo "<td>".$row['level']."</td>";
                      echo "<td>".date('j M, Y', $empdate)."</td>";
                      echo "<td>".$row['daysworked']."</td>";
                      echo "<td>".$row['daysentitled']."</td>";
                      echo "<td>".$row['daysgone']."</td>";
                      echo "<td>".$row['permissibledays']."</td>";
                      echo "<td>".number_format($row['leavebonus'], 2)."</td>";
                      echo "<td>".$row['bankacct']."</td>";
                      echo "<td>".$row['bankname']."</td>";
                  echo "</tr>";

                  $totalbonus += $row['leavebonus'];
                }//end of while loop
                   echo "<tr>";
                      echo "<td colspan=\"12\"><b>TOTAL LEAVE BONUS IN NAIRA</b></td>";
                      echo "<td>".number_format($totalbonus,2)."</td>";
                      echo "<td></td>";
                      echo "<td></td>";
                   echo "</tr>";
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
              echo "<td colspan=\"15\"> No Staff Applied for leave yet</td>";
          echo "</tr>";
        }

     echo "</table>";
    echo "</div>";
 //number_format("1000000",2)
    //print_r(getname($schedule[0]['staffid']));
    //echo count($schedule);

    if(isset($_GET['schedule']) == 1 ){

            $transactionDate = date('Y-m-d');
            $transaactionNo = transactionNo();

            $officer = "HR";
            $recc = "Presented";
            $comment = "Presented";
            $action = "Presented";

            $lvobj->insertLeaveScheduleTransaction($transactionDate, $transaactionNo, $cursession, $officer, $recc, $comment, $action);  

        if($lvobj->insertLeaveSchedule($schedule[], $cursession))
        {
          //header('Location: annualleavereport3.php');
          echo '<script> location.replace("annualleavereport3.php"); </script>';
        }
        else{
          echo "<p>Schedule not generated. Please try again.</p>";
        }
    }
?>

    <!-- End of table list -->
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>

  <p style="text-align: right;">
    <?php
       if ($nm > 0)//session exist
       {
        // echo ' ';
         echo '
              <button>
                    <a style="font-size: 14px;" href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'">Dashboard</a>
              </button>
              ';
       } 
       else 
       {
         echo '<button class="btn"><a href="annualleavereport.php?schedule=1">Generate Schedule</a></button>';
         echo '
              <button>
                    <a style="font-size: 14px;" href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'">Cancel</a>
              </button>
              ';
       }
    ?><!-- 
    <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php //echo base64_encode($_SESSION['staffdetails']['staffid']); ?>">Cancel</a>
    </button>
      <button onclick="goBack()" class="btn btn-default">Back to dashboard</button> --> 

  </p>
</div>

   <script>
      $("#generate").click(function(){
        //console.log("generate");
          alert("generate");
      });
</script>
</body>
</html>