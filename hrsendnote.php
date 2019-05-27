<?php 

include "include/config.php";

$lvobj->checkSession();

$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;

$hro = $_SESSION['staffinfo']['hro'];
$rego = $_SESSION['staffinfo']['rego'];
$vco = $_SESSION['staffinfo']['vco'];

  
  $cursession = '2018/2019';  
  $transactionDate = date('Y-m-d');
  $transaactionNo = transactionNo();

  $officer = "HR";
  $recc = "Presented";
  $comment = "Note Sent";
  $action = "Presented";

  if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $recc, $comment, $action))
  {
     echo "Note Submitted";
     //$lvobj->sendMail($to, $header, $subject, $message);
  }

?>
