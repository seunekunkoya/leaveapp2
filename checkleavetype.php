<?php 

include "include/config.php";

$lvobj->checkSession();

extract($_POST);

$result = array();

$stmt = $lvobj->isLeaveAppExist($staffid, $leavetype);

$num = $stmt->rowCount();

if( $num > 0 )
{
	$result['status'] = "TRUE";
} 
else{
	
	$result['status'] = "FALSE";
}

echo json_encode($result);

?>