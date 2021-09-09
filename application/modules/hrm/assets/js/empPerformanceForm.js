$(function(){
    'use strict';
    $("#date").datepicker({ dateFormat:'yy-mm-dd' });
    $("#end_date").datepicker({ dateFormat:'yy-mm-dd' }).bind("change",function(){
        var minValue = $(this).val();
        minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
        minValue.setDate(minValue.getDate());
        $("#end_date").datepicker( "option", "minDate", minValue );
    })
});

'use strict';
function starcheck(){
var star = $('#number_of_star').val();
if(star > 5){
alert('You Can not input More Than five Star');
document.getElementById('number_of_star').value = '';
}
}