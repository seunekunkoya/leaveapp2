<?php

/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      releasestaff.php
For every appno entrying this file, the transactionid increases by 1.
*/
include "include/config.php";

$lvobj->checkSession();


$result = array();


$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);

extract($_POST);

$numAppr = (int)$lvobj->numdays($sdate, $edate);

if((int)$numd > $numAppr)
{
  $result['numGreater'] = true;
}

else {

	$result['numGreater'] = false;
}

echo json_encode($result);

?>