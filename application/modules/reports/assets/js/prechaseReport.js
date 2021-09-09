function getreport(){
    'use strict';
	var base = $('#base_url').val();
	var csrf = $('#csrf_token').val();
	var from_date=$('#from_date').val();
	var to_date=$('#to_date').val();
	if(from_date==''){
		alert("Please select from date");
		return false;
		}
	if(to_date==''){
		alert("Please select To date");
		return false;
		}
	var myurl = base+"reports/report/purchasereport/";
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: {csrf_test_name: csrf, from_date: from_date, to_date: to_date},
		 success: function(data) {
			 $('#getresult2').html(data);
		 } 
	});
	}