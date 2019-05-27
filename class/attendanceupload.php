<?php

require_once('general.php');//including the general functions

class attendanceupload extends general
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
    //download lecture attendance sheet
    public function downloadlectureatt($coursecode)
    {
        $regrecords = array();
        $sql= "SELECT matricno, regno from  regdata where coursecode = '$coursecode' ORDER BY matricno, regno ";
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
    //get week header
    public function getweekheader($coursecode){
        $sql = "select distinct weekid from lectureattendanceuploadv where coursecode='$coursecode' order by weekid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    //get weekly lecture dates
    public function getweekdate($coursecode, $weekid){
        $sql = "select distinct lecturedate from lectureattendanceuploadv where coursecode='$coursecode' and weekid='$weekid' order by lecturedate";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    //get total lecture days for each course
}

?>