$( document ).ready(function() {
    'use strict';
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    $("#facilitydetails").DataTable({
        "processing": true,
             "serverSide": true,
             
             "ajax":{
                url : base+"/room_facilities/room_facilitidetails/responses", // json datasource
                type: "post",
                data: {csrf_test_name: csrf},  // type of method  ,GET/POST/DELETE
                error: function(){
                  $("#employee_grid_processing").css("display","none");
                }
              },
      dom:"<'row  m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        order: [[ 0, "desc" ]],
        lengthMenu:[[10,25,50,-1],[10,25,50,"All"]],
        buttons:[{extend:"copy",className:"btn-sm prints"},
            {extend:"csv",title:"Data List",className:"btn-sm prints"},
            {extend:"pdf",title:"Data List",className:"btn-sm prints"},
            {extend:"print",className:"btn-sm prints"}]});
            $('.dataTables_filter input').addClass('search__text'); 
    });