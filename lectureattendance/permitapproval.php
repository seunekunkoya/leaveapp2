<?php
    include "../include/config.php";
    //$staffid =implode(',', array_map(function($el){ return $el['idno']; }, get_user($_SESSION['loginid'])));
 ?>
    <DOCTYPE html>
        <html lang="en">
        <head>
            <title>DAPU Interface</title>
                    <!-- BEGIN META -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="keywords" content="your,keywords">
            <meta name="description" content="Short explanation about this website">
                    <!-- END META -->
                    <!-- BEGIN STYLESHEETS -->
            <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
            <link type="text/css" rel="stylesheet" href="../assets/css/theme-default/bootstrap.css?1422792965" />
            <link type="text/css" rel="stylesheet" href="../assets/css/theme-default/materialadmin.css?1425466319" />
            <link type="text/css" rel="stylesheet" href="../assets/css/theme-default/font-awesome.min.css?1422529194" />
            <link type="text/css" rel="stylesheet" href="../assets/css/theme-default/material-design-iconic-font.min.css?1421434286" />
        </head>
        <style type="text/css">
            #content{
                display: table;
            }
            #item_table{
                margin-top: 10px;
                margin-left: 80px;
                position: relative;
            }
            th{
                color: black;
                background-color: #cccccc;
            }
        </style>
        <body class="menubar-hoverable header-fixed ">
            <!-- BEGIN HEADER-->
            <section>
            	<h3 class="text-center">Application Permit :- DAPU View</h3>
            	<table  class="text-center"  width="90%"; id=item_table  border color= "lightgrey" >
            		<tr>
                        <th class="text-center">S/N</th>
                        <th class="text-center">App ID</th>
                        <th class="text-center">Transaction ID</th>
                        <th class="text-center">Course</th>
                        <th class="text-center">Lecture Date</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Comment</th>
                        <th class="text-center">Permit Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    <?php
                        $sno=1;
                        $getddapuview=$attendanceupload->getddapuview()->fetchall(PDO::FETCH_ASSOC);
                        if(count($getddapuview)){
                        foreach ($getddapuview as $rows){
                            $applicationid=$rows['applicationid'];
                            $statusid=$rows['statusid'];
                            $transactionid=$rows['transactionid'];
                            $coursecode= $rows['coursecode'];
                            $lecturedate=$rows['lecturedate'];
                            $modlecturedate= date("Y-M-d", strtotime($lecturedate));
                            $tdate=$rows['tdate'];
                            $modlecturedate= date("Y-M-d", strtotime($tdate));
                            $reason=$rows['reason'];
                            $weekid=$rows['weekid'];
                            $comment=$rows['comment'];
                            $status=$rows['status'];
                            //$status='Recommended';
                            $dept=$rows['dept'];
                            $permitid=$rows['permittype'];
                    ?>
                            <tr>
                                <td width=2%;>
                                    <?php echo $sno++;?>
                                </td>
                                <td width=4%;>
                                    <?php echo  $applicationid;?>
                                </td>
                                <td width=5%;>
                                    <?php echo $transactionid;?>
                                </td>
                                <td width=5%;>
                                    <?php echo $coursecode;?>
                                </td>
                                <td width=5%;>
                                    <?php echo $lecturedate;?>
                                </td>
                                <td id="reason" width=30%;>
                                    <?php echo $reason;?>
                                </td>
                                <td width=30%;>
                                    <?php echo $comment;?>
                                </td>
                                
                                <?php
                                $getapplicationpermit=$attendanceupload->getapplicationpermit($permitid)->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <td width=25%;>
                                <?php echo $getapplicationpermit['permittype'];?>
                                 </td>
                                <td width=25%;>
                                    <?php echo $status;?>
                                </td>
                               
                                <td width="50%">
                                 <button id="edit" >
                                     <a href=permitapprovalreview.php?coursecode=<?php echo urlencode(base64_encode($coursecode));?>&lecturedate=<?php echo urlencode(base64_encode($lecturedate));?>&transactionid=<?php echo urlencode(base64_encode($transactionid));?>&dept=<?php echo urlencode(base64_encode($dept));?>>Review
                                     </a>
                                 </td>
                               

                    
                    
                        <?php
                    }
                    }    
                    else
                    {
                        echo '<tr><td colspan="10"><h2>There is no existing application awaiting for approval!!!!</h2></td></tr>';
                    }
                    ?>

                </table>
            </section>
            <?php

            $comment = 'Permit application transaction edit by '.$staffid .' for '.$coursecode.' on '.$lecturedate.' of '.$applicationid;
            $appid = '19';
            $menuid = '89';
            logguser($userid,$comment, $appid, $menuid);

            ?>

            <!--end #base-->

            <!-- END BASE -->



            <!-- BEGIN JAVASCRIPT -->

            <script src="../assets/js/libs/jquery/jquery-1.11.2.min.js"></script>

            <script src="../assets/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>

            <script src="../assets/js/libs/bootstrap/bootstrap.min.js"></script>

            <script src="../assets/js/libs/spin.js/spin.min.js"></script>

            <script src="../assets/js/libs/autosize/jquery.autosize.min.js"></script>

            <script src="../assets/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>

            <script src="../assets/js/core/source/App.js"></script>

            <script src="../assets/js/core/source/AppNavigation.js"></script>

            <script src="../assets/js/core/source/AppOffcanvas.js"></script>

            <script src="../assets/js/core/source/AppCard.js"></script>

            <script src="../assets/js/core/source/AppForm.js"></script>

            <script src="../assets/js/core/source/AppNavSearch.js"></script>

            <script src="../assets/js/core/source/AppVendor.js"></script>

            <script src="../assets/js/core/demo/Demo.js"></script>

            <!-- END JAVASCRIPT -->

        </body>

        </html>








