function starcheck(){
    'use strict';
    var star = $('#number_of_star').val();
   if(star > 5){
       alert('You Can not input More Than five Star');
       document.getElementById('number_of_star').value = '';
       }
   }