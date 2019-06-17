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
              if ($staffid == $hodid) {
                  echo "Pending Leave Application For ".$dept." Department";
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

    <p style="text-align: right;">
    <button>
          <a class="btn btn-small" style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Dashboard</a>
    </button><!-- 
      <button onclick="goBack()" class="btn btn-default">Back to dashboard</button> -->

  </p>
    
    <div class="row">     
          <table class="table-sm tbl">
       
<?php 

if(isset($_GET['id']))
{
  $id = base64_decode($_GET['id']);  
  if ($id == $hodid)//test for staff role
  {
      $stmt = $lvobj->getHodView($hodid, $dept, $cat); 
      $num = $stmt->rowCount();    
      
     #Table begins below
          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            echo "<th class='tdat'> Staff Name</th>";
            echo "<th> Application Date</th>";
            echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {

                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="leavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';//link to update record
                      echo "</td>";
                  echo "</tr>";
                 }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          echo "<tr>";
                    echo "<td colspan=\"13\"> No Staff in the department applied for leave yet</td>";
          echo "</tr>";
        }
      
       
     echo "</table>";
    echo "</div>";
}//end of if HOD id is set
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 #Dean or Director section

  elseif ($id == $deanid){
          
          $stmt = $lvobj->getDeanView($deanid, $kol, $cat); 
          
          $num = $stmt->rowCount();
            
         #Table begins below

          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            echo "<th class='tdat'> Staff Name</th>";
            echo "<th> Application Date</th>";
            echo "<th> Action</th>";
         echo "</tr>";


        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                    echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";
                                 
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="leavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';
                          //link to update record
                      echo "</td>";
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
  
  elseif ($id == $hro){
    //echo "HUMAN RESOURCES";
      $stmt = $lvobj->getHrView($hro); 
      $num = $stmt->rowCount();
    
       #Table begins below
          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            echo "<th class='tdat'> Staff Name</th>";
            echo "<th> Application Date</th>";
            echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";   
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="leavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';
                          //link to update record
                      echo "</td>";
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


  elseif ($id == $rego){
   // echo "REGISTRAR";

    $stmt = $lvobj->getRegView($rego); 
    $num = $stmt->rowCount();

       #Table begins below
          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
            echo "<th class='tdat'> Staff Name</th>";
            echo "<th> Application Date</th>";
            echo "<th> Action</th>";
         echo "</tr>";
 
        if ($num > 0) { //if starts here
          $n = 1;
          
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {

                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td>".$row['appno']."</td>";
                      echo "<td>".$row['leavetype']."</td>";
                      echo "<td>".$row['reason']."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";   
                      echo "<td>";
                      //view a single record
                      $appno = $row['appno'];
                      echo '<a href="leavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';
                          //link to update record
                      echo "</td>";
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

  elseif ($id == $vco){
   // echo "VICE CHANCELOR'S OFFICE";
    $stmt = $lvobj->getVcView($vco); 
    $num = $stmt->rowCount();

       #Table begins below

          echo "<tr>";
            echo "<th> No</th>";
            echo "<th> Appno</th>";
            echo "<th> Leave Type</th>";
            echo "<th class='tdat'> Reason</th>";
            echo "<th> Start Date</th>";
            echo "<th> End Date</th>";
            echo "<th> Days</th>";
            echo "<th> Location</th>";
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
                      echo "<td>".date('j M, Y', strtotime($row['recstartdate']))."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['recenddate']))."</td>";
                      echo "<td class='tdat'>".$lvobj->numdays($row['recstartdate'], $row['recenddate'])."</td>";
                      echo "<td>".$row['location']."</td>";
                      echo "<td>".$lvobj->getname($row['staffid'])."</td>";
                      echo "<td>".date('j M, Y', strtotime($row['datecreated']))."</td>";   
                      echo "<td>";
                          //view a single record
                      $appno = $row['appno'];
                      echo '<a href="leavedash.php?appno='.base64_encode($appno).'" class="btn btn-sm m-r-0em">Review</a>';
                          //link to update record
                      echo "</td>";
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
}//end of get variable       
?>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
<!-- 
<p style="text-align: right;">
    <button>
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php //echo base64_encode($_SESSION['staffid']); ?>">Dashboard</a>
        </button>
      <button onclick="goBack()" class="btn btn-default">Back to dashboard</button>

  </p> 
  End of table list -->
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