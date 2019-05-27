<?php

    # Check for department of the viewing personnel
    //get staff name
    //get staff department
    //link staff to supervisors

    //After testing for department
    //This page gets leave application of staff based on department
  /***********************************************************************************************************************************************************************/
  include 'config/database.php';
  include 'leavefunction.php';

  checksession();

  
  
  $cursession = '2018/2019';  
  $transactionDate = date('Y-m-d');
  $transaactionNo = transactionNo();

  $officer = "HR";
  $recc = "Presented";
  $comment = "Presented";
  $action = "Presented";

  $qry = "INSERT INTO leavescheduletransaction (transactionDate, transactionNo, session, officer, recommendation, comment, action) VALUE (:transactionDate, :transactionNo, :session, :officer, :recommendation, :comment, :action)";
                           
            $stm = $con -> prepare($qry);
            $stm->bindparam(':transactionDate', $transactionDate);
            $stm->bindparam(':transactionNo', $transaactionNo);
            $stm->bindparam(':session', $cursession);
            $stm->bindparam(':officer', $officer);
            $stm->bindparam(':recommendation', $recc);
            $stm->bindparam(':comment', $comment);
            $stm->bindparam(':action', $action);

            $stm->execute();

 try 
      {
        #Query to select leave details of the $this staff
        $query = "SELECT st.staffid, st.fname, st.sname, st.title, st.post, st.level, st.dept, st.employmentdate, st.monthlybasic, ap.apstartdate, ap.apenddate, ap.session, ap.leavetype
          FROM stafflst AS st
          LEFT JOIN approvedleaves AS ap
          ON st.staffid = ap.staffid";

          

        $stmt = $con->prepare($query);
        $stmt->execute();  

        $num = $stmt->rowCount();
    
       if ($num > 0) { //if starts here
          $n = 1;
          
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                    //$n++;
                      //$lbonus = (int)number_format(mt_rand(1000, 1000000));
                      $empdate = strtotime($row['employmentdate']);

                      $staffname = getname($row['staffid']);
                      $title = $row['title'];
                      $staffid = $row['staffid'];
                      $post = $row['post'];
                      $progunit = $row['dept'];
                      $level = $row['level'];
                      $employmentdate = $row['employmentdate'];

                     
                      $today = date('Y-m-d');
                    
                      if((int)numdays($row['employmentdate'], $today) > 365)
                      {
                        //echo "<td>365</td>";
                        $workedDays = 365;
                        $lbonus = 1.2 * $row['monthlygross'];

                        //echo "<td>".(int)numdays($row['employmentdate'], $today)."</td>";
                      }
                      else
                      {
                          //  echo "<td>".numdays($row['employmentdate'], $today)."</td>";
                            $workedDays = (int)numdays($row['employmentdate'], $today);
                            $lbonus = 1.2 * $row['monthlygross'] * ($workedDays/365);
                      }
                         
                      $lda = leavedaysallowed($row['staffid'],'annual');

                      $leavedaysgone = annualleavedaysgone($row['staffid'], $cursession);
                      
                      $ldg = annualleavedaysgone($row['staffid'], $cursession);

                      $leaveallowed = (int)leavedaysallowed($row['staffid'], 'annual');//total number of days allowed for any staff
                      $ndays = numdays($row['apstartdate'], $row['apenddate']);

                      $dayspermissible = $leaveallowed - $leavedaysgone;
                      
                      //$lbonus = 'Leave Bonus';
                      $bnkacct = 'Bank Account';
                      $bnkname = 'Bank Name';

                      //echo $progunit;;

                      $query1 = "INSERT INTO leaveschedule (session, title, staffname, staffid, post, progunit, level, empdate, daysworked, daysentitled, daysgone, dayspermissible, leavebonus, bankacctno, bankname) VALUE (:cursession, :title, :staffname, :staffid, :post, :progunit, :level, :empdate, :daysworked, :daysentitled, :daysgone, :dayspermissible, :leavebonus, :bankacctno, :bankname)";

                    try {

                          $stmt1 = $con -> prepare($query1);

                          $stmt1->bindparam(':cursession', $cursession);
                          $stmt1->bindparam(':title', $title);
                          $stmt1->bindparam(':staffname', $staffname);
                          $stmt1->bindparam(':staffid', $staffid);
                          $stmt1->bindparam(':post', $post);
                          $stmt1->bindparam(':progunit', $progunit);
                          $stmt1->bindparam(':level', $level);
                          $stmt1->bindparam(':empdate', $employmentdate);
                          $stmt1->bindparam(':daysworked', $workedDays);
                          $stmt1->bindparam(':daysentitled', $lda);
                          $stmt1->bindparam(':daysgone', $ldg);
                          $stmt1->bindparam(':dayspermissible', $dayspermissible);
                          $stmt1->bindparam(':leavebonus', $lbonus);
                          $stmt1->bindparam(':bankacctno', $bnkacct);
                          $stmt1->bindparam(':bankname', $bnkname); 

                          //print_r($stmt1);             

                          $stmt1->execute();
                          
                          //echo 'SUCCESS';                           

                      }//end of try
                      catch(PDOException $e){
                          echo "Error: " . $e->getMessage();
                      }//end of catch
                    

                }//end of while loop


            }//end of if statement for printing results into tables 
        else {
          echo "Data not inserted";
        }

  }//end of try
       catch(PDOException $e){
         echo "Error: " . $e->getMessage();
       }//end of catch

       header('Location: annualleavereport3.php');

       //echo $n;
?>
