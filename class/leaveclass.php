<?php
//class for general public functions
class leaveclass extends general {

	//db public function
	function __construct($con)
	{
		parent::__construct($con);
	}
	
	public function checkSession()
	{
		// Check if the user is logged in, if not then redirect him to login page
		if(!isset($_SESSION["staffid"]) && $_SESSION["staffid"] !== true){
    			header("location: login.php");
    	exit;
		}
	}//end of public function checkSession

	public function login($id){

		$qry = "SELECT staffid FROM stafflst WHERE staffid = '$id'";
        
        $stmt = $this->db->prepare($qry);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//return $stmt;

		 if($stmt->rowCount() > 0){
		 	$_SESSION['staffid'] = $row['staffid'];
			return true;
		}
		else {
			return false;
		}
	}//end of login

	public function redirect($url) {
        header("Location: $url");
    }

    	public function staffDetails()
	{
		
		$userdetails = get_user($_SESSION['loginid']);
		// $staffdetails = get_user($staffid);
	//	$staffid = implode(',',array_map(public function($el){return $el['idno']; }, $userdetails));
	}

	public function staffInfo($id){
		$staffInfo = array();
		$query = "
					SELECT * from stafflst WHERE staffid = '$id'
				 ";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$staffinfo = $row;
		return $staffinfo;
	}

  function getSession()
  {
      $query = "
                SELECT DISTINCT session 
                FROM rptyears 
                WHERE sessionid = (SELECT max(sessionid) FROM rptyears)
         ";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $session;
  }

