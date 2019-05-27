<?php

require_once('general.php');//including the general functions

class resultupload extends general
{
	function __construct($con)
	{
		parent::__construct($con);
	}
	
	//get program name
	public function getProgramcheck($selectedProgram) {
		$query = "SELECT program FROM programs WHERE prgid = '$selectedProgram'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$name = $rows['program'];
		return $name;
	}
	//download score sheet
	public function download($coursecode,$testtype)
	{
        $regrecords = array();
        $sql= "SELECT matricno, regno from  regdata where coursecode = '$coursecode' ORDER BY regno ";  
		$stmt = $this->db->prepare($sql); 
        $stmt->execute();
        $downloadrecordcount=$stmt->rowCount();
		$i = 0;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row);
			$regrecords[$i] = array('matricno'=>$matricno ,'regno'=>$regno);
			$i++;
		}
		return $regrecords;
	}

}

?>