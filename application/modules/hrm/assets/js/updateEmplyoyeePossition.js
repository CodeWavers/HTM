function SelectToLoad(id){
    'use strict';
    var base = $('#base_url').val();
    //Ajax Load data from ajax
    $.ajax({
        url : base+'hrm/Employees/select_to_load/' + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
              $('[name="pos_id"]').val(data.pos_id);
        
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}