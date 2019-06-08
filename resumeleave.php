<?php

/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      resumeleave.php
For every appno entrying this file, the transactionid increases by 1.
*/

include "include/config.php";

$staffid = $_SESSION['staffid'];

$lvobj->staffInfo($staffid);
$lvobj->checkSession();


extract($_POST);

//get transaction id for the current application stream 
$track = $lvobj->trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d', strtotime($rdate));
$comment = '';
$remarks = '';
 
//need be changed
//get comment
#comment is gotten from the post variables

//get status
try
{
    
    if($lvobj->insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment, $status, $sdate, $edate, $remarks))
    {      
            $resumed = 1;
            
            if($lvobj->approvedleavesUpdate($resumed, $appno));
            {
                //$lvobj->sendMail($to, $header, $subject, $message);
                $message = "Query Submitted";
                echo $message;
            }  
        }
    else
    {
        echo "Query not inserted";
        // print_r($_POST);
    }

}
catch(PDOException $e)
{
   	            echo "Error: " . $e->getMessage();
}//end of catch

?>
