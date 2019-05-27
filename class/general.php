<?php
//class for general functions
class general {

	protected $db;
	
	//db function
	function __construct($con)
	{
		$this->db = $con;
	}
	
	public function currentsemester()
	{   
		/*        
		$sql = "SELECT * FROM currentsemester ";
		$stmt = $this->db->prepare($sql);	
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$csession=$rows['currentsession'];
		$csem =$rows['semester'];
		$csemid =$rows['semesterid'];
		*/
		//$details = array('semesterid' => $csemid, 'currentsession' => $csession, 'semester' => $csem);
		$details = array('semesterid' => 2, 'currentsession' => '2018/2019', 'semester' => 'first');
		return $details;
		
	}	
	public function getstudentdetails($matno){
		$output = '';
		$sql = "select * from reglist where matricno='$matno'";
		$stmt = $this->db->prepare($sql);	
		$stmt->execute();
		return $stmt;
	}
	
   public function lecturercourse($staffid, $selectedcourse = null)
   {
    	$output = '';
    	$query = "SELECT * FROM  lecturercoursetitlev  WHERE staffid = '$staffid'";
    	$statement = $this->db->prepare($query);
    	$statement->execute();
    	$result = $statement->fetchAll();
    	foreach($result as $row)
    	{      
			$roleid=$row['staffrole'];
             $homedeptid=$row['homedeptid'];
             $_SESSION['staffrole']= $roleid;
             $_SESSION['homedeptid']= $homedeptid;
			 if($selectedcourse == $row['coursecode']){
				 $output .= '<option value="'.$row["coursecode"].'" selected>'.ucfirst($row["coursecode"]).'     '.$row["coursetitle"].' '.$row["courseunit"].'Units'.'</option>';
			 }
			 else{
				 $output .= '<option value="'.$row["coursecode"].'">'.ucfirst($row["coursecode"]).'     '.$row["coursetitle"].' '.$row["courseunit"].'Units'.'</option>';
			 }

    	}
		return $output;
	}

	
	public function coursedetails($coursecode)
	{     
        $getcoursesql = "SELECT coursetitle ,courseunit, coursetypeid, homeprogid FROM courseprogtitle WHERE coursecode = '$coursecode'";
        $stmt2 = $this->db->prepare($getcoursesql); 
        $stmt2->execute();
    	$count = $stmt2->rowCount();
		if($count != 0){
			$rows = $stmt2->fetch(PDO::FETCH_ASSOC);
			extract($rows);
			$details = array('coursetypeid' => $coursetypeid, 'courseunit' => $courseunit, 'coursetitle' => $coursetitle, 'homeprogid' => $homeprogid);
		}
		else
			$details = array();
		return $details;
		
	}

	//get program name
	public function getProgramName($selectedProgram) {
		$query = "SELECT program FROM programs WHERE prgid = '$selectedProgram'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$name = $rows['program'];
		return $name;
	}
	//function for Africa/Lagos date
 	public function africaDate() {

 		date_default_timezone_set('Africa/Lagos');
 		$dbDate = date('Y-m-d H:i:s');
 		return $dbDate;
 	}

  //function to delete row
	public function delete($id, $tblname)
	{
		//deleting from db
    	$sql = "DELETE FROM `$tblname` WHERE id = '$id'";

    	$stmt = $this->db->query($sql);
    	$count = $stmt->rowCount();

    	if ($count > 0) {
    		//set msg
    		$msg = true;
    	}

    	return $msg;
	}


	
  //function for pulling programs
  public function programs($selectedProgram) {

      $sql = "SELECT * FROM programs ORDER BY program";
      $stmt = $this->db->query($sql);

      while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
          //get value from row
          $program = $rows['program'];
          $prgid = $rows['prgid'];

          //setting selected option if both countries are same
          $r = ($prgid == $selectedProgram) ? 'selected' : '';

          //echo the option
          echo '<option value="'.$prgid.'" '.$r.'>'.$program.'</option>';
      }
  }
  
  //function for pulling yog
  public function yog($selectedyog) {

      $sql = "SELECT distinct yog FROM cert ORDER BY yog";
      $stmt = $this->db->query($sql);

      while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
          //get value from row
          $yog = $rows['yog'];

          //setting selected option if both countries are same
          $r = ($yog == $selectedyog) ? 'selected' : '';

          //echo the option
          echo '<option value="'.$yog.'" '.$r.'>'.$yog.'</option>';
      }
  }
  
	public function getweekname($weekid){
		$weekname = '';
		$sql = "select weekname from weeknames where weekid = '$weekid'";
		$stmt = $this->db->prepare($sql);	
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$weekname = $rows['weekname'];
		return $weekname;
	}
	
	
	public function getUserIpAddr()
	{
		if(!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		  return $ip;
	}

  
	//function to get the session and semester name
	public function sessionSemester() {
		$sql = "SELECT semestername, session FROM rptyear";
        $stmt = $this->db->query($sql);
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		
		//set
		$semester = $rows['semestername'];
		$session = $rows['session'];
		
		//return details
		$details = array('semester' => $semester, 'session' => $session);
		return $details;
	}

}

?>
