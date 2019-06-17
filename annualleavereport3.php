<?php 
include "include/config.php";
$lvobj->checkSession();
$staffid = $_SESSION['staffid'];
//$staffid = implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));
$staffdetails = $lvobj->staffInfo($staffid);
$_SESSION['staffinfo'] = $staffdetails;
  $hro = $_SESSION['staffinfo']['hro'];
  $rego = $_SESSION['staffinfo']['rego'];
  $vco = $_SESSION['staffinfo']['vco'];  
  $dfs = $_SESSION['staffinfo']['dfs'];
  
  //echo $rego;
    $cursession = $lvobj->getSession();
    $slashedSession = $lvobj->addSlash($cursession);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $stmt = $lvobj->leaveReport($slashedSession);
    $num = $stmt->rowCount();
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /*Query to check if HR has made a comment*/
    $hrstm = $lvobj->leaveReportHR($slashedSession);
    $hrnum = $hrstm->rowCount();

        //$rowdfs = $hrstm->fetch(PDO::FETCH_ASSOC);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /*Query to check if registrar has made a comment*/
        $stmdfs = $lvobj->leaveReportReg($slashedSession); 
        $regnm = $stmdfs->rowCount();

        $rowdfs = $stmdfs->fetch(PDO::FETCH_ASSOC);
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to check if dfs has made a comment
        $stmvco = $lvobj->leaveReportDFS($slashedSession);
        $vconum = $stmvco->rowCount();

        $rowvco = $stmvco->fetch(PDO::FETCH_ASSOC);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to display comments for VC
        $stmvc = $lvobj->leaveReportVC($slashedSession);
        $vcnum = $stmvc->rowCount();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        #Query to check if vc has made a comment
        $vcstm = $lvobj->leaveReportIsVC($slashedSession);
        $numvc = $vcstm->rowCount();

        //$rowvco = $stmvco->fetch(PDO::FETCH_ASSOC); 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     
        
        
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/sc-2.0.0/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/sc-2.0.0/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/annual.css"/>
  <script src="js/table2excel.js"></script>
  
  <script type="text/javascript">
  $(document).ready(function() {
        $('#example').DataTable( {
            "scrollY": 600,
            "scrollX": true,
            "searching": false,
            "ordering": false,
            "paging": false,
            "info": false
        } );
  } );
</script>

</head>
<body>
<div class="container">
<h2 class="head">
  <?php echo $cursession; ?> Annual Leave Schedule
</h2>

<table id="example" class="display nowrap center-all" style="width:100%">
    
  <thead>
    <tr class="trow">
            <th> No</th>
            <th> Category</th>
            <th> College / Directorate</th>
            <th> Program/Unit</th>
            <th> Staff Name</th>
            <th> Title</th>
            <th> Staffid</th>
            <th> Post</th>
            <th> Proram/ Unit</th>
            <th> Salary Level</th>
            <th> Resumption Date</th>
            <th> Days Worked in the Year</th>
            <th> Days Entitled</th>
            <th> Days Already Taken</th>
            <th> Days Permissible</th>
            <th> Leave Bonus</th>
            <th> Bank Account</th>
            <th> Bank Name</th>
        </tr>
    </thead>
    <?php 
    if ($num > 0) { //if starts here
          $n = 1;
          
          $totalbonus = 0;
          while($row=$stmt->fetch(PDO::FETCH_ASSOC))         
                {
                    $lbonus = $row['leavebonus'];
                    $empdate = strtotime($row['empdate']);
                   
                   echo "<tr>";
                      echo "<td>".$n++."</td>";                       
                      echo "<td>".$row['category']."</td>";
                      echo "<td>".$row['coldean']."</td>";  
                      echo "<td>".$row['progunit']."</td>";    
                      echo "<td>".$row['staffname']."</td>";//$staffid = getname($row['staffid'])
                      echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['staffid']."</td>";
                      echo "<td>".$row['post']."</td>";
                      echo "<td>".$row['progunit']."</td>";
                      echo "<td>".$row['level']."</td>";
                      echo "<td>".date('j M, Y', $empdate)."</td>";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                     
                      $today = date('Y-m-d');
                    
                        if((int)$lvobj->numdays($row['empdate'], $today) > 365)
                        {
                          echo "<td>365</td>";
                        }
                        else
                        {
                          echo "<td>".
                                   $lvobj->numdays($row['empdate'], $today).
                                "</td>";
                        }
                         
                      echo "<td>".$row['daysentitled']."</td>";
                      
                      echo "<td>".$row['daysgone']."</td>";
                      echo "<td>".$row['dayspermissible']."</td>";
                      echo "<td>".number_format($lbonus, 2)."</td>";
                      #calculate total leave bonus
                      $totalbonus += (int)$lbonus;
                      echo "<td>".$row['bankacctno']."</td>";
                      echo "<td>".$row['bankname']."</td>";
                      
                  echo "</tr>";
                }//end of while loop
                }//end of if statement for printing results into tables 
        else {
          //echo "<tr>";
                    //echo "<td colspan=\"14\"> No Schedule Yet </td>";
                    echo "<p>No Leave Schedule Yet</p>";
          //echo "</tr>";
        }
    ?>
    </tbody>
