$("#apply").on('click', function(){
    var leavetype = $("#leavetype").val();
    var reason = $("#reason").val();
    var sdate = $("#sdate").val();
    var edate = $("#edate").val();
    var officer1 = $("#officer1").val();
    var officer2 = $("#officer2").val();
    var officer3 = $("#officer3").val();
    
    
// AJAX code to send data to php file.
        $.ajax({
            type: "POST",
            url:   "leavein.php",
            data: {leavetype:leavetype},
            dataType: "json",
            success: function(data) {
                //alert(data);
                $("#message").html(data);
                $("p").addClass("alert alert-success");
            },
            error: function(data) {
                alert("Wrong");
            },
        });
  });