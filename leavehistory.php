<?php  

if(isset($_POST["appno"]))  
{  
     include 'config/database.php';
     require 'leavefunction.php';

     $appno = $_POST["appno"];
     $output = ''; 

try{

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $qry = "SELECT * FROM approvedleaves
           WHERE appno = '$appno'";

    $stmt = $con->prepare($qry);
    $stmt->execute();

    $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';  

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {  
      $output .= '  
                <tr>  
                     <td> <label>Leave Type</label></td> 
                     <td> <label>Reason</label></td>
                     <td> <label>Started On</label></td>
                     <td> <label>Ended On</label></td>
                     <td> <label>Days</label></td>
                     <td> <label>Location</label></td>
                       
                </tr>  
                <tr>  
                     <td>'.ucfirst($row["leavetype"]).'</td> 
                     <td>'.ucfirst($row["reason"]).'</td> 
                     <td>'.date('j M, Y', strtotime($row['apstartdate'])).'</td> 
                     <td>'.date('j M, Y', strtotime($row['apenddate'])).'</td>
                     <td>'.numdays($row['apstartdate'], $row['apenddate']).'</td>
                     <td>'.$row["location"].'</td>
                </tr> 

                 ';  
    }  
    $output .= "</table></div>";  
    echo $output; 
  }
      catch(PDOException $e){
        echo "Error: " . $e->getMessage();
      }//end of catch

}
 ?>