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
<!DOCTYPE html>
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
         echo "Overstayed Leave List";
      ?> 
        </h3>
    </div>  
    <!-- End of title  -->
</div>

    
<div class="container">
    <div class="row">
      
          <table class="table-sm ">
       
<?php 

if(isset($_GET['id']))
{
  $id = base64_decode($_GET['id']);  
    
     try 
      {
        #Query to select leave details of the $this staff
        $stmt = $lvobj->getOverstayedStaff();
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
            echo "<th> Action</th>";
         echo "</tr>";

         $today = date('Y-m-d');
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
            {
              $resdt =$lvobj->resumptionday($row['apenddate']);

              if($today > $resdt){
                  
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
                      //echo "<td>".$row['status']."</td>";
                      
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="overstayeddash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">View Details</a>';
                          //link to update record
                      echo "</td>";
                  echo "</tr>";
                # code...
              }//end of resumption
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
    <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Dashboard</a>
        </button>
      <!-- <button onclick="goBack()" class="btn btn-default">Back to dashboard</button> -->
  </p>
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