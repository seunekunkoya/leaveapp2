$(document).ready(function(){
      
      $('.view_history').click(function(){  
           var appno = $(this).attr("id");  
           $.ajax({  
                url:"leavehistory.php",  
                method:"post",  
                data:{appno:appno},  
                success:function(data){  
                     $('#leavehistory').html(data);  
                     $('#myModal2').modal("show");  
                }  
           });  
      });


      $("#edate").change(function(ev){

      ev.preventDefault();

      var sdate = $("#sdate").val();
      var edate = $("#edate").val();

      $.ajax({
        type: "POST",
        url: "datediff.php",
        data: {
            sdate:sdate,
            edate:edate
        },
        dataType: "text",
            success: function(res) {             
               
                $('#datecomot').hide();
                $('#datedif').show();
                $('#datedif').html(res);
              },
            error: function(data) {
                $("#message").html(data);
                $("p").addClass("alert alert-danger");
            },
      });
    
      //alert("The text has been changed.");
  });
          
  $('.goback').click(function() {
       history.back();
   });   


  $('#btn-save').click(function(){
           // $('.rec-form').hide();
          
      var appno = $('#appno').val();
      var staffid = $('#staffid').val();
      var sdate = $('#sdate').val();
      var edate = $('#edate').val();
      var remarks = $('#remarks').val();
      var reco = $('#reco').val();
      var role = $('#role').val();
      var stage = $('#stage').val();

      var encappno = window.btoa(staffid);

      var url = "leavedashboard.php?id="+encappno;            

      if ((appno == '') || (staffid == '') || (sdate == '') || (edate == '') || (remarks == '') || (reco == ''))
      {
         alert("All fields are necessasry");
      }
      else {

            if (reco == 'Approved') {
               $('#error').load('leaveapprove.php', {
                   appno: appno,
                   staffid:staffid,
                   sdate: sdate,
                   edate: edate,
                   remarks: remarks,
                   reco: reco,
                   role: role,
                   stage: stage
                }, 
               function(){
                   $(location).attr('href', url);
              });
                     
            }//end of if reco
                  
           else {        

              //alert(reason + edate + sdate + reco);
              $('#error').load('leaverec.php', {
                  appno: appno,
                  staffid:staffid,
                  sdate: sdate,
                  edate: edate,
                  remarks: remarks,
                  reco: reco,
                  role: role,
                  stage: stage
               }, 
              function(){
                alert("Recommendation Saved");
                $(location).attr('href', url);
              });
          }//end of else
      }//end of main else
  });
});