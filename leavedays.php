<?php 
	
include "include/config.php";

$lvobj->checkSession();

extract($_POST);


	// $output = ' ';
	// $output .= '  <p>A test </p>   '; 

	 
	 $session = $lvobj->getSession();
	 $session = $lvobj->addSlash($session);
	 $output = ' ';
	 $outp = array();

	 
	$leavedaysgone = (int)$lvobj->leavedaysgone($staffid, $session, $leavetype);
	$leaveallowed = (int)$lvobj->leavedaysallowed($staffid, $leavetype);
		 
	if(is_null($leaveallowed)){
	   	$leaveallowed = 0;
	}

    if(is_null($leavedaysgone)){
    	$leavedaysgone = 0;
    }

    $dayspermissible = (int)$leaveallowed - (int)$leavedaysgone; 
    $outp['status'] = 'ok';
    $outp['dg'] = $leavedaysgone;
    $outp['da'] = $leaveallowed;
    $outp['dp'] = $dayspermissible;  
		
	$output .= "<h6>". ucfirst($leavetype). " leave allowance is: ".$leaveallowed." days</h6>";
	$output .= "<h6>". ucfirst($leavetype). " leave days gone is ".$leavedaysgone." day(s), ";
	$output .= "you still have ".$dayspermissible." days out of your ".$leavetype." leave </h6>";
	
	echo $outp['da']."|".$outp['dg']."|".$outp['dp'];
	// echo json_encode($outp);
?>