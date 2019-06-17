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
$dfs = $_SESSION['staffinfo']['dfs'];
$payroll = $_SESSION['staffinfo']['payrollofficer'];

$cursession = $lvobj->getSession();
//$slashedSession = $lvobj->addSlash($cursession);
$transactionDate = date('Y-m-d H:i:s');

if(isset($_POST['staffid'])){

    extract($_POST);

    $staffid = $_POST['staffid'];
    if($staffid == $hro){

          
          $transaactionNo = $lvobj->transactionNo();

          $officer = "HR";
          

          if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $reccom, $comment))
          {
             echo "Note Submitted";
             //$lvobj->sendMail($to, $header, $subject, $message);
          }

    }//end of hr
    if($staffid == $rego){

          $transaactionNo = $lvobj->transactionNo();

          $officer = "Registrar";

          if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $reccom, $comment))
          {
             echo "Note Submitted";
             //$lvobj->sendMail($to, $header, $subject, $message);
          }//
    }//end of reg
    if($staffid == $dfs){

      $transaactionNo = $lvobj->transactionNo();
        
      $officer = "DFS";

      if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $reccom, $comment))
          {
             echo "Note Submitted";
             //$lvobj->sendMail($to, $header, $subject, $message);
          }//

    }//end of dfs
    if($staffid == $vco){

      $transaactionNo = $lvobj->transactionNo();

          $officer = "VC";
          
           if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $reccom, $comment))
            {
               echo "Note Submitted";
               //$lvobj->sendMail($to, $header, $subject, $message);
            }//
    }//end of vc

    if($staffid == $payroll){

      $transaactionNo = $lvobj->transactionNo();

          $officer = "payroll";
          
           if($lvobj->insertNote($transactionDate, $transaactionNo, $cursession, $officer, $reccom, $comment))
            {
               echo "Note Submitted";
               //$lvobj->sendMail($to, $header, $subject, $message);
            }//
    }//end of payroll

}//end of post
else{
  echo "Resource Unavailable";
}
  
  

?>
