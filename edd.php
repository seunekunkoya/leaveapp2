<?php 
	
	include('config/database.php');
	include('leavefunction.php');

	extract($_POST);	
	
	$outp = array();

	$eddbefore = date('d-M-Y', strtotime('-14 days', strtotime($edd)));
	$afterEdd = date('d-M-Y', strtotime('+84 days', strtotime($edd)));
	  

    $outp['eddb'] = $eddbefore;
    $outp['edda'] = $afterEdd;
    $outp['edd'] = $edd;
   
	echo $outp['edda']."|".$outp['eddb']."|".$outp['edd'];

	 
	// echo json_encode($outp);
?>