</table>
</div><!--End of container -->

<div class="container pad">
  <div class="row pt-5">
    <div class="col-sm-6">
      <!-- <button class="btn_schedule">Print Schedule</button> -->
      <button class = "dsh_btn">
          <a style="font-size: 14px;" href="leavedashboard.php?id= <?php echo base64_encode($_SESSION['staffid']); ?>">Dashboard</a>
        </button>
        <button class = "dsh_btn export">Export To Excel</button>
        <?php //echo $rego ?>
    </div>
    <div class="col-sm-3">
      
    </div>
    <div class="col-lg-3">
      <table id="table_style">
        <tr>
          <th>Total Leave Bonus</th>
          <td> <b><?php echo " &nbsp;&nbsp;&nbsp;   ". number_format($totalbonus); ?> </b></td>
        </tr>
      </table>
    </div>
    
  </div>
</div>

<div class="container">  
<div class="row">

<?php 
  if($rego == $staffid) {
    if($regnm > 0)
    { 
      $message = '<div class="col-sm-3"> </div>
                  <div class="col-sm-8">
                  <h3 class="recommend">Note Sent.</h3>
                  </div>';
      echo $message;
    }
    else {
            //echo "Rego";
                $commentform = '
                    <div class="col-sm-3"> </div>
                    <div class="col-sm-8">
                    <h4 class="recommend">Enter Comment and Recommendation below</h4>
                    <div class="row">
                        <div class="col-sm-4">
                          <label>Comment</label>
                          <textarea id="comment"></textarea>
                        </div>
                        <div class="col-sm-3">
                          <label>Recommendation</label>
                          <select id="reccom">
                          <option></option>
                            <option>Recommended</option>
                            <option>Not Recommended</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn_style" id="regbtn">Save</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div> 
                ';
              echo $commentform;
    }
  }//end of registrar
  if($dfs == $staffid) {
    if($vconum > 0)
    { 
      $message = '<div class="col-sm-3"> </div>
                  <div class="col-sm-8">
                  <h3 class="recommend">Note Sent.</h3>
                  </div>';
      echo $message;
    }
    else {
      while($rowvc = $stmvc->fetch(PDO::FETCH_ASSOC))
        {  
         // echo $rowvc['comment'];
          if($rowvc['officer'] == 'Registrar')
          {
            $officer = 'Registrar';
          }
          else
          {
            continue;//moves the iteration 
            $officer = 'HR';
          }
    
            $prevComment = "
            <div class='row'>
                <div class='col-sm-3'> </div>
                <div class='col-sm-8'>
                  <div class='row'>
                    <div class='col-sm-4'>
                      <label>{$officer}'s Comment</label>
                      <p>{$rowdfs['comment']} </p>
                    </div>
                   <div class='col-sm-3'>
                      <label>Recommendation</label>
                      <p>{$rowvc['recommendation']}</p>
                    </div>
                  </div>
                </div>
               <div class='col-sm-1'>
               </div>
            </div>
            <hr>
          ";
          echo $prevComment;
      }//end of while loop
           $commentform = '
                    <div class="col-sm-3"> </div>
                    <div class="col-sm-8">
                       <h4 class="recommend">Enter Comment and Recommendation below</h4>
                    <div class="row">
                        <div class="col-sm-4">
                          <label>Comment</label>
                          <textarea id="comment"></textarea>
                        </div>
                        <div class="col-sm-3">
                          <label>Recommendation</label>
                          <select id="reccom">
                          <option></option>
                            <option>Recommended</option>
                            <option>Not Recommended</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn_style1" id="dfsbtn">Save</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div> 
                ';
              echo $commentform;
     }//end of vconum
  }//end of dfs
  if($vco == $staffid) {
    if($numvc > 0)
    {
      $message = '<div class="col-sm-3"> </div>
                  <div class="col-sm-8">
                  <h3 class="recommend">Note Sent.</h3>
                  </div>';
      echo $message;
    }
    else {
        while($rowvc = $stmvc->fetch(PDO::FETCH_ASSOC))
        {  
         // echo $rowvc['comment'];
          if($rowvc['officer'] == 'Registrar')
          {
            $officer = 'Registrar';
          }
          else if($rowvc['officer'] == 'DFS')
          {
            $officer = 'DFS';
          }
          else if($rowvc['officer'] == 'VC')
          {
            $officer = 'VC';
          }
          else
          {
            continue;//moves the iteration 
            $officer = 'HR';
          }
            $prevComment = "
            <div class='row'>
                <div class='col-sm-3'>  </div>
                <div class='col-sm-8'>
                  <div class='row'>
                    <div class='col-sm-4'>
                      <label>{$officer}'s Comment</label>
                      <p>{$rowvc['comment']} </p>
                    </div>
                   <div class='col-sm-3'>
                      <label>Recommendation</label>
                      <p>{$rowvc['recommendation']}</p>
                    </div>
                  </div>
                </div>
               <div class='col-sm-1'>
               </div>
            </div>
            <hr>
          ";
          echo $prevComment;
        }
                $commentform = '
                    <div class="col-sm-3"> </div>
                    <div class="col-sm-8">
                       <h4 class="recommend">Enter Comment and Recommendation below</h4>
                    <div class="row">
                        <div class="col-sm-4">
                          <label>Comment</label>
                          <textarea id="comment"></textarea>
                        </div>
                        <div class="col-sm-3">
                          <label>Recommendation</label>
                          <select id="reccom">
                          <option></option>
                            <option>Approved</option>
                            <option>Not Approved</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn_style" id="vcbtn">Save</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div> 
                ';
              echo $commentform;
     }//end of numvc
  }//end of vco
  else if ($hro == $staffid)
  {
    if($lvobj->HRpass($slashedSession))
    {
          
          $send_btn = '
                <div class="col-sm-3"> </div>
                <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-10">         
                        <h3 style="
                            border: 1px solid;
                            margin-left: auto;
                            margin-right: auto;
                            padding: 10px;
                            width: 16em;
                        ">
                        Schedule Sent to DFS for Payroll</h3>
                      </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div>
                  ';
                echo $send_btn;
    }

    else if($lvobj->VCApproves($slashedSession))
    {
          $send_btn = '
                <div class="col-sm-3"> </div>
                <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-12">         
                        <button class="hr_send_btn" id="hrpassbtn">Pass to DFS</button>
                      </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div>
                  ';
                echo $send_btn;
    }

    else if($hrnum > 0)
    {
      $message = '<div class="col-sm-3"> </div>
                  <div class="col-sm-8">
                  <h3 class="recommend">Note Sent.</h3>
                  </div>';
      echo $message;
    }
    else 
    {    
        
               //echo "It is HR";
                $send_btn = '
                <div class="col-sm-3"> </div>
                <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-12">         
                        <button class="hr_send_btn" id="hrbtn">Send Schedule Notification</button>
                      </div>
                    </div>
                </div>
                <input type="hidden" id="staffid" name="staffId" value="'.$_SESSION['staffid'].'">
                <div id ="note"class="col-sm-1"> </div>
                  ';
                echo $send_btn;
    }//end of hrnum
  }//end of hr
