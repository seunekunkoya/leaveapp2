<?php
# Check for department of the viewing personnel
    //get staff name
    //get staff department
    //link staff to supervisors

        //After testing for department
        //This page gets approved leave application of staff
        /*************************************************************************/
  include "include/config.php";

  $lvobj->checkSession();

  $staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

  $staffdetails = $lvobj->staffInfo($staffid);
  $_SESSION['staffinfo'] = $staffdetails;

//print_r($staffdetails);

  $level = $_SESSION['staffinfo']['level'];

  $id = base64_decode($_GET['id']);
  $cat = $_SESSION['staffinfo']['category'];

  $dept = $_SESSION['staffinfo']['dept'];
  $kol = $_SESSION['staffinfo']['kol'];
  $cat = $_SESSION['staffinfo']['category'];
  $hodid = $_SESSION['staffinfo']['hod'];
  $deanid = $_SESSION['staffinfo']['dean'];
  $hro = $_SESSION['staffinfo']['hro'];
  $rego = $_SESSION['staffinfo']['rego'];
  $vco = $_SESSION['staffinfo']['vco'];
?>

<html>
<head>
  <title>View Resumed staff</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="js/table2excel.js"></script>
<body>
<div class="container">
    <div class="row hed" >
      <div class="col-md-5"></div>
      <h3 class="h3"><?php echo "Resumed Leave List";?> </h3>
    </div>  
    <!-- End of title  -->
    <div class="row btnbtn">
        <?php 
            echo '<button class="btn" id="export">Export to Excel</button>';
            echo '<a href="leavedashboard.php?id='.base64_encode($_SESSION['staffid']).'"><button class="btn">Dashboard</button></a>';
        ?>
    </div>
</div>
    
<div class="container">
    <div class="row">
      <div class="col-md-3"></div>
          <table class="table-sm" id="resumelist">
       
<?php 

if(isset($_GET['id']))
{
  $id = base64_decode($_GET['id']);  
    
     try 
      {
        #Query to select leave details of the $this staff
          $stmt = $lvobj->getResumedStaff();
          $num = $stmt->rowCount();
        
       }//end of try
       catch(PDOException $e){
       echo "Error: " . $e->getMessage();
       }//end of catch

       #Table begins below

          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Dept</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            echo  "<th> Phone</th>";
            echo  "<th> Release Date</th>";
            echo  "<th> Resumption Date</th>";
            // echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                   //extract row this truns array keys into variables
                   //extract($row);
                   //create new row per record
                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      //echo "<td>".date('j M, Y - h:i:s', strtotime($row['timeviewed']))."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".$row['dept']."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                     // echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['apstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['apenddate']))."</td>";
                      echo "<td>".$lvobj->numdays($row['apstartdate'], $row['apenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$row['phone']."</td>";
                      echo "<td>".$row['releaseddate']."</td>";
                      echo "<td>".$row['resumeddate']."</td>";
                      
                  echo "</tr>";
                 }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
                    echo "<td colspan=\"14\"> No Staff Applied for leave yet</td>";
          echo "</tr>";
        } 
     echo "</table>";
    echo "</div>";
  }       
       
?>


    <!-- End of table list -->
    <div>&nbsp;</div>

  <p style="text-align: right;">
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>"><button class="btn">Dashboard</button></a>
  </p>
</div>
  <script>
  $(document).ready(function () {
     $('#export').click(function(){
        $("#resumelist").table2excel({
            filename: "resumedlist.xls"
        });
      });
  });      
</script>
</body>
</html>