<?php

require_once('general.php');//including the general functions

class setup extends general
{
	function __construct($con)
	{
		parent::__construct($con);
	}
	
	//function ro check if course plan exists
	public function checkexixstplan($coursecode)
	{
		$courseassescount = 0;
        $existingplancourse = "SELECT coursecode  FROM courseassessmentplanv WHERE coursecode = '$coursecode'";
        $stmt = $this->db->query($existingplancourse);
        $courseassescount=$stmt->rowCount();
		return $courseassescount;
		
	}
	
	//function to get test plans
	public function gettesttype(){
		$sql = "SELECT * FROM testtypes ORDER BY testtypeid ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$rwcount = $stmt->rowCount();
		$i = 0;
		while($rows = ($stmt->fetch(PDO::FETCH_ASSOC))){
			extract($rows);
			$details[$i] = array('testtype' => $testtype, 'testtypeid' => $testtypeid);
			$i++;
		}
		return $details;
		
	}
	
	//submit course assement setup
	
	function newcoursesetup($coursecode,$maxmark,$testtypeid,$staffid,$semesterid,$assesdate)
	{
	   $mark[] = $maxmark;
		foreach ($mark as $maximum)
	  {
		$totalmark = ((int)$maximum[0] +  (int)$maximum[1] + (int)$maximum[2]);   
	  }
	  if($totalmark!==100)
	  {
		  $remark = "Score must be equal to 100(hundred)!!!!!!";
	  }
		else
		 {
			  $sql = "SELECT coursecode FROM courseassessmentplan WHERE coursecode='$coursecode'";
			  $stmt = $this->db->prepare($sql);  
			  $stmt->execute();
			  $plancoursecodecount=$stmt->rowCount();
			  if($plancoursecodecount > 0){
				$remark = "You Have An Existing Plan For This Course!!!!!!";
			}
			else{

			  for($count = 0; $count < count($_POST["testtypeid"]); $count++)
			{ 
			   $query = "INSERT INTO courseassessmentplan (coursecode, maxmark, testtypeid, staffid, semesterid, postdate) 
			   VALUES (:coursecode, :maxmark, :testtypeid , :staffid, :semesterid, :postdate )";
			   $statement = $this->db->prepare($query);    
			 $result = $statement->execute(
				 array(':coursecode'=> $coursecode,
				  ':maxmark'  => $maxmark[$count],
				  ':testtypeid' =>$testtypeid[$count],
				  ':staffid' =>   $staffid,
				 ':semesterid' => $semesterid,
				 ':postdate' =>  $assesdate
				)
			   );
			   
			}
			if($result)
			{
				$remark = "Record Succesfully Inserted!!!!!!";
			}
			else
			{
				$remark = "Total mark must be equal to hundred(100)!!!!!!";
			} 
	  
		} 
	  }
	  return $remark;
	}
	
	//get assement previous set up
	public function getprevassesmentplan($coursecode){
		$courseassementdetails = array();
		$sql = "SELECT coursecode,testtype,maxmark, testtypeid, id FROM courseassessmentplanv WHERE coursecode ='$coursecode'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$i = 0;
		while($rows = ($stmt->fetch(PDO::FETCH_ASSOC))){
			$coursecode=$rows['coursecode'];
			$testtype=$rows['testtype'];
			$testtypeid=$rows['testtypeid'];
			$id=$rows['id'];
			$maxmark=$rows['maxmark'];
			$courseassementdetails[$i] = array('coursecode' => $coursecode, 'testtype' => $testtype, 'testtypeid' => $testtypeid, 'id' => $id, 'maxmark' => $maxmark);
			$i++;
		}
		return $courseassementdetails;
	}
	
	//update course assement plan
	public function updatecourseassesment($coursecode,$maxmark,$id, $semesterid, $staffid)
	{
		$mark[] = $maxmark;
		foreach ($mark as $maximum)
		{
			$totalmark = ((int)$maximum[0] +  (int)$maximum[1] + (int)$maximum[2]);
		}
		if($totalmark!==100)
		{
			$remark = "Score must be equal to 100(hundred)!!!!!!";
		}
		else{
			$assesdate =  (new \DateTime())->format('Y-m-d H:i:s');
			for($count = 0;  $count < count($maxmark); $count++)
			{
				$query = "UPDATE courseassessmentplan SET maxmark=:maxmark, postdate=:postdate, semesterid=:semesterid, staffid=:staffid WHERE id=:id";
				$statement = $this->db->prepare($query);
				$result=$statement->execute(
				array(
				':maxmark'=>$maxmark[$count],
				':id'=>$id[$count],
				':postdate'=>$assesdate,
				':staffid'=>$staffid,
				':semesterid'=>$semesterid
				)
				); 
			}
			if($result)
			{
				$remark =  "Course Assesment Succesfully Updated!!!!!!";
			}
			else
			{
				$remark =  "Update not succesful!!!!!!";
			}
		}
		return $remark;


	}


}

?>