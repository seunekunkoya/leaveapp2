<?php
/*
Developer: Ekunkoya Isaiah
Site:      ekunkoya.com.ng
Script:    Inserts data into transaction table
File:      leaverec.php
For every appno entrying this file, the transactionid increases by 1.
*/

include "include/config.php";

$lvobj->checkSession();

extract($_POST);

//get transaction id for the current application stream 
$track = trackid($appno); //this is the transactionid which will later be increased by 1
$transactionid = $track + 1;

//get the time viewed
$timeviewed = date('Y-m-d H:i:s');
 
//need be changed
//get comment
#comment is gotten from the post variables

//get status
try{

        if($lvobj->insertResumptionConfirmed($appno, $staffid, $role, $transactionid, $timeviewed, $reco, $sdate, $edate, $remarks)){
            echo "Query Inserted";
        }
        else
        {
            echo "Query not inserted";
           // print_r($_POST);
        }

    }
        catch(PDOException $e){
   	            echo "Error: " . $e->getMessage();
 }//end of catch

?>
