<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            
            <div class="panel-body">
                <?php echo form_open('room_service/room_service/create') ?>
                <?php echo form_hidden('service_id', (!empty($intinfo->service_id)?$intinfo->service_id:null)) ?>
                <div class="form-group row">
                    <label for="service_name" class="col-sm-4 col-form-label">Service Name <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="service_name" class="form-control" type="text" placeholder="Add Service" id="tablename" value="<?php echo html_escape((!empty($intinfo->service_name)?$intinfo->service_name:null)) ?>">
                    </div>
                </div>


                <div class="editService">

                    <div id="edit_service" class="edit_service">
                        <div class="form-group row">
                            <?php if ($v_list){
                            foreach($v_list as $int) {?>
                            <label for="no_of_people" class="col-sm-4 col-form-label">Variation<span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input name="v_name_e[]" autocomplete="off" class="v_name form-control" type="text"  value="<?php echo html_escape((!empty($int->variation_name)?$int->variation_name:null)) ?>" id="v_name" placeholder="Variation">
                            </div>
                            <div class="col-sm-3">
                                <input name="rate_e[]" autocomplete="off" class="rate form-control" type="text"  value="<?php echo html_escape((!empty($int->rate)?$int->rate:null)) ?>" id="rate" placeholder="Rate">
                            </div>
                            <?php } ?>
                                <div  class=" col-sm-1">
                                    <a href="#" id="" class=" btn-sm btn btn-danger-soft remove_service_edit" ><i class="fa fa-minus-circle m-r-2"></i></a>
                                </div>
                            <div  class=" col-sm-1">
                                <a href="#" id="edit_service_btn" class=" btn-sm btn btn-black-soft edit_service_btn" ><i class="fa fa-plus-circle m-r-2"></i></a>
                            </div>

                            <?php } else {?>

                            <label for="no_of_people" class="col-sm-4 col-form-label">Variation<span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input name="v_name_e[]" autocomplete="off" class="v_name form-control" type="text"  value="<?php echo html_escape((!empty($int->variation_name)?$int->variation_name:null)) ?>" id="v_name" placeholder="Variation">
                            </div>
                            <div class="col-sm-3">
                                <input name="rate_e[]" autocomplete="off" class="rate form-control" type="text"  value="<?php echo html_escape((!empty($int->rate)?$int->rate:null)) ?>" id="rate" placeholder="Rate">
                            </div>
                                <div  class=" col-sm-1">
                                    <a href="#" id="edit_service_btn" class=" btn-sm btn btn-black-soft edit_service_btn" ><i class="fa fa-plus-circle m-r-2"></i></a>
                                </div>
                            <?php } ?>
                        </div>


                    </div>

                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){



        //  console.log(service)

        $("#edit_service_btn").click(function(){

            $(".editService").append("<div id=\"edit_service\" class=\"edit_service\">\n" +
                "                                            <div class=\"form-group row\">\n" +
                "                                                <label for=\"no_of_people\" class=\"col-sm-4 col-form-label\">Variation<span class=\"text-danger\">*</span></label>\n" +
                "                                                <div class=\"col-sm-3\">\n" +
                "                                                    <input name=\"v_name_e[]\" autocomplete=\"off\" class=\"v_name form-control\" type=\"text\"  value=\"\" id=\"v_name\" placeholder=\"Variation\" \">\n" +
                "                                                </div>\n" +
                "                                                <div class=\"col-sm-3\">\n" +
                "                                                    <input name=\"rate_e[]\" autocomplete=\"off\" class=\"rate form-control\" type=\"text\"  value=\"\" id=\"rate\" placeholder=\"Rate\">\n" +
                "                                                </div>\n" +
                "\n" +
                "                                                <div  class=\" col-sm-2\">\n" +
                "                                                    <a href=\"#\" id=\"\" class=\"btn-sm btn btn-danger-soft remove_service_edit\" ><i class=\"fa fa-minus-circle m-r-2\"></i></a>\n" +
                "                                                </div>\n" +
                "\n" +
                "                                            </div>\n" +
                "\n" +
                "\n" +
                "                                    </div>");
        });

        //  $('select').select2();


    });



    $("body").on("click",".remove_service_edit",function(e){
        $(this).parents('.edit_service').remove();
        //the above method will remove the user_data div
    });



</script>