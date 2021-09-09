"use strict";
function starcheck(){
    var star = $("#number_of_star").val();
    var editstar = $("#nos").val();
    if(star > 5){
    alert('You Can not input More Than five Star');
    $("#number_of_star").val('');
}
    if(editstar > 5){
    alert('You Can not input More Than five Star');
    $("#nos").val('');
    }
}