<?php
// include 'config/database.php';
// include "leavefunction.php";
//check for session
//checkSession();
include "include/config.php";

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

print_r($_SESSION['staffinfo']);

$staffid = $_SESSION['staffinfo']['staffid'];
$hodid = $_SESSION['staffinfo']['hod'];
$cat = $_SESSION['staffinfo']['category'];
$deanid = $_SESSION['staffinfo']['dean'];
$dfs = $_SESSION['staffinfo']['dfs'];

//$cursession = '2018/2019';

$cursession = $lvobj->getSession();
echo '<br><br>'.$cursession;


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
    The query below is to find if a staff has been released so as to allow the resume button to be activated. If the query is false then the button will not be activated for the staff.
*/
            $stmt = $lvobj->getStaffRelease($staffid);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            print_r($stmt);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $stmreg = $lvobj->getDashboardOfficer($cursession);

            $rowreg = $stmreg->fetch(PDO::FETCH_ASSOC);
            $numreg = $stmreg -> rowCount();  

//          echo $rowreg['officer'];   
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
             #Query to check  if schedule is created
            $stmt1 = $lvobj->getSchedule($cursession);
            $num1 = $stmt1->rowCount();       
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h3><b><?php //echo getname(htmlspecialchars($_SESSION["loginid"])); ?></b>Leave Application Dashboard</h3>
    </div>
    <?php 
        if(isset($_GET['id']))
            $id = base64_decode($_GET['id']);
            {
    ?>
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h3>Personal Leave Tab</h3>
              <p><a href="leaveapptest.php" class="btn btn-default">Make New Application</a></p>

        <?php 
                echo '<p><a href="leavestatus.php?id='.base64_encode($id).'" class="btn btn-default">View Application Status</a></p>'; 
                
                    if ($num > 0 ) {
                        echo ' <p><a href="resume.php?id='.base64_encode($id).'" class="btn btn-default">Resume Work</a></p>'; 
                        
                    }//the query to check if released date of staff exist
            ?>
        </div>

        <div class="col-md-3">
            <h3>Official Leave Tab</h3>
        <?php 
                 if ($hodid == $id) {
                    echo "HOD";                       
                    echo '<p> <a href="leaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Applications</a></p>';
                    echo '<p> <a href="viewresumedstaff.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Resumption Confirmation</a></p>';
                }
                if ($deanid == $id) {
                    echo "DEAN";
                    echo ' <p><a href="leaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Applications</a></p>';
                }
                if ($_SESSION['staffinfo']['hro'] == $id) {
                     echo '<p> <a href="leaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Applications</a></p>';
                     echo '<p> <a href="approvedleaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Approved Leave</a></p>';
                     echo '<p> <a href="releasedleaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Released Leave</a></p>';
                     echo '<p> <a href="staffresumed.php?id='.base64_encode($id).'" class="btn btn-default">View Resumed Staff</a></p>';
                     echo '<p> <a href="overstayedview.php?id='.base64_encode($id).'" class="btn btn-default">View Overstayed Staff</a></p>';
                     
                    if( $num1 > 0 ){
                        echo '<p><a href="annualleavereport3.php" class="btn btn-default">View Schedule</a></p>';    
                    }
                    else
                     {
                        echo '<p> <a href="annualleavereport.php" class="btn btn-default">Leave Schedule</a></p>';
                     }
                }
                if ($_SESSION['staffinfo']['rego'] == $id) {
                    echo '<p> <a href="leaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Applications</a></p>';
                    if( $num1 > 0 ){
                        echo '<p><a href="annualleavereport3.php" class="btn btn-default">View Schedule</a></p>';    
                    }               
                    
                }
                if ($_SESSION['staffinfo']['vco'] == $id) {
             
                    echo ' <p><a href="leaveview.php?id='.base64_encode($id).'" class="btn btn-default">View Pending Applications</a></p>';
                   if( $num1 > 0 ){
                        echo '<p><a href="annualleavereport3.php" class="btn btn-default">View Schedule</a></p>';    
                    }
                }
                if ($_SESSION['staffinfo']['dfs'] == $id) {
                    if( $num1 > 0 ){
                        echo '<p><a href="annualleavereport3.php" class="btn btn-default">View Schedule</a></p>';    
                    }                 
                }
        ?>
        </div>
    </div><!--end of row-->
    <div class="row"> 
        <p> <a href="logout.php" class="btn btn-default">Sign Out</a> </p>
    </div>
</div>
<?php }// end of if isset get id?>
  
</body>
</html>