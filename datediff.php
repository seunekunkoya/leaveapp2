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

		//numdays($_POST['sdate'], $_POST['edate'])." Days ";
		if($leavetype == 'casual' || $leavetype == 'annual' || $leavetype == 'maternity'){

				 
				 if($ndaysapplied < 0 )
				 {
				   	$result['status'] = 'neg';
				   	$result['reason'] = 'Invalid Days Selected';
				 }
			     
			     else if ($ndaysapplied > $dayspermissible) 
			     {
			     	$result['status'] = 'err';
			     	$result['reason'] = 'Leave duration is more than allowed and permissible';
			     	$result['daysapplied'] = $ndaysapplied;
			     }
			     
			     else
			     {
			     	$result['status'] = 'ok';
			     	$result['allow'] = $dayspermissible;
			     	$result['daysapplied'] = $ndaysapplied;
			     }

		}//end of leavetype test
		
		else
		{	//$dayspermissible = 0;

			if(	$ndaysapplied < 0 )
			{
			   	$result['status'] = 'neg';
			   	$result['reason'] = 'Invalid Days Selected';
			}

			else
			{
			   	$result['status'] = 'ok';
			   	$result['allow'] = $dayspermissible;
			   	$result['daysapplied'] = $ndaysapplied;
			}
		}//end of else leavetype test

		 

	    echo json_encode($result);
?>