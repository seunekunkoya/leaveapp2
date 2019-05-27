<?php 

include "include/config.php";

$lvobj->checkSession();

extract($_POST);

$result = array();

$numD .=' days';
$date = date_create($startFr);
date_add($date, date_interval_create_from_date_string($numD));

$result['newdate'] = date_format($date, 'd-M-Y');


echo json_encode($result);


?>