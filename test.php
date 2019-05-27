<?php

	 include 'config/database.php';
  	 include 'leavefunction.php';

/*
  $edate = "2019-03-11";
  
  //$edd = date();

  $edd = date_create('2000-01-20');
  $eddbefore = date('Y-m-d', strtotime('-14 days', strtotime($edate)));
  $afterEdd = date('Y-m-d', strtotime('+84 days', strtotime($edate)));
  
  
  echo "<br>". $eddbefore;
  echo "<br>". $afterEdd;
  //echo date_format($eddbefore, 'Y-m-d'). "<br>";
  //echo date_format($afterEdd, 'Y-m-d');    


  
$date = date_create('2000-01-20');

echo date_format($date, 'Y-m-d');


$date = date_create('2000-01-01');

echo date_format($date, 'Y-m-d');


CREATE TABLE suppliers
  AS (SELECT companies.id, companies.address, categories.cat_type
      FROM companies, categories
      WHERE companies.id = categories.id
      AND companies.id > 1000);

SELECT Customers.CustomerName, Orders.OrderID
INTO CustomersOrderBackup2017
FROM Customers
LEFT JOIN Orders ON Customers.CustomerID = Orders.CustomerID;

#Query to select leave details of the $this staff

$query = "SELECT * FROM tablo";

$stmt = $con->prepare($query);
$stmt->execute();  

$num = $stmt->rowCount();

if( $stmt  && ( $num >= 0 ) ) 
{
	echo "Table Exist";
}
else
{
	echo "Table does not exist";
}
*/

CREATE TABLE suppliers
  AS (SELECT companies.id, companies.address, categories.cat_type
      FROM companies, categories
      WHERE companies.id = categories.id
      AND companies.id > 1000);

SELECT Customers.CustomerName, Orders.OrderID
INTO CustomersOrderBackup2017
FROM Customers
LEFT JOIN Orders ON Customers.CustomerID = Orders.CustomerID;

SELECT st.staffid, st.fname, st.sname, st.title, st.post, st.level, st.dept, st.employmentdate, ap.apstartdate, ap.apenddate, ap.session
          FROM stafflst AS st
          INNER JOIN approvedleaves AS ap
          ON st.staffid= ap.staffid
          WHERE ap.leavetype = 'annual'
          AND ap.resumed = 1
          AND ap.session = '$cursession'

FROM subscribers 

#Query to select leave details of the $this staff

$query = "INSERT INTO leaveschedule
		  SELECT ap.session AS session, st.title AS title, st.fname AS fname, st.sname AS sname, st.staffid AS staffid, st.post AS post, st.dept AS progunit, st.level AS level, st.employmentdate AS empdate
		  FROM stafflst As st
		  LEFT JOIN approvedleaves AS ap ON st.staffid = ap.staffid
		  WHERE ap.leavetype = 'annual'
          AND ap.resumed = 1
          AND ap.session = '2018/2019'";

$stmt = $con->prepare($query);
$stmt->execute();  

$num = $stmt->rowCount();

?>