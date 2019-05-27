<?php
# Check for department of the viewing personnel
    //get staff name
    //get staff department
    //link staff to supervisors

    //After testing for department
    //This page gets leave application of staff based on department
  /*************************************************************************/

  include 'config/database.php';
  include 'leavefunction.php';

  checksession();

  $hro = $_SESSION['staffdetails']['hro'];
  $rego = $_SESSION['staffdetails']['rego'];
  $vco = $_SESSION['staffdetails']['vco'];
  
  $cursession = '2018/2019';
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
      <div class="col-md-4"></div>
        <h3 class="h3">
          Casual Leave Report
        </h3>
    </div>  
    <!-- End of title  -->
    
    <div class="row">

      <div class="col-md-2"></div>      
          <table class="table-sm ">
       
<?php 

    try 
      {
        #Query to select leave details of the $this staff
        $query = "SELECT st.staffid, st.fname, st.sname, st.title, st.post, st.level, st.dept, st.employmentdate, ap.apstartdate, ap.apenddate, ap.session
          FROM stafflst AS st
          INNER JOIN approvedleaves AS ap
          ON st.staffid= ap.staffid
          WHERE ap.leavetype = 'casual'
          AND ap.resumed = 1
          AND ap.session = '$cursession'";

        $stmt = $con->prepare($query);
        $stmt->execute();  

        $num = $stmt->rowCount();
        
       }//end of try
       catch(PDOException $e){
         echo "Error: " . $e->getMessage();
       }//end of catch

       #Table begins below

          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Staffid</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Title</th>";
            echo "<th> Post</th>";
            echo "<th> Proram/ Unit</th>";
            echo "<th> Level</th>";
            echo "<th> Employment Date</th>";
            echo "<th> Days Worked</th>";
            echo "<th> Days Entitled</th>";
            echo "<th> Days Gone</th>";
            echo "<th> Days Permissible</th>";
            //echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                  $empdate = strtotime($row['employmentdate']);
                   //extract row this truns array keys into variables
                   //extract($row);
                   //create new row per record
                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".getname($row['staffid'])."</td>";
                      echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['post']."</td>";
                      echo "<td>".$row['dept']."</td>";
                      echo "<td>".$row['level']."</td>";
                      echo "<td>".date('j M, Y', $empdate)."</td>";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                     
                      $today = date('Y-m-d');
                    
                    if((int)numdays($row['employmentdate'], $today) > 365)
                    {
                      echo "<td>More than 365</td>";
                    }
                    else{

                      echo "<td>".
                               numdays($row['employmentdate'], $today).
                      "</td>";
                      
                    }
                      
                      echo "<td> 7 days</td>";
                      $leavedaysgone = casualleavedaysgone($row['staffid'], $cursession);
                      echo "<td>".$leavedaysgone."</td>";

                      
                      $leaveallowed = (int)leavedaysallowed($row['staffid'], 'casual');;//total number of days allowed for any staff
                      $ndays = numdays($row['apstartdate'], $row['apenddate']);

                      $dayspermissible = $leaveallowed - $leavedaysgone;
                      echo "<td>".$dayspermissible."</td>";
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
  
    
?>


    <!-- End of table list -->
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>

  <p style="text-align: right;">
    <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffdetails']['staffid']); ?>">Dashboard</a>
        </button><!-- 
      <button onclick="goBack()" class="btn btn-default">Back to dashboard</button> -->

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