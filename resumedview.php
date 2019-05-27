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

//  echo $_SESSION['category'];
  $staffid = $_SESSION['staffdetails']['staffid'];
  $dept = $_SESSION['staffdetails']['dept'];
  $kol = $_SESSION['staffdetails']['kol'];
  $cat = $_SESSION['staffdetails']['category'];
  $hodid = $_SESSION['staffdetails']['hod'];
  $deanid = $_SESSION['staffdetails']['dean'];
  $hro = $_SESSION['staffdetails']['hro'];
  $rego = $_SESSION['staffdetails']['rego'];
  $vco = $_SESSION['staffdetails']['vco'];
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
              if ($staffid == $hodid) {
                  echo "List of Resumed Staff in ".$dept." Department";
              } 
              elseif ($staffid == $deanid)
              {
                echo "Pending Leave Application For ".$kol;
              //kol is the directorate or college based on staff category. 
              }//end of if statement 
              elseif ($staffid == $hro)
              {
                echo "Pending Leave Application For HR"; 
              }//end of if statement
              elseif ($staffid == $rego)
              {
                echo "Leave Application List";
              }//end of if statement
              elseif ($staffid == $vco)
              {
                echo "Leave Application List";
              }//end of if statement
          
          ?> 
        </h3>
    </div>  
    <!-- End of title  -->
    
    <div class="row">
      <div class="col-md-3"></div>
          <table class="table-sm ">
       
<?php 

if(isset($_GET['id']))
{
  $id = base64_decode($_GET['id']);  
  if ($id == $hodid)//test for staff role
  {             
  try 
      {
        #Query to select the details of staff who has indicated resumption from leave.
        #The query uses the status in leave transaction table to fetch it result. 
        #The result is usually
          $query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          INNER JOIN approvedleaves AS ap
          ON ap.appno = lt.appno
          WHERE st.dept = '$dept' 
          AND st.staffid != '$hodid' 
          AND lt.status = 'Resumed'
          AND lt.role = 'Applicant'
          AND lt.remarks = ''
          AND ap.resumeddate = ''
          AND st.category = '$cat'
          ORDER BY lt.timeviewed DESC";

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
            echo "<th> Action Date</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            //echo "<th> Remarks</th>";
            echo  "<th> Status</th>";
            echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                  $newArray[] = $row;

                  // if ($newArray['tstaffid'] == 'CU0055')
                  // {
                  //   continue
                  // }

                   //extract row this truns array keys into variables
                   //extract($row);
                   //create new row per record
                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".date('j M, Y - h:i:s', strtotime($row['timeviewed']))."</td>";
                      echo "<td>".getname($row['staffid'])."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['startdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['enddate']))."</td>";
                      echo "<td>".numdays($row['startdate'], $row['enddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      //echo "<td>".$row['remarks']."</td>";
                      echo "<td>".$row['status']."</td>";
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="resumptionconfirmdash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';//link to update record
                      echo "</td>";
                  echo "</tr>";
                 }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
                    echo "<td colspan=\"13\"> No Staff has indicated Resumption</td>";
          echo "</tr>";
        }
      
       
     echo "</table>";
    echo "</div>";
}//end of if HOD id is set
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}//end of get variable       
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