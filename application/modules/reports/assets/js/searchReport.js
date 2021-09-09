function showinvoice(){
    'use strict';
    $('#hide_report').hide();
    $('#report_show').show();
    var csrf = $('#csrf_token').val();
    var geturl=$("#invoiceurl").val();
    var status=$("#status").val();
    var start_date=$("#start_date").val();
    var to_date=$("#to_date").val();
        $.ajax({
        type: "POST",
        url: geturl,
        data: {csrf_test_name:csrf, status: status, start_date: start_date, to_date: to_date},
        success: function(data) {
            $('#itemlist').html(data);
        } 
   });
   
   }