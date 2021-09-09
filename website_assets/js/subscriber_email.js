function IsEmail(email) {
    'use strict';
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
function subscribeemail(){
    'use strict';
    var email=$("#youremail").val();
    if(email==''){
        alert('Please Enter Your email');
        return false;
    }
    if(!IsEmail(email)){
        alert('Please enter a valid Email.');
        return false;
    }
    var baseurl = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    var geturl=baseurl+'hotel/subscribe';
    if(IsEmail(email)){
        $("#youremail").val('');
    }
    $.ajax({
            type: "POST",
            url: geturl,
            data: {csrf_test_name: csrf, email: email},
            success: function(data) {
                alert("Thanks for Subscription");
            } 
    });
    }	