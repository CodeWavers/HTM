$(document).ready(function() {
    'use strict';
    $("#exdatatable").DataTable({
        dom:"<'row m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        lengthMenu:[[10,25,50,-1],[10,25,50,"All"]],
        buttons:[{extend:"copy",className:"btn-sm prints"},
            {extend:"csv",title:"Data List",className:"btn-sm prints"},
            {extend:"pdf",title:"Data List",className:"btn-sm prints"},
            {extend:"print",className:"btn-sm prints"}]}),
    $('#dataTableExample2').DataTable({
        "dom": 'lBfrtip',
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
	$('.dataTables_filter').addClass('');  
	$('.dataTables_filter label').addClass('search__inner');  
	$('.dataTables_filter input').addClass('search__text');	});