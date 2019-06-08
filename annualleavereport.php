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
  
  $cursession = $lvobj->getSession();


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
  <script src="js/table2excel.js"></script>
<style>
 .btnn{
  margin-top: 20px;
}
.btnn a{
  color: white;
}
.container{
  width: 1600px;
}
#btncapsule{
  padding-left: 180px;
  margin-right: 0px;
}
</style>

<body>
<div class="container">
    <div class="row">
      <div class="col-md-6">
        <h3 class="h3">
          <?php echo $cursession; ?> Annual Leave Schedule
        </h3>
      </div>
      <div class="col-md-3"></div>
      <div class="col-md-3" id="btncapsule">
        <button type="button" class="btn-primary btnn" id="export">Export to Excel</button>
        <button class="btn-primary btnn">
          <?php 
              echo '<a style="font-size: 14px;" href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'">Dashboard</a>'; 
          ?>
        </button>
      </div>
        
    </div>  
    <!-- End of title  -->
    
    <div class="row">  
      <table class="table table-bordered table-condensed" id="leaveschedule">
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
            echo "<th> Category</th>";
            echo "<th> College/Directorate</th>";
            echo "<th> Department</th>";
            echo "<th> Unit/Program</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Title</th>";
            echo "<th> Staffid</th>";
            echo "<th> Post</th>";
            echo "<th> CUSS</th>";
            echo "<th> Employment Resumption Date</th>";
            echo "<th> Days Worked in the Year</th>";
            echo "<th> Days Entitled</th>";
            echo "<th> Days Already Taken</th>";
            echo "<th> Days Permissible</th>";
            echo "<th> Leave Bonus</th>";
            echo "<th> Bank Account</th>";
            echo "<th> Bank Name</th>";
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
                      echo "<td>".$row['category']."</td>";
                      echo "<td>".$row['kol']."</td>";  
                      echo "<td>".$row['dept']."</td>";
                      echo "<td>".$row['unitprg']."</td>";   
                      echo "<td>".$row['staffname']."</td>";//$staffid = getname($row['staffid'])
                      echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".$row['post']."</td>";
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
                      echo "<td colspan=\"15\"><b>TOTAL LEAVE BONUS IN NAIRA</b></td>";
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
      $(document).ready(function () {
        $('#export').click(function(){
            $("#leaveschedule").table2excel({
                filename: "leaveschedule.xls"
            });
        });
      });
        
</script>
</body>
</html>