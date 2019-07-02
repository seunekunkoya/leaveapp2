<?php 
include "include/config.php";

$currentsession = $lvobj->addSlash($lvobj->getSession()); 
$leavetype = 'annual';

	$query = "SELECT st.staffid, CONCAT(sname,\" \", fname) AS staffname, st.title, st.post, st.level, st.dept, st.employmentdate, st.category, st.kol, st.unitprg,IFNULL(dy.daystaken, 0) AS daysgone, st.monthlybasic, st.bankacct, st.bankname
             FROM stafflst AS st
               LEFT JOIN daystaken AS dy
                 ON st.staffid = dy.staffid
                  ORDER BY st.category, st.kol, st.dept, st.unitprg, staffname";

    $stmt = $con->prepare($query);
    $stmt->execute();  

    //print_r($stmt->fetchall(PDO::FETCH_ASSOC));
echo "<table>";
    echo "<tr align ='center'>";
            echo "<th> No</th>";
            echo "<th> Category</th>";
            echo "<th> College /<br/ > Directorate</th>";
            echo "<th> Department</th>";
            echo "<th> Unit/Program</th>";
            echo "<th> Staff Name</th>";
            echo "<th> Title</th>";
            echo "<th> Staffid</th>";
            echo "<th> Post</th>";
            echo "<th> CUSS</th>";
            echo "<th> Employment Resumption Date</th>";
            echo "<th> Days Worked in the Year</th>";
            echo "<th> Days Entitled</th>";
            echo "<th> Days Already Taken</th>";
            echo "<th> Days Permissible</th>";
            echo "<th> Leave Bonus</th>";
            echo "<th> Bank Account</th>";
            echo "<th> Bank Name</th>";
         echo "</tr>";
          $schedule = array();
          $n = 1;
          $totalbonus = 0;
          while($row = $stmt->fetch(PDO::FETCH_ASSOC))         
                {             
                  $date1 = $row['employmentdate'];
                  $date2 = date("Y-m-d");

                  $employmentdate = strtotime($row['employmentdate']);
                  $daysworked = $lvobj->numdays($date1, $date2);
                  $lbonus = $lvobj->leaveBonus($row['monthlybasic'], $daysworked);
                 
                   echo "<tr>";
                      echo "<td>".$n++."</td>";
                      echo "<td class = 'tdat'>".$row['category']."</td>";
                      echo "<td class = 'tdat'>".$row['kol']."</td>";  
                      echo "<td class = 'tdat'>".$row['dept']."</td>";
                      echo "<td>".$row['unitprg']."</td>";   
                      echo "<td>".$row['staffname']."</td>";
                      echo "<td class = 'tdat'>".$row['title']."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".$row['post']."</td>";
                      echo "<td class = 'tdat'>".$row['level']."</td>";
                      echo "<td>".date('j M, Y', $employmentdate)."</td>";
                      echo "<td class = 'tdat'>".$lvobj->getDaysWorked($daysworked)."</td>";
                      echo "<td class = 'tdat'>".$lvobj->getEntitleDays($row['staffid'])."</td>"; 
                      echo "<td class = 'tdat'>".$row['daysgone']."</td>";
                      echo "<td class = 'tdat'>".$lvobj->annualPermissibleDays($row['staffid'], $currentsession, $leavetype)."</td>";
                      echo "<td>".number_format($lbonus, 2)."</td>";
                      echo "<td>".$row['bankacct']."</td>";
                      echo "<td>".$row['bankname']."</td>";
                  echo "</tr>";

                  $totalbonus += $lbonus;
                }//end of while loop
                   echo "<tr>";
                      echo "<td colspan=\"15\"><b>TOTAL LEAVE BONUS IN NAIRA</b></td>";
                      echo "<td>".number_format($totalbonus,2)."</td>";
                      echo "<td></td>";
                      echo "<td></td>";
                   echo "</tr>";
         
       echo "</table>";


?>


