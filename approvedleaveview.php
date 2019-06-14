<?php
# Check for department of the viewing personnel
    //get staff name
    //get staff department
    //link staff to supervisors

        //After testing for department
        //This page gets approved leave application of staff
        /*************************************************************************/

  include "include/config.php";

  //checksession();
  $lvobj->checkSession();
  $staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

  $staffdetails = $lvobj->staffInfo($staffid);
  $_SESSION['staffinfo'] = $staffdetails;


  $staffid = $_SESSION['staffid'];
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
  <title>View leave Page</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
<body>
<div class="container">
    <div class="row hed" >
      <div class="col-md-3"></div>
      <h3 class="h3">
      
      <?php 
         echo "Approved Leave List";
      ?> 
        </h3>
    </div>
</div> 
    <!-- End of title  -->

<div class="container">
    <div class="row">
      <div class="col-md-1"></div>
          <table class="table-sm ">
       
<?php 

if(isset($_GET['id']))
{
  $id = base64_decode($_GET['id']);  
    
     try 
      {
        #Query to select leave details of the $this staff
        $stmt = $lvobj->getApprovedLeaves();  
        $num = $stmt->rowCount();
        
       }//end of try
       catch(PDOException $e){
       echo "Error: " . $e->getMessage();
       }//end of catch

       #Table begins below

          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th class='tdat'> Location</th>";
            echo "<th class='tdat'> Staff Name</th>";
            echo "<th> Application Date</th>";
            echo "<th> Action</th>";
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
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['apstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['apenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['apstartdate'], $row['apenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";
                      //echo "<td>".$row['dept']."</td>";                                          
                      //echo "<td>".$row['phone']."</td>";
                      //echo "<td>".$row['releaseddate']."</td>";
                      //echo "<td>".$row['status']."</td>";
                      
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="approvedleavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Release</a>';
                          //link to update record
                      echo "</td>";
                  echo "</tr>";
                 }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
                    echo "<td colspan=\"14\"> Waiting for leaves to be approved.</td>";
          echo "</tr>";
        } 
     echo "</table>";
    echo "</div>";
  }       
       
?>


    <!-- End of table list -->
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>

  <p style="text-align: right;">
      <button class="btn btn-default">
          <a class="btn btn-small" style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Dashboard</a>
      </button>

  </p>
</div>

</div>


  <script src="js/jquery-slim.min.js"></script>
    <script src="../../dist/js/bootstrap.js"></script>
    <script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>