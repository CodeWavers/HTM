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

	// room_name.forEach((item, index)=>{
	// 	console.log(item)
	// 	//var room=$("#room").val(item);
	// 	text += item + ",";
	// })

	// for (let i = 0; i <= room_name.length; i++) {
	// 	text += room_name[i] + ",";
	// }


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


			 $('#bookinginfo').html(data);
			  $('select').selectpicker();
			 var found=$("#found").val();

		 } 
	  });
}

function getroomnumber(sl){

		///alert(sl)

	var totalroom=$("#numofroom_"+sl).val();

	//console.log(totalroom)
	var totalnight=$("#totalnight_"+sl).val();
	var price=$("#pernight_"+sl).text();
	var subtotal=$("#orgSubtotal_"+sl).val();
	var charge=$("#serviceCharge").val();
	var vat=$("#orgtax").val();
	var total=parseInt(subtotal)*parseInt(totalroom);

	//console.log(total)
	var totalprice=parseInt(totalnight)*parseInt(price);
	var discount=parseInt(totalprice)*parseInt(totalroom);
	var totaldiscount=parseInt(discount)-parseInt(total);



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

	$("#prcharge").text(serviceCharge);
	$("#prtax").text(tax);


	var granttotal=parseInt(gr_tot)+parseInt(serviceCharge)+parseInt(tax);

	$("#gramount").val(granttotal);
	$("#total").text(granttotal.toFixed(2));

	// var noofpeople=$("#numofpeople").val();
	// var maxpeople=$("#maxpeople").val();
	// var capacity=$("#capacity").val();
	// if(parseInt(noofpeople)>parseInt(maxpeople)){
	// alert("Max number of people exceeds");
	// $("#numofpeople").val('');
	// return false;
	// }
	// if(parseInt(noofpeople)>parseInt(totalroom*capacity)){
	// alert("Number of peoples capacity exceed on room");
	// $("#numofpeople").val('');
	// return false;
	// }
	// if(parseInt(totalroom)>parseInt(maxpeople/capacity)){
	// alert("Room capacity exceed");
	// $("#numofroom").val('');
	// return false;
	// }
	}