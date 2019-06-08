<?php 

  include "include/config.php";

  //checksession();
  $lvobj->checkSession();
  $staffid = $_SESSION['staffid'];

  $output = '';
#from leavedash.php
#handling specific details of leave history of staff
if(isset($_POST["staffid"]))  
{  
   $staffid = $_POST["staffid"];
   $ltype = $_POST["ltype"];

    $stmt = $lvobj->getLeavesGone($staffid, $ltype);

    $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';
    $output .= '<tr>  
                     <td> <label>Leave Type</label></td> 
                     <td> <label>Reason</label></td>
                     <td> <label>Started On</label></td>
                     <td> <label>Ended On</label></td>
                     <td> <label>Days</label></td>
                     <td> <label>Location</label></td>
                       
                </tr> ';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {  
      $output .= '
                <tr>  
                     <td>'.ucfirst($row["leavetype"]).'</td> 
                     <td>'.ucfirst($row["reason"]).'</td> 
                     <td>'.date('j M, Y', strtotime($row['apstartdate'])).'</td> 
                     <td>'.date('j M, Y', strtotime($row['apenddate'])).'</td>
                     <td>'.$lvobj->numdays($row['apstartdate'], $row['apenddate']).'</td>
                     <td>'.$row["location"].'</td>
                </tr> 

                 ';  
    }  
    $output .= "</table></div>";  
    echo $output; 
}
else
{
    $output .= "Resource not found. Please try again latter";
}
?>