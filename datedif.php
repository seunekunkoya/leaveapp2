<?php 
	include "include/config.php";
	echo  "Days ". $lvobj->numdays($_POST['sdate'], $_POST['edate']); 
?>