	#LAT => Leave Application Table
	public function insertLAT($staffid, $appno, $leavetype, $reason, $sdate, $edate, $session, $location, $phone, $officer1, $officer2, $officer3, $leavestatus, $leavestageid, $datecreated)
	{
		$stmt = $this->db->prepare("INSERT INTO leaveapplication(staffid, appno, leavetype, reason, startdate, enddate, session, location, phone, officer1,officer2, officer3, leavestatus, leavestageid, datecreated) 
                                VALUES(:staffid, :appno, :leavetype, :reason, :startdate, :enddate, :session, :location, :phone, :officer1, :officer2, :officer3, :leavestatus, :leavestageid, :datecreated )");

                    $stmt->bindparam(':staffid', $staffid);
                    $stmt->bindparam(':appno', $appno);
                    $stmt->bindparam(':leavetype', $leavetype);
                    $stmt->bindparam(':reason', $reason);
                    $stmt->bindparam(':startdate', $sdate);
                    $stmt->bindparam(':enddate', $edate);
                    $stmt->bindparam(':session', $session);
                    $stmt->bindparam(':location', $location);
                    $stmt->bindparam(':phone', $phone);
                    $stmt->bindparam(':officer1', $officer1);
                    $stmt->bindparam(':officer2', $officer2);
                    $stmt->bindparam(':officer3', $officer3);
                    $stmt->bindparam(':leavestatus', $leavestatus);
                    $stmt->bindparam(':leavestageid', $leavestageid);
                    $stmt->bindparam(':datecreated', $datecreated);

                    if($stmt->execute()){
                    	return true;
                    }
                    else{
                    	return false;
                    }
	}//end of insertLAT

	public function insertLT($appno, $staffid, $role, $transactionid, $timeviewed, $comment = null, $status, $recstartdate, $recenddate, $remarks = null)
  {
    $query1 = "INSERT INTO leavetransaction (appno, tstaffid, role, transactionid, timeviewed, comment , status, recstartdate, recenddate, remarks) VALUE (:appno, :tstaffid, :role, :transactionid, :timeviewed, :comment, :status, :recstartdate, :recenddate, :remarks)";
                        $stmt1 = $this->db->prepare($query1);

                        $stmt1->bindparam(':appno', $appno);
                        $stmt1->bindparam(':tstaffid', $staffid);
                        $stmt1->bindparam(':role', $role);
                        $stmt1->bindparam(':transactionid', $transactionid);
                        $stmt1->bindparam(':timeviewed', $timeviewed);
                        $stmt1->bindparam(':comment', $comment);
                        $stmt1->bindparam(':status', $status);
                        $stmt1->bindparam(':recstartdate', $recstartdate);
                        $stmt1->bindparam(':recenddate', $recenddate);
                        $stmt1->bindParam(':remarks', $remarks);

                      if($stmt1->execute()){
                        return true;
                      }
                      else{
                        return false;
                      }

  }

	public function insertLTS($appno, $staffid, $role, $transactionid, $timeviewed, $reco, $sdate, $edate, $remarks){

		$qry = "INSERT INTO leavetransaction (appno, tstaffid, role, transactionid, timeviewed, status, recstartdate, recenddate, remarks) 
				VALUES (:appno, :staffid, :role, :transactionid, :timeviewed, :status, :recstartdate, :recenddate, :remarks)";

        // prepare query for excecution
        $stmtu = $this->db->prepare($qry);

        // bind the parameters
        $stmtu->bindParam(':appno', $appno);
        $stmtu->bindParam(':staffid', $staffid);
        $stmtu->bindParam(':role', $role);
        $stmtu->bindParam(':transactionid', $transactionid);
        $stmtu->bindParam(':timeviewed', $timeviewed);
        $stmtu->bindParam(':status', $reco);
        $stmtu->bindParam(':recstartdate', $sdate);
        $stmtu->bindParam(':recenddate', $edate); 
        $stmtu->bindParam(':remarks', $remarks);

        if($stmtu->execute()){
           return true;
	     }
	     else{
	       	return false;
	     }
	}

	public function insertResumption($appno, $staffid, $role, $transactionid, $timeviewed, $reco, $sdate, $edate){

				$qry = "INSERT INTO leavetransaction (appno, tstaffid, role, transactionid, timeviewed, status, recstartdate, recenddate) 
						VALUES (:appno, :staffid, :role, :transactionid, :timeviewed, :status, :recstartdate, :recenddate)";

		        // prepare query for excecution
		        $stmtu = $this->db->prepare($qry);

		        // bind the parameters
		        $stmtu->bindParam(':appno', $appno);
		        $stmtu->bindParam(':staffid', $staffid);
		        $stmtu->bindParam(':role', $role);
		        $stmtu->bindParam(':transactionid', $transactionid);
		        $stmtu->bindParam(':timeviewed', $timeviewed);
		        $stmtu->bindParam(':status', $reco);
		        $stmtu->bindParam(':recstartdate', $sdate);
		        $stmtu->bindParam(':recenddate', $edate);

		        if($stmtu->execute()){
		        	return true;
		        }
		        else {
		        	return false;
		        }
	}

	public function approvedleavesUpdate($resumed, $appno){

		$qry3 = "UPDATE approvedleaves 
                          SET resumestatus = :resumed
                            WHERE appno = :appno";

                // prepare query for excecution
                $stmt3 = $this->db->prepare($qry3);     

                // bind the parameters
                $stmt3->bindParam(':resumed', $resumed);
                $stmt3->bindParam(':appno', $appno);

                if($stmt3->execute()){
                	return true;
                }
                else{
                	return false;
                }

	}


	public function approvedleavesUpdateByDate($rdate, $appno){

            $qry3 = "UPDATE approvedleaves 
                        SET resumeddate = :rdate
                           WHERE appno = :appno";

                // prepare query for excecution
                $stmt3 = $this->db->prepare($qry3);

                $stmt3->bindParam(':rdate', $rdate);
             	$stmt3->bindParam(':appno', $appno);

             	if($stmt3->execute()){
                	return true;
                }
                else{
                	return false;
                }               
	}

	public function approvedleavesUpdateByRelease($dateofrelease, $appno){

		$qry1 = "UPDATE approvedleaves
                  SET releaseddate=:dateofrelease
                  WHERE appno = :appno";

        // prepare query for excecution
        $stmt1 = $this->db->prepare($qry1);     

        // bind the parameters
        $stmt1->bindParam(':dateofrelease', $dateofrelease);
        $stmt1->bindParam(':appno', $appno);

        if($stmt1->execute()){
            return true;
          }
         else{
          	return false;
         } 
	}


	public function newApprovedleavesUpdateByRelease($dateofrelease, $appno, $numD, $startFr, $toRes){

			$qry1 = "UPDATE approvedleaves
	                  SET releaseddate=:dateofrelease, numdays=:numD, releaseStart=:startFr, releaseEnd=:toRes
	                  WHERE appno = :appno";

	        // prepare query for excecution
	        $stmt1 = $this->db->prepare($qry1);     

	        // bind the parameters
	        $stmt1->bindParam(':dateofrelease', $dateofrelease);
	        $stmt1->bindParam(':appno', $appno);
	        $stmt1->bindParam(':numD', $numD);
	        $stmt1->bindParam(':startFr', $startFr);
	        $stmt1->bindParam(':toRes', $toRes);

	        if($stmt1->execute()){
	            return true;
	          }
	         else{
	          	return false;
	         }
	}

	public function approveLeaves($appno, $sdate, $edate, $reco, $stage){

		$qry1 = "SELECT l.staffid, l.appno, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.session, l.location, l.phone
                FROM leaveapplication AS l
                INNER JOIN leavetransaction AS lt
                ON lt.appno = l.appno
                where lt.appno = '$appno'
                AND lt.status = 'Approved'";

                
                $stmt = $this->db->prepare($qry1);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $staffId = $row['staffid'];
                $leavetype = $row['leavetype'];
                $reason = $row['reason'];
                $csession = $row['session'];//current session
                $location = $row['location'];
                $phone = $row['phone'];
        
        $qry2 = "INSERT INTO approvedleaves (staffid, appno, leavetype, reason, apstartdate, apenddate, session, location, phone) 
                VALUES (:staffId, :appno, :leavetype, :reason, :recst, :recend, :session, :location, :phone)";

              $stmt1 = $this->db->prepare($qry2);

              $stmt1->bindParam(':staffId', $staffId);
              $stmt1->bindParam(':appno', $appno);
              $stmt1->bindParam(':leavetype', $leavetype);
              $stmt1->bindParam(':reason', $reason);
              $stmt1->bindParam(':recst', $sdate);
              $stmt1->bindParam(':recend', $edate);
              $stmt1->bindParam(':session', $csession);
              $stmt1->bindParam(':location', $location);
              $stmt1->bindParam(':phone', $phone);

              if($stmt1->execute())
              {
                $qry3 = "UPDATE leaveapplication 
                          SET leavestatus = :leavestatus, leavestageid = :stage
                            WHERE appno = :appno";

                // prepare query for excecution
                $stmt3 = $this->db->prepare($qry3);     

                // bind the parameters
                $stmt3->bindParam(':leavestatus', $reco);
                $stmt3->bindParam(':stage', $stage);
                $stmt3->bindParam(':appno', $appno);
    
                if($stmt3->execute()){
                	return true;
                }else{
                	return false;
                }
	}

}

	public function updateLeaveApplication($reco, $stage, $appno){

		$qry1 = "UPDATE leaveapplication 
                          SET leavestatus = :leavestatus, leavestageid = :stage
                            WHERE appno = :appno";

                // prepare query for excecution
                $stmt1 = $this->db->prepare($qry1);     

                // bind the parameters
                $stmt1->bindParam(':leavestatus', $reco);
                $stmt1->bindParam(':stage', $stage);
                $stmt1->bindParam(':appno', $appno);

                if($stmt1->execute()){
                	return true;
                }
                else{
                	return false;
                }
    
	} 

	public function insertResumptionConfirmed($appno, $staffid, $role, $transactionid, $timeviewed, $reco, $sdate, $edate, $remarks){

		$qry = "INSERT INTO leavetransaction (appno, tstaffid, role, transactionid, timeviewed, status, recstartdate, recenddate, remarks) 
				VALUES (:appno, :staffid, :role, :transactionid, :timeviewed, :status, :recstartdate, :recenddate, :remarks)";

        // prepare query for excecution
        $stmtu = $this->db->prepare($qry);

        // bind the parameters
        $stmtu->bindParam(':appno', $appno);
        $stmtu->bindParam(':staffid', $staffid);
        $stmtu->bindParam(':role', $role);
        $stmtu->bindParam(':transactionid', $transactionid);
        $stmtu->bindParam(':timeviewed', $timeviewed);
        $stmtu->bindParam(':status', $reco);
        $stmtu->bindParam(':recstartdate', $sdate);
        $stmtu->bindParam(':recenddate', $edate); 
        $stmtu->bindParam(':remarks', $remarks);

        if($stmtu->execute()){
        	return true;
        }else{
        	return false;
        }

	}

	public function insertLeaveScheduleTransaction($transactionDate, $transaactionNo, $cursession, $officer, $recc, $comment, $action){

		$qry = "INSERT INTO leavescheduletransaction (transactionDate, transactionNo, session, officer, recommendation, comment, action) VALUE (:transactionDate, :transactionNo, :session, :officer, :recommendation, :comment, :action)";
                                     
                $stm = $this->db->prepare($qry);
                $stm->bindparam(':transactionDate', $transactionDate);
                $stm->bindparam(':transactionNo', $transaactionNo);
                $stm->bindparam(':session', $cursession);
                $stm->bindparam(':officer', $officer);
                $stm->bindparam(':recommendation', $recc);
                $stm->bindparam(':comment', $comment);
                $stm->bindparam(':action', $action);

                $stm->execute();
	}

	public function insertLeaveSchedule($schedule, $cursession ){

		for ($i=0; $i < count($schedule); $i++)
      	{ 

            $query1 = "INSERT INTO leaveschedule (session, title, staffname, staffid, post, progunit, level, empdate, daysworked, daysentitled, daysgone, dayspermissible, leavebonus, bankacctno, bankname) VALUE (:cursession, :title, :staffname, :staffid, :post, :progunit, :level, :empdate, :daysworked, :daysentitled, :daysgone, :dayspermissible, :leavebonus, :bankacctno, :bankname)";

                          $stmt1 = $this->db->prepare($query1);

                          $stmt1->bindparam(':cursession', $cursession);
                          $stmt1->bindparam(':title', $schedule[$i]['title']);
                          $stmt1->bindparam(':staffname', $schedule[$i]['staffname']);
                          $stmt1->bindparam(':staffid', $schedule[$i]['staffid']);
                          $stmt1->bindparam(':post', $schedule[$i]['post']);
                          $stmt1->bindparam(':progunit', $schedule[$i]['dept']);
                          $stmt1->bindparam(':level', $schedule[$i]['level']);
                          $stmt1->bindparam(':empdate', $schedule[$i]['employmentdate']);
                          $stmt1->bindparam(':daysworked', $schedule[$i]['daysworked']);
                          $stmt1->bindparam(':daysentitled', $schedule[$i]['daysentitled']);
                          $stmt1->bindparam(':daysgone', $schedule[$i]['daysgone']);
                          $stmt1->bindparam(':dayspermissible', $schedule[$i]['permissibledays']);
                          $stmt1->bindparam(':leavebonus', $schedule[$i]['leavebonus']);
                          $stmt1->bindparam(':bankacctno', $schedule[$i]['bankacct']);
                          $stmt1->bindparam(':bankname', $schedule[$i]['bankname']); 

                          //print_r($stmt1);             
                          $result1 = $stmt1->execute();                          
                           
        }//end of for loop
	}

	public function insertNote($transactionDate, $transaactionNo, $cursession, $officer, $recc, $comment, $action){

			$qry = "INSERT INTO leavescheduletransaction (transactionDate, transactionNo, session, officer, recommendation, comment, action) VALUE (:transactionDate, :transactionNo, :session, :officer, :recommendation, :comment, :action)";
                           
            $stm = $this->db->prepare($qry);
            $stm->bindparam(':transactionDate', $transactionDate);
            $stm->bindparam(':transactionNo', $transaactionNo);
            $stm->bindparam(':session', $cursession);
            $stm->bindparam(':officer', $officer);
            $stm->bindparam(':recommendation', $recc);
            $stm->bindparam(':comment', $comment);
            $stm->bindparam(':action', $action);

            if($stm->execute()){
    	    	return true;
	        }else{
        		return false;
        	}

	}

	public function getStatus($id, $cat){

		$query = "SELECT st.fname, st.sname, s.dept, s.hod, l.staffid, l.leavetype, l.reason, l.startdate, l.enddate, l.leavestatus, l.appno, lt.tstaffid, lt.comment, lt.role, lt.transactionid, lt.recstartdate, lt.recenddate, lt.status, lt.timeviewed, lt.remarks, l.location
				  FROM stafflst AS s
                  INNER JOIN leaveapplication AS l
                  ON s.staffid = l.staffid
                  INNER JOIN stafflist AS st
                  ON s.staffid = st.staffid
                  INNER JOIN leavetransaction AS lt
                  ON l.appno = lt.appno
                  WHERE l.staffid = '$id' AND s.category = '$cat'
                  ORDER BY lt.appno DESC";

        		  $stmt = $this->db->prepare($query);
        		  $stmt->execute();
        		  
        		  return $stmt;
	}//end of get status

	public function getHodView($hodid, $dept, $cat){
		#Query to select leave details of the $this staff
          $query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept, l.datecreated
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.dept = '$dept' 
          AND st.staffid != '$hodid' 
          AND l.leavestatus = 'Submitted'
          AND l.leavestageid = '1'
          AND lt.role = 'Applicant'
          AND st.category = '$cat'
          ORDER BY lt.timeviewed DESC";

          $stmt = $this->db->prepare($query);
          $stmt->execute();

          return $stmt;  
	}//end of getHodView

	public function getDeanView($deanid, $kol, $cat){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept, l.datecreated
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.kol = '$kol' 
          AND st.staffid != '$deanid' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '2'
          AND lt.role = 'Hod'
          AND st.category = '$cat'
          ORDER BY lt.timeviewed DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;  
	}//end of getDeanView

	public function getHrView($hro){

		$query = "
				  SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept, l.datecreated
		          FROM leavetransaction AS lt
		          INNER JOIN leaveapplication AS l
		          ON lt.appno = l.appno
		          INNER JOIN stafflst AS st
		          ON st.staffid = l.staffid
		          WHERE st.staffid != '$hro' 
		          AND l.leavestatus = 'Recommended'
		          AND l.leavestageid = '3'
		          AND lt.role = 'Dean'
		          ORDER BY lt.timeviewed DESC
		        ";


        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}//end of getHrView

	public function getRegView($rego){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept, l.datecreated
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.staffid != '$rego' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '4'
          AND lt.role = 'HR'
          ORDER BY lt.timeviewed DESC";
          
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;

	}//end of getRegView->Regisrars view

	public function getVcView($vco){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, lt.recstartdate, lt.recenddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept, l.datecreated
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.staffid != '$vco' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '5'
          AND lt.role = 'Registrar'
          AND st.category = 'ACS'
          ORDER BY lt.timeviewed DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function leaveDetails($appno){

		$query = "
					SELECT st.sname, st.fname, l.staffid, l.leavetype, l.startdate, l.enddate, l.phone, l.reason, l.officer1, l.officer2, l.officer3, st.post, st.dept, st.kol, st.unitprg, st.category
                       FROM leaveapplication AS l
                       INNER JOIN stafflst AS st
                       ON st.staffid = l.staffid
                       WHERE appno = $appno
                   ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        //$row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
	}

	function leaveProgress($appno){

		$trqry = "SELECT *
                  FROM leavetransaction
                  WHERE appno = $appno 
                  AND transactionid > 1
                  ORDER BY transactionid ASC";

        $stmtr = $this->db->prepare($trqry);
        $stmtr->execute();

        return $stmtr;
	}

	public function leaveHistory($appno){

		$hquery = "SELECT st.fname, st.sname, ap.appno, ap.leavetype, ap.apstartdate, ap.apenddate, ap.resumeddate 
                   FROM approvedleaves AS ap
                   INNER JOIN stafflst As st 
                   ON ap.staffid = st.staffid
                   WHERE ap.staffid LIKE (SELECT staffid FROM leaveapplication WHERE appno = $appno)";
        $hstmt = $this->db->prepare($hquery);
        $hstmt->execute();

        return $hstmt;

	}
  
  public function getApprovedDates($appno){
    #this function gets the approved date for an appno
    $chkdtqry = "SELECT * FROM leavetransaction WHERE appno LIKE '$appno' AND status = 'Approved'";
    
        $chkstmt1 = $this->db->prepare($chkdtqry);
        $chkstmt1->execute();

        return $chkstmt1;
  }
	public function leaveRec($staffid, $staffcat, $hodid, $deanid, $rego, $hro, $vco){

		if (($staffid == $rego) &&  ($staffcat == 'NTS'))
            {
              $recqry = "SELECT recctitle, reccgroup
                            FROM leaverecommendations
                            WHERE reccgroup = 2";            
            }

            else if(($staffid == $hodid) || ($staffid == $deanid) || ($staffid == $rego) || ($staffid == $hro))
            {
                $recqry = "SELECT recctitle, reccgroup
                            FROM leaverecommendations
                            WHERE reccgroup = 1";              
            } 
          
            else if ($staffid == $vco) 
            {
              $recqry = "SELECT recctitle, reccgroup
                            FROM leaverecommendations
                            WHERE reccgroup = 2";             
            }

            $recstmt = $this->db->prepare($recqry);
            $recstmt->execute();

            return $recstmt;
	}

	public function extractRecc($appno){

		$chkdtqry = "SELECT recstartdate, recenddate FROM leavetransaction 
                     WHERE appno = '$appno' 
                     ORDER BY `sn` DESC
                     LIMIT 1";

        $chkstmt1 = $this->db->prepare($chkdtqry);
        $chkstmt1->execute();

        return $chkstmt1;
	}

	public function checkSupervisor1($appno, $staffid){		
		$chkqry = "SELECT * FROM leavetransaction 
                   WHERE appno LIKE '$appno' 
                   AND tstaffid LIKE '$staffid' ORDER BY `sn` ASC";


        $chkstmt1 = $this->db->prepare($chkqry);
        $chkstmt1->execute();

        return $chkstmt1;
	}

	public function getApprovedLeaves(){

		$query ="
              SELECT st.fname, st.sname, st.dept, al.staffid, al.appno, al.leavetype, al.apstartdate, al.apenddate, al.location, al.phone, al.releaseddate, l.datecreated, l.reason 
              FROM approvedleaves AS al 
              INNER JOIN stafflst AS st  
              ON st.staffid = al.staffid
              INNER JOIN leaveapplication as l
              ON l.appno = al.appno
              WHERE al.releaseddate = '' 
              ORDER BY l.datecreated DESC
                ";


        $stmt = $this->db->prepare($query);
        $stmt->execute();  

        return $stmt;
	}


	public function getRecHr(){

		$recqry = "SELECT recctitle, reccgroup
                                FROM leaverecommendations
                                WHERE reccgroup = 3";            
                    
        $recstmt = $this->db->prepare($recqry);
        $recstmt->execute();

        return $recstmt; 
	}

	public function staffDetailsforHR(){
		$query ="SELECT st.fname, st.sname, st.dept, al.staffid, al.appno, al.leavetype, al.apstartdate, al.apenddate, al.location, al.phone, al.releaseddate, al.resumeddate
                    FROM approvedleaves AS al
                    INNER JOIN stafflst AS st
                    ON st.staffid = al.staffid        
                    ORDER BY al.appno DESC";


        $stmt = $this->db->prepare($query);
        $stmt->execute();  

        return $stmt;
	}

	public function getResumedStaff(){

		$query ="SELECT st.fname, st.sname, st.dept, al.staffid, al.appno, al.leavetype, al.apstartdate, al.apenddate, al.location, al.phone, al.releaseddate, al.resumeddate
                    FROM approvedleaves AS al
                    INNER JOIN stafflst AS st
                    ON st.staffid = al.staffid
                    WHERE al.resumeddate != ''      
                    ORDER BY al.appno DESC";


        $stmt = $this->db->prepare($query);
        $stmt->execute(); 

        return $stmt; 
	}

	public function getOverstayedStaff(){
		$query ="SELECT st.fname, st.sname, st.dept, al.staffid, al.appno, al.leavetype, al.apstartdate, al.apenddate, al.location, al.phone, al.releaseddate, al.resumeddate
                    FROM approvedleaves AS al
                    INNER JOIN stafflst AS st
                    ON st.staffid = al.staffid       
                    WHERE al.resumeddate = ' '";


        $stmt = $this->db->prepare($query);
        $stmt->execute();
		return $stmt;
	}

	public function getDetailsByCategory($appno, $cat){

		$query = "	SELECT *
          			FROM stafflst AS s
          			INNER JOIN leaveapplication AS l
	                  ON s.staffid = l.staffid
	                  INNER JOIN stafflist AS st
	                  ON s.staffid = st.staffid
	                  INNER JOIN leavetransaction AS lt
	                  ON l.appno = lt.appno
	                  INNER JOIN approvedleaves AS ap
	                  ON ap.staffid = s.staffid
	                  WHERE l.appno = '$appno' 
	                  AND s.category = '$cat'
	                  AND ap.resumeddate = ''
	                  ORDER BY lt.timeviewed DESC
	                  LIMIT 1
	             ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function getDetailsByStaffid($staffid, $cat){

		$query = "	SELECT *
          			FROM stafflst AS s
          			INNER JOIN leaveapplication AS l
                  	ON s.staffid = l.staffid
                  	INNER JOIN stafflist AS st
                  	ON s.staffid = st.staffid
                  	INNER JOIN leavetransaction AS lt
                  	ON l.appno = lt.appno
                  	INNER JOIN approvedleaves AS ap
                  	ON ap.staffid = s.staffid
                  	WHERE l.staffid = '$staffid' 
                  	AND s.category = '$cat'
                  	AND ap.resumeddate = ''
                  	ORDER BY lt.timeviewed DESC
                  	LIMIT 1
	             ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function getResumptionViewHOD($dept, $hodid, $cat){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          INNER JOIN approvedleaves AS ap
          ON ap.appno = lt.appno
          WHERE st.dept = '$dept' 
          AND st.staffid != '$hodid' 
          AND lt.status = 'Resumed'
          AND lt.role = 'Applicant'
          AND lt.remarks = ''
          AND ap.resumeddate = ''
          AND st.category = '$cat'
          ORDER BY lt.timeviewed DESC";

          $stmt = $this->db->prepare($query);
          $stmt->execute();

          return $stmt;
	}

	public function getResumptionViewDean($kol, $deanid, $cat){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.kol = '$kol' 
          AND st.staffid != '$deanid' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '2'
          AND lt.role = 'Hod'
          AND st.category = '$cat'
          ORDER BY lt.timeviewed DESC";

        $stmt = $con->prepare($query);
        $stmt->execute();

        return $stmt;
	}


	public function getResumptionViewHR($hro){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.staffid != '$hro' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '3'
          AND lt.role = 'Dean'
          ORDER BY lt.timeviewed DESC";


        $stmt = $this->db->prepare($query);
        $stmt->execute();  

        return $stmt;
	}


	public function getResumptionViewReg($rego){

		 $query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.staffid != '$rego' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '4'
          AND lt.role = 'HR'
          ORDER BY lt.timeviewed DESC";
          
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function getResumptionViewVC($vco){

		$query = "SELECT lt.timeviewed, l.staffid, lt.appno, lt.tstaffid, l.leavetype, l.reason, l.startdate, l.enddate, l.location, lt.remarks, lt.status, st.coldirid, st.hod, st.dean, st.dept
          FROM leavetransaction AS lt
          INNER JOIN leaveapplication AS l
          ON lt.appno = l.appno
          INNER JOIN stafflst AS st
          ON st.staffid = l.staffid
          WHERE st.staffid != '$vco' 
          AND l.leavestatus = 'Recommended'
          AND l.leavestageid = '5'
          AND lt.role = 'Registrar'
          AND st.category = 'ACS'
          ORDER BY lt.timeviewed DESC";

          
     
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function getStaffRelease($staffid){

		$query = "SELECT releaseddate, resumestatus, resumeddate
                        FROM approvedleaves
                          WHERE staffid LIKE '$staffid'
                          AND resumestatus = 0";
              
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt;

	}

	public function getDashboardOfficer($cursession){

		$qryreg = "
                        SELECT officer, recommendation
                            FROM leavescheduletransaction
                                WHERE session = '$cursession'
                        ";
              
            $stm = $this->db->prepare($qryreg);
            $stm->execute();

            return $stm;

	}

	public function getSchedule($cursession){

		$query1 = "SELECT *
                      FROM leaveschedule
                      WHERE session = '$cursession'";

                    $stmt1 = $this->db->prepare($query1);
                    $stmt1->execute(); 

                    return $stmt1;
	}

	public function isExistSchedule($cursession){

		$qry = "SELECT * FROM leaveschedule 
          WHERE session = '$cursession' ";

        $stmt = $this->db->prepare($qry);
        $stmt->execute();

        return $stmt;
	}

	public function getAnnualLeaveSchdule(){

		$query = "SELECT st.staffid, CONCAT(sname,\" \", fname) AS staffname, st.title, st.post, st.level, st.dept, st.employmentdate,
                  daysworked(st.employmentdate) AS daysworked, 
                  daysentitled(st.level) AS daysentitled,
                  IFNULL(daystaken.daystaken, 0) AS daysgone, 
                  permissible(daysentitled(st.level), IFNULL(daystaken.daystaken, 0)) AS permissibledays, 
                  leavebonus(st.monthlybasic, daysworked(st.employmentdate)) AS leavebonus, 
                  st.bankacct, 
                  st.bankname
                  FROM stafflst AS st
                  LEFT JOIN daystaken
                  ON st.staffid = daystaken.staffid";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;  

	}

	public function isLeaveAppExist($staffid, $leavetype){

		$query = "	SELECT staffid, leavetype, leavestatus
						FROM leaveapplication 
							WHERE staffid = '$staffid'
								AND leavetype = '$leavetype' 
									AND leavestatus != 'Approved'
				";


		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	public function leaveReport($cursession){

		$query = "SELECT *
          FROM leaveschedule
          WHERE session = '$cursession'";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
	}

	public function leaveReportHR($cursession){

		$hrqry = "
            SELECT * FROM leavescheduletransaction
            WHERE session = '$cursession' 
            AND officer = 'HR'
            AND comment = 'Note Sent'
          ";

        $hrstm = $this->db->prepare($hrqry);
        $hrstm->execute();

        return $hrstm;  
	}

	public function leaveReportReg($cursession){

		$qrydfs = "SELECT * FROM leavescheduletransaction
          WHERE session = '$cursession' 
          AND officer = 'Registrar'";

        $stmdfs = $this->db->prepare($qrydfs);
        $stmdfs->execute(); 

        return $stmdfs;
	}

	public function leaveReportDFS($cursession){

		$qryvco = "SELECT * FROM leavescheduletransaction
          WHERE session = '$cursession' 
          AND officer = 'DFS'";

        $stmvco = $this->db->prepare($qryvco);
        $stmvco->execute(); 

        return $stmvco; 
	}

	public function leaveReportVC($cursession){

		$qryvc = "
                  SELECT * FROM leavescheduletransaction
                  WHERE session = '$cursession' 
                ";

        $stmvc = $this->db->prepare($qryvc);
        $stmvc->execute();

        return $stmvc;
	}

	public function leaveReportIsVC($cursession){

		$vcqry = "SELECT * FROM leavescheduletransaction
          WHERE session = '$cursession' 
          AND officer = 'VC'";

        $vcstm = $this->db->prepare($vcqry);
        $vcstm->execute();

        return $vcstm;
	}

public function resumptionday($edate)
{
	$dayOfWeek = date("l", strtotime($edate));

	if($dayOfWeek == 'Friday')
		{
			//$resumption = "Leave ends on ".date_format(date_create($edate), 'l, d-M-Y'). "<br>";
			$date = date_create($edate);
			date_modify($date, '+3 day');
			$resumption = "". date_format($date, 'Y-m-d');

			return $resumption;
		}

		elseif ($dayOfWeek == 'Saturday') 
		{
		    //$resumption = "Leave ends on ".date_format(date_create($edate), 'l, d-M-Y'). "<br>";
		    $date = date_create($edate);
		    date_modify($date, '+2 day');
		    $resumption = "". date_format($date, 'Y-m-d');

		    return $resumption;
		}

		else
		{
		    //$resumption = "Leave ends on ".date_format(date_create($edate), 'l, d-M-Y'). "<br>";
		    $date = date_create($edate);
		    date_modify($date, '+1 day');
		    $resumption = "". date_format($date, 'Y-m-d');

		    return $resumption;
		}
}//resumption day end


		public function checkLeaveStatus($id)
		{
			$qry = "SELECT appstatus
					FROM leaveapp
					WHERE staffid = '$id'
					LIMIT 0,1";

			$stmt = $this->db->prepare($qry);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$status = $row['appstatus'];
			
			if ($status = 0) {
				echo "denied";
			} elseif ($status = 1) {
				echo "pending";
			} elseif ($status = 2){
				echo "approved";
			} else {
				echo "The staff has no application in the database";
			}
				
		}//end of public function check leave status

public function leavedaysallowed($staffid, $leavetype)
{
	
	if($leavetype == 'casual')
	 {
		  	$ndays = (int)7;
	 }


	else if($leavetype == 'maternity')
	 {
		  	$ndays = 98;
	 }

	else if ( $leavetype == 'annual' )
	{
		    $query = "SELECT level FROM stafflst WHERE staffid = '$staffid'";
		    $stmt = $this->db->prepare($query);
		    $stmt -> execute();
		    $row = $stmt->fetch(PDO::FETCH_ASSOC);

		    $level = $row['level'];

		    if((int)$level >= 12)
		    {
		    	$ndays = 30;
		    }
		    else if((int)$level >= 10)
		    {
		    	$ndays = 21;
		    }
		    else {
		    	$ndays = 14;
		    }
	}//////////////////////////
	else{
		$ndays = 0;
	}

	return $ndays;
}//end of public function leavedays

public function leavedaysgone($staffid, $currentsession, $leavetype)
{
	
            	$hquery = "SELECT ap.apstartdate, ap.apenddate 
		                   FROM approvedleaves AS ap
		                   WHERE ap.staffid LIKE '$staffid'
		                   AND ap.session = '$currentsession'
		                   AND ap.leavetype = '$leavetype'";

		        $hstmt = $this->db->prepare($hquery);
		        $hstmt->execute();      
		        $hnum = $hstmt->rowCount(); 
		     
		        $days = array();
	            $i = 1;
	            $leavedaystotal = 0;
	            while ($row = $hstmt->fetch(PDO::FETCH_ASSOC))
	            {  
		            $date1 = $row['apstartdate'];
		            $date2 = $row['apenddate']; 
		            $days[$i] = (int)numdays($date1, $date2);
		            $leavedaystotal += $days[$i];   
		            ++$i;//increment counter
		          }

		         return $leavedaystotal;
}//end of public function leavedaysdone

public function casualleavedaysgone($staffid, $currentsession)
{

            	$hquery = "SELECT ap.apstartdate, ap.apenddate 
		                   FROM approvedleaves AS ap
		                   WHERE ap.staffid LIKE '$staffid'
		                   AND ap.leavetype = 'casual'
		                   AND ap.session = '$currentsession'";

		        $hstmt = $this->db->prepare($hquery);
		        $hstmt->execute();      
		        $hnum = $hstmt->rowCount(); 
		     
		        $days = array();
	            $i = 1;
	            $leavedaystotal = 0;
	            while ($row = $hstmt->fetch(PDO::FETCH_ASSOC))
	            {  
		            $date1 = $row['apstartdate'];
		            $date2 = $row['apenddate']; 
		            $days[$i] = (int)numdays($date1, $date2);
		            $leavedaystotal += $days[$i];   
		            ++$i;//increment counter
		          }

		         return $leavedaystotal;
}//end of public function leavedaysdone

public function annualleavedaysgone($staffid, $currentsession)
{
	

            	$hquery = "SELECT ap.apstartdate, ap.apenddate 
		                   FROM approvedleaves AS ap
		                   WHERE ap.staffid LIKE '$staffid'
		                   AND ap.leavetype = 'annual'
		                   AND ap.session = '$currentsession'";

		        $hstmt = $this->db->prepare($hquery);
		        $hstmt->execute();      
		        $hnum = $hstmt->rowCount(); 
		     
		        $days = array();
	            $i = 1;
	            $leavedaystotal = 0;
	            while ($row = $hstmt->fetch(PDO::FETCH_ASSOC))
	            {  
		            $date1 = $row['apstartdate'];
		            $date2 = $row['apenddate']; 
		            $days[$i] = (int)numdays($date1, $date2);
		            $leavedaystotal += $days[$i];   
		            ++$i;//increment counter
		          }

		         return $leavedaystotal;
}//end of public function leavedaysdone

public function getname($id)
{
		$qry = "SELECT sname, mname, fname
				FROM stafflist
				WHERE staffid = '$id'";

		$stmtname = $this->db->prepare($qry);
		$stmtname->execute();

		$row = $stmtname->fetch(PDO::FETCH_ASSOC);

		$name = $row['sname']." ".$row['mname']." ".$row['fname'];

		return $name;

}//end of public function getname

public function getunitprgid($id){
	
	

	$qry = "SELECT unitprgid 
        	  FROM stafflst
			  WHERE staffid = '$id'";

    $stmt = $this->db->prepare($qry);
    $stmt->execute();
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	$unitprgid = $row['unitprgid'];

	return $unitprgid;

}//end of public function to get unit/program ID

public function getcolid($id){
	
	

	$qry = "SELECT coldirid	 
        	  FROM stafflst
			  WHERE staffid = '$id'";

    $stmt = $this->db->prepare($qry);
    $stmt->execute();
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	$coldirid = $row['coldirid'];

	return $coldirid;

}//end of public function to get unit/program ID

public function getkol($id){
	$qry = "SELECT kol	 
        	  FROM stafflst
			  WHERE staffid = '$id'";

    $stmt = $this->db->prepare($qry);
    $stmt->execute();
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	$kol = $row['kol'];

	return $kol;

}

public function getdept($id){

	$qry = "SELECT dept	 
        	  FROM stafflst
			  WHERE staffid = '$id'";

    $stmt = $this->db->prepare($qry);
    $stmt->execute();
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	$dept = $row['dept'];

	return $dept;

}

public function isHod($staffid){

		$qry = "SELECT staffid
				FROM stafflst
				WHERE hod = '$staffid'";

		$stmt = $this->db->prepare($qry);
		$stmt->execute();
			 if($stmt->rowCount() >= 1)
			{
				return true;
			}
			else
			{
				return false;
			}
	}//end of public function isHod

public function isdean($staffid){

		$qry = "SELECT staffid
				FROM stafflst
				WHERE '$staffid' = dean";

		$stmt = $this->db->prepare($qry);
		$stmt->execute();
		if($stmt->rowCount() >= 1)
			{
				return true;
			}
			else
			{
				return false;
			}
	}//end of public function isHod

public function getstaffidbyappno($appno){

	$qry = "SELECT staffid
        	  FROM leaveapplication
			  WHERE appno = '$appno'";

    $stmt = $this->db->prepare($qry);
    $stmt->execute();
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	$staffid = $row['staffid'];

	return $staffid;

}//end of public function to get staffid by appno in leaveapplication tabless

	public function randomString($length) {
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}//end of public function random string

	public function randomID($length) {
		$str = "";
		$characters = array_merge(range('A','Z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}//end of public function randomID


	public function appNo($length) {
		$str = "";
		$characters = array_merge(range('0','9'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}//end of public function randomID

	public function serAppno(){

		$query = "SELECT appno
                  FROM leaveapplication
                  ORDER BY sn
                  DESC LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt -> execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
        	 $row = $stmt->fetch(PDO::FETCH_ASSOC);
        	 $appno = sprintf('%09d', ($row['appno'] + 1));
	       	 return $appno;
        
        } else {

        	return $appno = "000000001";
        }//end of if else      
	}//end of public function serAppno

	public function transactionNo(){
		$query = "SELECT transactionNo
                  FROM leavescheduletransaction
                  ORDER BY sn
                  DESC LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt -> execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
        	 $row = $stmt->fetch(PDO::FETCH_ASSOC);
        	 $transactionNo = sprintf('%09d', ($row['transactionNo'] + 1));
	       	 return $transactionNo;
        
        } else {

        	return $transactionNo = "000000001";
        }//end of if else      
	}//end of public function transactionNo

	public function test_input($data) {
		$data = trim($data);
		$data = addslashes($data);
		$data = htmlspecialchars($data);
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		return $data;
	}//end of public function test_input

	
	public function get_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		return $data;
	}//end of public function get_input

	public function trackid($appno){
		
		

		$qry = "SELECT MAX(transactionid) AS transaction
				FROM leavetransaction
				WHERE appno = '$appno'";

		$stmt= $this->db->prepare($qry);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$track = $row['transaction'];

		return $track;

	}//end of trackid

public function getstatusbyID($id)
{
			$query = "SELECT st.fname, st.sname, s.dept, s.hod, l.staffid, l.leavetype, l.reason, l.startdate, l.enddate, l.leavestatus, l.appno, lt.tstaffid, lt.comment, lt.transactionid, lt.recstartdate, lt.recenddate, lt.status, lt.timeviewed
				  FROM stafflst AS s
                  INNER JOIN leaveapplication AS l
                  ON s.staffid = l.staffid
                  INNER JOIN stafflist AS st
                  ON s.staffid = st.staffid
                  INNER JOIN leavetransaction AS lt
                  ON l.appno = lt.appno
                  WHERE l.staffid = '$id'";

        	$stmt = $this->db->prepare($query);
        	$stmt->execute(); 

        	$row=$stmt->fetch(PDO::FETCH_ASSOC);

        	return $row;

}

	public function getstaffnames($id)
	{
		$query = "";

        	$stmt = $this->db->prepare($query);
        	$stmt->execute(); 

        	$row=$stmt->fetch(PDO::FETCH_ASSOC);

        	return $row;
        

	}

	public function getstatus1($appno){

		$qry = "SELECT status
				FROM leavetransaction
				WHERE appno = '$appno'
				ORDER BY transactionid DESC
				LIMIT 1";

		$stmt = $this->db->prepare($qry);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$status = $row['status'];

		//use status to get recomendation

		$qry1 = "SELECT rectitle
				FROM leaverecommendation
				WHERE recid = '$status'";

		$stmt1= $this->db->prepare($qry1);
		$stmt1->execute();

		$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

		$recomendation = $row1['rectitle'];

		return $recomendation; //recommendations in the database gives the status of the application.

	}

	
	public function cleandata($formdata){
			$arrlength=count($formdata);

			for($x = 0; $x < $arrlength; $x++)
		  	{
		  		 $formdata[$x] = trim($formdata[$x]);
		  		 $formdata[$x] = stripslashes($formdata[$x]);
		  		 $formdata[$x] = filter_var($formdata[$x], FILTER_SANITIZE_STRING);  		
		  	}

		  	return $formdata;
	}//end of public function clean data

	public function numdays($date1, $date2)
	{
		$stdate = date_create($date1);
		$eddate = date_create($date2);
        
        $diff = date_diff($stdate,$eddate);
        $ndays = $diff->format("%r%a ");

        return $ndays;
	}//end of public function number of days

	public function decoder($words)
	{
            echo base64_encode($words);
            echo base64_decode($words); 

	}//end of public function to encode and decode words 
	
}

?>