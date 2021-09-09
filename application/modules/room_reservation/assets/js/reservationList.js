$( document ).ready(function() {
    'use strict';
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    $("#bookingdetails").DataTable({
        "processing": true,
             "serverSide": true,
             "ajax":{
                url : base+"/room_reservation/room_reservation/bookingdatatable", // json datasource
                type: "post",
                data: {'csrf_test_name': csrf},  // type of method  ,GET/POST/DELETE
                error: function(){
                  $("#employee_grid_processing").css("display","none");
                  $('[data-toggle="tooltip"]').tooltip(); 
                }
              },
        dom:"<'row m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        order: [[ 1, "desc" ]],
        lengthMenu:[[10,25,50,-1],[10,25,50,"All"]],
        buttons:[{extend:"copy",className:"btn-sm prints"},
            {extend:"csv",title:"ExampleFile",className:"btn-sm prints"},
            {extend:"pdf",title:"ExampleFile",className:"btn-sm prints"},
            {extend:"print",className:"btn-sm prints"}]}),
    $('#_bookingdetails').DataTable({
             "processing": true,
             "serverSide": true,
             "ajax":{
                url : base+"/room_reservation/room_reservation/bookingdatatable", // json datasource
                type: "post",
                data: {'csrf_test_name': csrf},  // type of method  ,GET/POST/DELETE
                error: function(){
                  $("#employee_grid_processing").css("display","none");
                  $('[data-toggle="tooltip"]').tooltip(); 
                }
              },
              
             "dom": 'lBfrtip',
             "order": [[ 1, "desc" ]],
             "language": {
                    "search": '<i class="search__helper" data-sa-action="search-close"></i>',
                    "sFilterInput": "form-control",
                    "searchPlaceholder": "Search"
                },
                
             "buttons": [
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'csv',
                        'pdf',
                        'print'
                    ]
                }
            ]
            }); 
        $('.dataTables_filter').addClass('search');  
        $('.dataTables_filter label').addClass('search__inner');  
        $('.dataTables_filter input').addClass('search__text');	
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    