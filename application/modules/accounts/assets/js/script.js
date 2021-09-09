function load_code(id,sl){
'use strict';
    var baseurl = $('#base_url').val();
  $.ajax({
      url : baseurl+'accounts/accounts/debtvouchercode/' + id,
      type: "GET",
      dataType: "json",
      success: function(data)
      {
         $('#txtCode_'+sl).val(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
  function addaccount(divName){
    'use strict';
      var cnt = $('#cntra').html();

  var row = $("#debtAccVoucher tbody tr").length;
  var count = row + 1;
  var limits = 500;
  var tabin = 0;
  if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
  else {
        var newdiv = document.createElement('tr');
        var tabin="cmbCode_"+count;
        var tabindex = count * 2;
        newdiv = document.createElement("tr");
         
        newdiv.innerHTML ="<td class='form-group'> <select name='cmbCode[]' id='cmbCode_"+ count +"' class='form-control basic-single' data-live-search='true' onchange='load_code(this.value,"+ count +")'>"+cnt+"</select></td><td><input type='text' name='txtCode[]' class='form-control'  id='txtCode_"+ count +"' required></td><td><input type='text' name='txtAmount[]' class='form-control total_price' value='0' id='txtAmount_"+ count +"' onkeyup='calculation("+ count +")' required></td><td><input type='text' name='txtAmountcr[]' class='form-control total_price1' id='txtAmount1_"+ count +"' value='0' onkeyup='calculation("+ count +")' required></td><td><button class='btn btn-danger red t_right' type='button' value='' onclick='deleteRow(this)'> <i class='ti-trash'></i></button></td>";
        document.getElementById(divName).appendChild(newdiv);
        $(".basic-single").select2();
        document.getElementById(tabin).focus();
        count++;
      

      }
  }
  function addaccountDevid(divName){
    'use strict';
    var cnt = $('#davit').html();

var row = $("#debtAccVoucher tbody tr").length;
var count = row + 1;
var limits = 500;
var tabin = 0;
if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
else {
      var newdiv = document.createElement('tr');
      var tabin="cmbCode_"+count;
      var tabindex = count * 2;
      newdiv = document.createElement("tr");
       
      newdiv.innerHTML ="<td class='form-group'> <select name='cmbCode[]' id='cmbCode_"+ count +"' class='form-control basic-single' data-live-search='true' onchange='load_code(this.value,"+ count +")'>"+cnt+"</select></td><td><input type='text' name='txtCode[]' class='form-control'  id='txtCode_"+ count +"' required></td><td><input type='text' name='txtAmount[]' class='form-control total_price' id='txtAmount_"+ count +"' onkeyup='calculation("+ count +")' required></td><td><button class='btn btn-danger red t_right' type='button' value=''onclick='deleteRow(this)'><i class='ti-trash'></i></button></td>";
      document.getElementById(divName).appendChild(newdiv);
      $(".basic-single").select2();
      document.getElementById(tabin).focus();
      count++;

    }
}
//update contra voucher
function load_code_update(id,sl){
    'use strict';
    var baseurl = $('#base_url').val();
    $.ajax({
        url : baseurl+'accounts/accounts/debit_voucher_code/' + id,
        type: "GET",
        dataType: "json",
        success: function(data)
        {
          
           $('#txtCode_'+sl).val(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
    function addaccountUpdate(divName){
        'use strict';
        var cnt = $('#cntra').html();
        var row = $("#debtAccVoucher tbody tr").length;
    var count = row + 1;
    var limits = 500;
    var tabin = 0;
    if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
    else {
          var newdiv = document.createElement('tr');
          var tabin="cmbCode_"+count;
          var tabindex = count * 2;
          newdiv = document.createElement("tr");
          newdiv.innerHTML = "<td class='form-group'> <select name='cmbCode[]' id='cmbCode_" + count + "' class='form-control basic-single' data-live-search='true' onchange='load_code_update(this.value," + count + ")'>"+cnt+"</select></td><td><input type='text' name='txtCode[]' class='form-control text-center'  id='txtCode_" + count + "' required></td><td><input type='text' name='txtAmount[]' class='form-control total_price text-right' value='' placeholder='0' id='txtAmount_" + count + "' onkeyup='calculation(" + count + ")' required></td><td><input type='text' name='txtAmountcr[]' class='form-control total_price1 text-right' id='txtAmount1_" + count + "' value='' placeholder='0' onkeyup='calculation(" + count + ")' required></td><td><button  class='btn btn-danger red t_right' type='button' value=''onclick='deleteRow(this)'><i class='ti-trash'></i></button></td>";
          document.getElementById(divName).appendChild(newdiv);
          $(".basic-single").select2();
          document.getElementById(tabin).focus();
          count++;
           
        }
    }

// update credit bdt voucher
function addaccountbdtVoucher(divName){
    'use strict';
    var cnt = $('#davit').html();
    var row = $("#debtAccVoucher tbody tr").length;
    var count = row + 1;
    var limits = 500;
    var tabin = 0;
    if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
    else {
          var newdiv = document.createElement('tr');
          var tabin="cmbCode_"+count;
          var tabindex = count * 2;
          newdiv = document.createElement("tr");
           
          newdiv.innerHTML ="<td class='form-group'> <select name='cmbCode[]' id='cmbCode_"+ count +"' class='form-control basic-single' data-live-search='true' onchange='load_code(this.value,"+ count +")'>"+cnt+"</select></td><td><input type='text' name='txtCode[]' class='form-control'  id='txtCode_"+ count +"' required></td><td><input type='text' name='txtAmount[]' class='form-control total_price' id='txtAmount_"+ count +"' onkeyup='calculation("+ count +")' required></td><td><button style='text-align: right;' class='btn btn-danger red' type='button' value='' onclick='deleteRow(this)'><i class='ti-trash'></i></button></td>";
          document.getElementById(divName).appendChild(newdiv);
          $(".basic-single").select2();
          document.getElementById(tabin).focus();
          $('#cmbCode_'+ count).append(dropdown);
          count++;

        }
    }


function calculation(sl) {
    'use strict';
      var gr_tot1=0;
      var gr_tot = 0;
      $(".total_price").each(function() {
          isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
      });

$(".total_price1").each(function() {
          isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
      });
      $("#grandTotal").val(gr_tot.toFixed(2,2));
       $("#grandTotal1").val(gr_tot1.toFixed(2,2));
  }

  function deleteRow(t) {
    'use strict';
      var a = $("#debtAccVoucher > tbody > tr").length;
      if (1 == a) alert("There only one row you can't delete.");
      else {
        var e = t.parentNode.parentNode;
        e.parentNode.removeChild(e)
      }
      calculation()
  }
  
$(document).ready(function(){
    'use strict';
    var baseurl = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    $('#cmbGLCode').on('change',function(){
        var Headid=$(this).val();
        $.ajax({
                url: baseurl+'accounts/accounts/general_led',
            type: 'POST',
            data: {
                csrf_test_name: csrf,
                Headid: Headid
            },
            success: function (data) {
                $("#ShowmbGLCode").html(data);
            }
        });

    });
});

    $(function(){
        'use strict';
    $(".datepicker").datepicker({ dateFormat:'yy-mm-dd' });
    
});


function loadData(id){
    "use strict"; 
    var baseurl = $('#base_url').val();
$.ajax({
url : baseurl+'accounts/accounts/selectedform/' + id,
type: "GET",
dataType: "json",
success: function(data)
{
$('#newform').html(data);
},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error get data from ajax');
}
});
}

function newdata(id){
    "use strict"; 
    var baseurl = $('#base_url').val();
$.ajax({
url : baseurl+'accounts/accounts/newform/' + id,
type: "GET",
dataType: "json",
success: function(data)
{
var headlabel = data.headlabel;
$('#txtHeadCode').val(data.headcode);
document.getElementById("txtHeadName").value = '';
$('#txtPHead').val(data.rowdata.HeadName);
$('#txtHeadLevel').val(headlabel);
$('#btnSave').prop("disabled", false);
},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error get data from ajax');
}
});
}