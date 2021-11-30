// $("#room_name").change(function(){
// 	var room_name= this.value;
// 	let text=" ";
// 	for (let i = 0; i < room_name.length; i++) {
// 		text += room_name[i] + ",";
// 	}
//
// 	$("#room").val(text);
//
// 	console.log(room_name)
// });




	// $(document).on('change', '#room_name', function() {
	// 	var room_name= this.value;
	// 	let text=" ";
	// 	for (let i = 0; i < room_name.length; i++) {
	// 		text += room_name[i] + ",";
	// 	}
	//
	// 	$("#room").val(text);
	//
	// 	console.log(room_name)
	// 			});
// function room_id() {
// 	var room_name= $("#room_name").val();
// 	let text=" ";
// 	for (let i = 0; i < room_name.length; i++) {
// 		text += room_name[i] + ",";
// 	}
//
//  $("#room").val(text);
// }

function getfreerooms(){
    'use strict';
	var guest= $("#guest").val();

	var room_name= $("#room_name").val();
	let text=" ";

//	console.log(text)
	var no_of_people= $("#no_of_people");
	var check_in= $("#check_in").val();
	var check_out= $("#check_out").val();
	if(guest==''){
		alert('Please Select Guest!!');
		return false;
		}
	if(room_name==''){
		alert('Please Select Room');
		return false;
		}
	if(no_of_people==''){
		alert('Please Select Number of people');
		return false;
		}
	if(check_in==''){
		alert('Please Select Check In Date');
		return false;
		}
	if(check_out==''){
		alert('Please Select Check Out Date');
		return false;
        }
	var base = $('#base_url').val();
	var csrf = $('#csrf_token').val();
	var myurl=base+"room_reservation/room_reservation/checkroom";
	$.ajax({
		url: myurl,
		 type: "POST",
		 data: {csrf_test_name: csrf, guest: guest, room_name: room_name, check_in: check_in, check_out: check_out},
		 success: function(data) {

			 $('#bookinginfo').html(data).is(':visible');
			  $('select').selectpicker();
			 var found=$("#found").val();

		 } 
	  });
}

function getroomnumber(sl){


	var sel=$('#slroomno_'+sl).val();
	//console.log(sel.length)
	$("#numofroom_"+sl).val(sel.length);

	var totalroom=parseFloat($("#numofroom_"+sl).val());
	var totalnight=$("#totalnight_"+sl).val();
	var price=parseFloat($("#roomrate_"+sl).val());

	var charge=$("#serviceCharge").val();
	var vat=$("#orgtax").val();

	var totalprice=parseInt(totalnight)*parseInt(price);
	var total=totalprice*parseInt(totalroom);
	//console.log(total)
	var discount=parseInt(totalprice)*parseInt(totalroom);
	var totaldiscount=parseInt(total)-parseInt(discount);
	var subtotal=$("#orgSubtotal_"+sl).val(total);
//	var count = slroomno.length;

	//console.log(slroomno)
	$("#discount_"+sl).val(total);

	$("#offer_"+sl).text(totaldiscount);
	$("#sub_total_"+sl).val(total);
	$("#prdis_"+sl).text(total);


	var gr_tot=0;

	$(".sub_total").each(function() {
		isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))

		//console.log(this.value)
	});

	//console.log(gr_tot)



	$("#gr_tot").val(gr_tot);
	$("#total_pricex").text(gr_tot.toFixed(2));
//	$("#gr_tot").val(gr_tot);

	var serviceCharge=parseInt(charge)*parseInt(gr_tot)/100;
	var tax=parseInt(vat)*parseInt(gr_tot)/100;



	var granttotal=parseInt(gr_tot)+parseInt(serviceCharge)+parseInt(tax);
	var main_discount=parseFloat($("#main_discount").val());


	$("#gramount").val(granttotal);
	$("#total").text(granttotal.toFixed(2));
	calculation_dis()


	}
	
	function calculation_dis() {

	//alert('ok')

		var total_rent=parseFloat($("#gr_tot").val());

		var main_discount=parseFloat($("#main_discount").val());

		$("#gramount").val(total_rent-main_discount);
		$("#total").text(total_rent-main_discount.toFixed(2));


	}