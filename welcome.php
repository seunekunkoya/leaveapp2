<?php
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Dashboard for staff to view application progress
File:      welcome.php

*/

include "leavefunction.php";
include "config/database.php";

 $staffname = "AAAB";
 $appno  = $_GET['id'] ? $_GET['id'] : header("Location:leavereg.php") ;

try {
        #Query to select leave details of the $this staff
        $query = "SELECT lt.appno, lt.tstaffid, lt.transactionid, lt.comment, lt.status, lt.recstartdate, lt.recenddate, l.staffid
                  FROM leavetransaction AS lt
                  INNER JOIN leaveapplication AS l
                  ON lt.tstaffid = l.staffid
                  WHERE lt.appno = '$appno'";

                 


        $stmt = $con->prepare($query);
        $stmt->execute();
        
        $num = $stmt->rowCount();
        
    }//end of try
    catch(PDOException $e){
         echo "Error: " . $e->getMessage();
    }
                         
?>
<!DOCTYPE html>
<html>
<head>
    <title>View leave Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style type="text/css">
        body{ font: sans-serif; }
    </style>
<body>
<div class="container">  
       <div class="row hed" >
            <div class="col-md-3"></div>
                <h4 class="h4">Staff Leave Application View </h4>
        </div>  
        <!-- End of title  -->
        
        
        <div class="row">
            <div class="col-md-1"></div>
            <table class="table-sm table-borderless">
                    <tr>
                        <th> No</th>
                        <th> App No</th>
                        <th> Track</th>
                        <th> Comment</th>
                        <th> Start Date</th>
                        <th> End Date</th>
                        <th>Days</th>
                        <th> Status</th>
                        <th> Action</th>
                    </tr>

            <?php 
                if ($num > 0) { //if starts here
                    $n = 1;
                    
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                     {
                       //extract row this truns array keys into variables
                       extract($row);
                       //create new row per record
                       echo "<tr>";
                          echo "<td>".$n++."</td>";
                          echo "<td>{$appno}</td>";
                          echo "<td>{$transactionid}</td>";
                          echo "<td>{$comment}</td>";
                          echo "<td>{$staffid}</td>";
                          //echo "<td>{$staffname}</td>";

                          $stdate = date_create($recstartdate);
                          echo "<td>".date_format($stdate, "d-M-Y")."</td>";
                          
                          $eddate = date_create($recenddate);
                          echo "<td>".date_format($eddate, "d-M-Y")."</td>";

                          echo "<td>".numdays($recstartdate, $recenddate)."</td>";

                          if ($status == 1) {
                                echo "<td><b>Submitted</b></td>";
                          } elseif ($status == 2) {
                                echo "<td>Not Yet Recommended</td>";
                          } else {
                                echo "<td>Application Not Recommended</td>";
                          }
                          
                          echo "<td>";
                              //view a single record
                          echo "<a href='leavedash.php?id={$appno}' class='btn btn-info btn-sm'>Edit</a>";
                              //link to update record
                          echo "</td>";
                     }//end of while loop
                }//end of if statement for printing results into tables 
                else {
                    echo "<tr>";
                    echo "<td colspan=\"5\"> No Staff Applied for leave yet</td>";
                    echo "</tr>";
                }
            ?> 
             
            </table>
            
        </div>
        <!-- End of table list -->
        <div class="row">
            
                        echo  '<p><a href="leavedashboard.php?appno='.$appno.'"  class="btn btn-default">Back to dashboard</a></p>'; 

              //echo '<a href="leavestatus.php?id='.base64_encode($id).'" class="btn btn-default">View Application Status</a>';

            ?>
        </div>
</div>

    <script src="js/jquery-slim.min.js"></script>
    <script src="../../dist/js/bootstrap.js"></script>
</body>
</html>
 
