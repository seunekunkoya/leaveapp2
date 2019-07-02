<?php 

include "include/config.php";

  //checksession();
  $lvobj->checkSession();
  $staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

  $staffdetails = $lvobj->staffInfo($staffid);
  $_SESSION['staffinfo'] = $staffdetails;

  $level = $_SESSION['staffinfo']['level'];
  $leavetype = $_POST['leavetype'];

$result = array();

$session = $lvobj->getSession();
$session = $lvobj->addSlash($session);

$leavedaysgone = (int)$lvobj->leavedaysgone($staffid, $session, $leavetype);
$leaveallowed = (int)$lvobj->leavedaysallowed($staffid, $leavetype);
			     
$dayspermissible = $leaveallowed - $leavedaysgone;

$ndaysapplied = $lvobj->numdays($_POST['sdate'], $_POST['edate']);
        
$deductible = ($leaveallowed - $leavedaysgone) > 0 ? $ndaysapplied - ($leaveallowed - $leavedaysgone) : $ndaysapplied; #ternary operator

if(	$ndaysapplied < 0 )
{
	$result['status'] = 'neg';
	$result['reason'] = 'End date is less than start date.';
}

else{

switch ($leavetype) {
	case 'casual':
			if($ndaysapplied > 3)
			{
			   	$result['status'] = 'na';
			   	$result['reason'] = "You can't apply for more than 3 days at a time. Please speak to your supervisor if you need more days.";
			   	$result['daysapplied'] = $ndaysapplied;
			}
			else if ($ndaysapplied > $dayspermissible) 
			{
				$result['status'] = 'err';
				$result['reason'] = 'Days Applied cannot be accomodated under the casual leave policy';
				$result['daysapplied'] = $ndaysapplied;
				$result['deduct'] = $deductible;
			}
			else{				
				$result['status'] = 'ok';
	   			$result['allow'] = $dayspermissible;
	   			$result['daysapplied'] = $ndaysapplied;
			}
		break;
	case 'annual':
			if ($ndaysapplied > $dayspermissible) 
			{
				$result['status'] = 'np';
				$result['reason'] = 'Applied days are more than allowed';
				$result['daysapplied'] = $ndaysapplied;
			}
			else{				
				$result['status'] = 'ok';
	   			$result['allow'] = $dayspermissible;
	   			$result['daysapplied'] = $ndaysapplied;
			}
		break;

	default:
			$result['status'] = 'ok';
   			$result['allow'] = $dayspermissible;
   			$result['daysapplied'] = $ndaysapplied;
		break;
}//end of switch
 
}//end of if ndays applied
echo json_encode($result);
?>