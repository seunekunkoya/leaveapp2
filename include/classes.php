<?php

//including class file

include_once './class/general.php';
include_once './class/leaveclass.php';


//initializing new instance

$general = new general($con);

$lvobj = new leaveclass($con);

?>