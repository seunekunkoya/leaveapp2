<?php

	date_default_timezone_set("Africa/Lagos");

	function get_currentmenu($loginid){

		include('covenantportalconfig.php');

		$prepare = "select sessionvalue, sessionname from sessions where sessionname in('menuname', 'menuid') and loginid='$loginid'";

		$stmt = $conn->prepare($prepare);

		$stmt->execute();

		return $stmt;

	}

	function get_unit($conn, $unit){

		include('covenantportalconfig.php');

		$stmtunit = $conn->prepare("(select null as col, null as dept, unitid as abbr, unit as location FROM units where if ('$unit' <> '', unitid in ('$unit'), unitid not in ('$unit')) and unitstatus = 1 and unitid not in (select colid from colleges))

		union all (select programs.kolid as col, programs.dip as dept, programs.prgid as abbr, programs.program as location from programs where if( '$unit' <> '', prgid in ('$unit'), prgid not in (0)) and programstatus = 1 order by kolid)

		union all (select departments.kolid as col, departments.dpid as dept, departments.dpid as abbr, concat_ws(' ', departments.department, 'department') AS location from departments where if( '$unit' <> '', dpid in ('$unit'), dpid not in ('$unit') )and departmentstatus = 1) 

		union all (select colleges.colid as col, null as dept, colid as abbr, concat_ws(' ', 'College of', college) as location from colleges where if( '$unit' <> '', colid in ('$unit'), colid not in ('$unit')) and collegestatus = 1)order by col, dept");

		//print_r($stmtunit);

		$stmtunit->execute();

		$countunit = $stmtunit->rowCount();

		if($countunit > 0){

			$dataunit = $stmtunit->fetchAll();

			return $dataunit;

		}

		else{

			return "";

		}

	}

	function get_category($conn, $category = ''){

		include('covenantportalconfig.php');

		$stmt = $conn->prepare("select * from categories where if('$category' <> '', ct in ('$category'), ct not in (''))");

		//print_r($stmt);

		$stmt->execute();

		$count = $stmt->rowCount();

		if($count > 0){

			$data = $stmt->fetchAll();

			return $data;

		}

	}

	function get_gender($conn, $gender){

		include('covenantportalconfig.php');

		$stmt = $conn->prepare("select * from gender where gender in ('$gender')");

		$stmt->execute();

		$count = $stmt->rowCount();

		if($count > 0){

			$data = $stmt->fetchAll();

			return $data;

		}

	}

	function get_user($loginid){

		include('covenantportalconfig.php');

		if(is_numeric($loginid))

			$stmt = $conn->prepare("select * from current_staff where idno in (select userid from login where loginid in ('$loginid'))");

		else if(is_string($loginid))

			$stmt = $conn->prepare("select * from current_staff where idno in ('$loginid')");

		$stmt->execute();

		$count = $stmt->rowCount();

		if($count > 0 && ($loginid > 0 || $loginid != '')){

			$data = $stmt->fetchAll();

			return $data;

		}

		else if($count == 0 && !isset($_SESSION['loginid'])){

			$stmt1 = $conn->prepare("select * from vendorregister where vendorid = '$logind'");

			$stmt1->execute();

			$count1 = $stmt1->rowCount();

			if($count1 > 0){

				$data1 = $stmt1->fetchAll();

				return $data1;

			}

			else{

				return array();

			}

		}

		else{

			return array();

		}

	}

	function logguser($userid,$comment, $appid, $menuid)

	{

		include('covenantportalconfig.php');

		$date = date("Y-m-d H:i:s");

		if(isset($_SESSION['loginid'])){

			$staffid =implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));

		}

		$response = "";

		try{

			$strSQL1="insert into portallog (staffid,username,comments,date, appid, menuid) values('$staffid','$userid','$comment', '$date', '$appid', '$menuid')";

			//echo $strSQL1;

			$conn->exec($strSQL1);

			$last_id = $conn->lastInsertId();

			

			//echo "<br/>" .$strSQL1;

			//echo $staff;

			//echo $comment;

			//$result=mysql_query($strSQL1) or die('Error Logging !');

			$response ='done';

		}		

		catch(PDOException $e) {

			echo "Error: Loging Portal ";

		}

		//echo $strSQL1;

		return $response;

	}

	function test_input($data) {

		$data = trim($data);

		$data = addslashes($data);

		$data = htmlspecialchars($data);

		$data = filter_var($data, FILTER_SANITIZE_STRING);

		return $data;

	}

	function get_input($data) {

		$data = trim($data);

		$data = stripslashes($data);

		$data = filter_var($data, FILTER_SANITIZE_STRING);

		return $data;

	}

	function get_userrole($conn, $id){

		include('covenantportalconfig.php');

		$stmt = $conn-> prepare("select roleid from roles where status = 1 and roleid in (select roleid from userroles where loginid = $id and rolestatus = 1 and status = 1)");

		//$stmt = $conn->prepare("SELECT roleid FROM userroles where status = 1 and loginid=$id");

		//echo "select roledid from roles where status = 1 and roledid in (select roleid from usseroles where loginid = $id and rolestatus = 1 and status = 1)"; 

		$stmt->execute();

		$roles = array();

		while($data = $stmt->fetch()){

			array_push($roles, $data['roleid']);

		}

		return $roles;

}

?>			