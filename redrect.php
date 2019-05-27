<?php 
	include 'leavefunction.php';
  checksession();
  $_SESSION['staffdetails']

	if(isset($_GET['id']))
	{
    $id = base64_decode($_GET['id']);

		if(isdean($id))
            {
              //session_start();
              //$_SESSION['loginid'] = $id;
              header("location: leavedashboard.php?deanid=".base64_encode($id));
            } 
           
	    if(isHod($id))
          {
             //session_start();
             //$_SESSION['loginid'] = $id;
             header("location: leavedashboard.php?hodid=".base64_encode($id)); 
          } 

        if(!isHod($id) && !isdean($id))
          {                             
            //session_start();
            //$_SESSION['loginid'] = $id;                           
            header("location: leavedashboard.php?id=".base64_encode($id)); 
          }
	}
	else{
		header("location: login.php");	
	}
?>