?>

  
  <!---------------------------------------------------------------------------------------------------------------------------------------->
 </div><!--end of main row-->
 <hr>  
 <!-- Modal1 -->
 <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-center modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><label>Notice</label></h4>
        </div>
        <div class="modal-body" id="leavehistory">
            <div id="modalContent"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
        </div>
      </div>
    </div>
  </div>
<!----MODAL----->

</div><!--end of container--->
  <script>
    $('.export').click(function(){
            $("#example").table2excel({
                filename: "leaveschedule.xls"
            });
        });

      $("#hrpassbtn").click(function(){
        //console.log("generate");
          var staffid = $('#staffid').val();
          var encappno = window.btoa(staffid);
          var reccom = "Passed";
          var comment = "Passed to DFS";
          var url = "leavedashboard.php?id="+encappno;
          
          //alert("generate"); 
          $('#note').load('sendnote.php',
                { staffid: staffid, 
                  reccom: reccom, 
                  comment: comment 
                },
               function(){
                //alert("Notification Sent");
                $(location).attr('href', url);
              });                
    });
      $("#hrbtn").click(function(){
        //console.log("generate");
          var staffid = $('#staffid').val();
          var encappno = window.btoa(staffid);
          var reccom = "Presented";
          var comment = "Note Sent";
          var url = "leavedashboard.php?id="+encappno;
          
          //alert("generate"); 
          $('#note').load('sendnote.php',
                { staffid: staffid, 
                  reccom: reccom, 
                  comment: comment 
                },
               function(){
                //alert("Notification Sent");
                $(location).attr('href', url);
              });                
    });
    $("#regbtn").click(function(){
        //console.log("generate");
          var reccom = $('#reccom').val();
          var comment = $('#comment').val();
          var staffid = $('#staffid').val();
          if ((reccom == '') || (comment == ''))
            {
              $('#modalContent').html('<h5>All fields are required</h5>');
              $('#myModal1').modal({backdrop: 'static', keyboard: false});
              //console.log("All fields are required.");
            }
          
          else
          {
                var encappno = window.btoa(staffid);
                var url = "leavedashboard.php?id="+encappno;
          
          //alert(comment + reccom); 
          
                $('#note').load('sendnote.php',
                  { 
                    staffid: staffid,
                    comment: comment,
                    reccom: reccom 
                  },
                  function(){
                    //alert("Notification Sent");
                    $(location).attr('href', url);
                  });
          }//end of else          
    });
    $("#dfsbtn").click(function(){
        //console.log("generate");
          var reccom = $('#reccom').val();
          var comment = $('#comment').val();
          var staffid = $('#staffid').val();
          if ((reccom == '') || (comment == ''))
            {
              $('#modalContent').html('<h5>All fields are required</h5>');
              $('#myModal1').modal({backdrop: 'static', keyboard: false});
              //console.log("All fields are required.");
            }
          
          else
          {
              var encappno = window.btoa(staffid);
              var url = "leavedashboard.php?id="+encappno;
              
              //alert(comment + reccom); 
              
              $('#note').load('sendnote.php',
                    { 
                      staffid: staffid,
                      comment: comment,
                      reccom: reccom 
                    },
                   function(){
                    //alert("Notification Sent");
                   $(location).attr('href', url);
              });
          }//end of else
      });
    $("#vcbtn").click(function(){
      
          var reccom = $('#reccom').val();
          var comment = $('#comment').val();
          var staffid = $('#staffid').val();
          if ((reccom == '') || (comment == ''))
            {
              $('#modalContent').html('<h5>All fields are required</h5>');
              $('#myModal1').modal({backdrop: 'static', keyboard: false});
              //console.log("All fields are required.");
            }
          
          else
          {
              var encappno = window.btoa(staffid);
              var url = "leavedashboard.php?id="+encappno;
              
              $('#note').load('sendnote.php',
                    { 
                      staffid: staffid,
                      comment: comment,
                      reccom: reccom 
                    },
                   function(){
                    //console.log("Notification Sent");
                    $(location).attr('href', url);
               });
          }//end of else
       });
  </script>
</body>
</html>