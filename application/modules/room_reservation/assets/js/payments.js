$( document ).ready(function() {
    'use strict';
    var base = $('#base_url').val();
    var id = $('#booking_id').val();
    var csrf = $('#csrf_token').val();
    $('#bookingdetails').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
           url : base+"/room_reservation/room_reservation/paymentsdatatable/"+id, // json datasource
           type: "post",  // type of method  ,GET/POST/DELETE
           data: {csrf_test_name: csrf},
           error: function(){
             $("#employee_grid_processing").css("display","none");
             $('[data-toggle="tooltip"]').tooltip(); 
           }
         },
         dom:"<'row m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
   lengthMenu:[[10,25,50,-1],[10,25,50,"All"]],
   order: [[ 0, "desc" ]],
   buttons:[
       {extend:"csv",title:"ExampleFile",className:"btn-sm prints"},
       {extend:"print",className:"btn-sm prints"}]}),
    $('#_bookingdetails').DataTable({
             "processing": true,
             "serverSide": true,
             "ajax":{
                url : base+"/room_reservation/room_reservation/paymentsdatatable/"+id, // json datasource
                type: "post",  // type of method  ,GET/POST/DELETE
                data: {csrf_test_name: csrf},
                error: function(){
                  $("#employee_grid_processing").css("display","none");
                  $('[data-toggle="tooltip"]').tooltip(); 
                }
              },
              
             "dom": 'lBfrtip',
             "order": [[ 0, "desc" ]],
             "language": {
                    "search": '<i class="ti-search search__helper" data-sa-action="search-close"></i>',
                    "sFilterInput": "form-control",
                    "searchPlaceholder": "search"
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

function editpayment(payid,bookingid,bookingno,invno,paydate,pmethod,amount){
    'use strict';
    var base_url = $('#base_url').val();
    $("#payid").val(payid);
    $("#bookedid").val(bookingid);
    $("#booking_number").val(bookingno);
    $("#invoice_no").val(invno);
    $("#pay_date").val(paydate);
    $("#amount").val(amount);
    $("#payment_method").selectpicker("val", pmethod);
    $("#btnchnage").text("Update");
    $('#updatepayment').attr('action', base_url+"room_reservation/room_reservation/addpayment/"+bookingid);
    }