<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            
            <div class="card-body">
                <?php echo form_open('room_reservation/room-booking');?>
                <?php echo form_hidden('bookedid', (!empty($intinfo->bookedid)?$intinfo->bookedid:null)) ?>
                <div class="form-group row">
                    <label for="guest" class="col-sm-4 col-form-label"><?php echo display('guest') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <?php echo form_dropdown('guest2',$customerlist,$customerlist=$intinfo->cutomerid, 'class="selectpicker form-control" data-live-search="true" id="guest2" disabled="disabled"') ?>
                        <input name="guest" type="hidden"  value="<?php echo html_escape($intinfo->cutomerid) ?>" id="guest" >
                    </div>
                </div>
                <div class="form-group row" hidden>
                    <label for="bookingnumber" class="col-sm-4 col-form-label"><?php echo display('bookingnumber') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="bookingnumber" type="hidden"  value="<?php echo html_escape($intinfo->booking_number) ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="room_name" class="col-sm-4 col-form-label"><?php echo display('room_name') ?> <span class="text-danger">*</span></label>
<!--                    <div class="col-sm-8">-->
<!--                        --><?php //echo form_dropdown('room_name2',$roomlist,$roomlist=$intinfo->roomid, 'class=" form-control" data-live-search="true" id="room_name2"disabled="disabled"') ?>
<!--                        <input name="room_name" type="hidden"  value="--><?php //echo html_escape($intinfo->roomid) ?><!--" id="room_name" >-->
<!--                    </div>-->

                    <div class="col-sm-8">
                        <input name="room_type" class="form-control" type="text"  readonly="readonly"  value="<?php echo html_escape($room_name) ?>" >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_of_people" class="col-sm-4 col-form-label"><?php echo display('no_of_people') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="no_of_people" autocomplete="off" class="form-control" type="text"  readonly="readonly" placeholder="<?php echo display('no_of_people') ?>" value="<?php echo html_escape((!empty($intinfo->nuofpeople)?$intinfo->nuofpeople:null)) ?>" id="no_of_people" >
                    </div>
                </div>
                <?php if(!empty($intinfo->room_no)){ ?>
                <div class="form-group row">
                    <label for="select_room_no" class="col-sm-4 col-form-label"><?php echo display('select_room_no') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="room_no" autocomplete="off" class="form-control" type="text"  readonly="readonly" placeholder="<?php echo display('select_room_no') ?>" value="<?php echo html_escape((!empty($room_no)?$room_no:null)) ?>" id="select_room_no" >
                        <input name="sub_total"  autocomplete="off" class="form-control sub_total" type="text"  readonly="readonly" placeholder="" value="<?php echo html_escape((!empty($intinfo->sub_total)?$intinfo->sub_total:0)) ?>" id="sub_total" >
                        <input name="total_price"  autocomplete="off" class="form-control total_price" type="hidden"  readonly="readonly" placeholder="" value="<?php echo html_escape((!empty($intinfo->total_price)?$intinfo->total_price:0)) ?>" id="total_price" >
                        <input name="grand_total"  autocomplete="off" class="form-control grand_total" type="hidden"  readonly="readonly" placeholder="" value="<?php echo html_escape((!empty($intinfo->total_price)?$intinfo->total_price:0)) ?>" id="grand_total" >
                        <input name="s_price"  autocomplete="off" class="form-control s_price" type="hidden"  readonly="readonly" placeholder="" value="<?php echo html_escape((!empty($intinfo->service_total)?$intinfo->service_total:0)) ?>" id="s_price" >
                        <input name="service_total"  autocomplete="off" class="form-control service_total" type="hidden"  readonly="readonly" placeholder="" value="" id="service_total" >

                </div>
                <?php }else{ ?>
                <?php if($isfound==2){ ?>
                <span text-center><?php echo display('no_room_found'); ?></span>
                <?php }else{ ?>
                <div class="form-group row">
                    <label for="no_of_people" class="col-sm-4 col-form-label"><?php echo display('select_room_no') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select name="slroomno[]" multiple="multiple" class="selectpicker form-control" data-live-search="true" id="slroomno">
                            <option value="" disabled><?php echo display('select_room_no') ?></option>
                            <?php $allroomno=explode(',',$freeroom);
                            foreach($allroomno as $sroom){?>
                            <option value="<?php echo html_escape($sroom);?>"><?php echo html_escape($sroom);?> </option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php } ?>


<!--                <div class="addService">-->
<!--                    <div id="service" class="service">-->
<!--                        --><?php //if ($v_list){
//                            foreach($v_list as $int) {?>
<!--                                <div class="form-group row margin_top_10px">-->
<!---->
<!--                                    <label for="no_of_people" class="col-sm-4 col-form-label">Select Service <span class="text-danger">*</span></label>-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <input name="" autocomplete="off" class=" form-control" type="text"  value="--><?php //echo $int->service_name ?><!--" id="" placeholder="" readonly>-->
<!--                                        <input name="service[]" autocomplete="off" class="rate form-control" type="hidden"  value="--><?php //echo $int->service_no ?><!--" id="" placeholder="" readonly>-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label for="no_of_people" class="col-sm-4 col-form-label">Variation & Rate <span class="text-danger">*</span></label>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <input name="[]" autocomplete="off" class=" form-control" type="text"  value="--><?php //echo $int->variation_name ?><!--" id="" placeholder=""  readonly>-->
<!--                                        <input name="variation_id[]" autocomplete="off" class=" form-control" type="hidden" value="--><?php //echo $int->variation_no ?><!--" id="" placeholder=""  readonly>-->
<!--                                    </div>-->
<!---->
<!--                                    <div class="col-sm-3">-->
<!--                                        <input name="rate[]" autocomplete="off" class=" form-control" type="text"  value="--><?php //echo $int->rate ?><!--" id="" placeholder="Rate"  readonly>-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!---->
<!---->
<!---->
<!--                            --><?php //} ?>
<!--                        <div class="form-group row" style="justify-content: center">-->
<!--                            <div  class=" col-sm-2">-->
<!--                                <a href="#" id="" class=" btn-sm btn btn-danger-soft remove_service" ><i class="fa fa-minus-circle m-r-2"></i></a>-->
<!---->
<!--                            </div>-->
<!--                            <div  class=" col-sm-2">-->
<!--                                     <a href="#" id="" class="btn-sm btn btn-black-soft add_service" ><i class="fa fa-plus-circle m-r-2"></i></a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                            --><?php //} else {?>
<!--                                <div class="form-group row margin_top_10px">-->
<!---->
<!--                                    <label for="no_of_people" class="col-sm-4 col-form-label">Select Service <span class="text-danger">*</span></label>-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <select name="service[]" class="selectpicker form-control"  data-live-search="true"  id="service_id_1" onchange="select_var(1)">-->
<!--                                            <option value="" >Select Service</option>-->
<!--                                            --><?php
//                                            foreach($service_list as $service_list) {?>
<!--                                                <option value="--><?php //echo html_escape($service_list->service_id);?><!--">--><?php //echo html_escape($service_list->service_name);?><!-- </option>-->
<!--                                            --><?php //} ?>
<!--                                        </select>-->
<!--                                    </div>-->
<!---->
<!--                                    <div  class=" col-sm-2">-->
<!--                                        <a href="#" id="" class=" btn-sm btn btn-black-soft add_service" ><i class="fa fa-plus-circle m-r-2"></i></a>-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!--                                <div id="subCat_div" style="">-->
<!--                                    <div class="form-group row">-->
<!--                                        <label for="no_of_people" class="col-sm-4 col-form-label">Variation & Rate <span class="text-danger">*</span></label>-->
<!--                                        <div class="col-sm-4">-->
<!--                                            <select name="variation_id[]" class="selectpicker form-control variation_id"  data-live-search="true"  id="variation_id_1" onchange="select_rate(1)">-->
<!--                                                <option></option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!---->
<!--                                        <div class="col-sm-2">-->
<!--                                            <input name="rate[]" autocomplete="off" class="rate form-control" type="text"  value="" id="rate_1" placeholder="Rate" onkeyup="calculation(1)" readonly>-->
<!--                                        </div>-->
<!---->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                        --><?php //} ?>
<!---->
<!--                    </div>-->

                    <div class="addService">
                        <div id="service" class="service">
                            <div class="form-group row margin_top_10px">

                                <label for="no_of_people" class="col-sm-4 col-form-label">Select Service <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <select name="service[]" class="selectpicker form-control"  data-live-search="true"  id="service_id_1" onchange="select_var(1)">
                                        <option value="" >Select Service</option>
                                        <?php
                                        foreach($service_list as $service_list) {?>
                                            <option value="<?php echo html_escape($service_list->service_id);?>"><?php echo html_escape($service_list->service_name);?> </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div  class=" col-sm-2">
                                    <a href="#" id="" class=" btn-sm btn btn-black-soft add_service" ><i class="fa fa-plus-circle m-r-2"></i></a>
                                </div>

                            </div>
                            <div id="subCat_div" style="">
                                <div class="form-group row">
                                    <label for="no_of_people" class="col-sm-4 col-form-label">Variation & Rate <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <select name="variation_id[]" class="selectpicker form-control variation_id"  data-live-search="true"  id="variation_id_1" onchange="select_rate(1)">
                                            <option></option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <input name="rate[]" autocomplete="off" class="rate form-control" type="text"  value="" id="rate_1" placeholder="Rate" onkeyup="calculation(1)" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>





                <input type="hidden" id="service_list" value='<?php foreach ($service as $se) {?>  <option value="<?php echo $se->service_id?>" ><?php echo $se->service_name?></option><?php } ?>' name="">
<!--                    <div id="cheque" class="cheque">-->
<!--            -->
<!--                        <div class="form-group row">-->
<!--                            <label for="no_of_people" class="col-sm-4 col-form-label">Rate:<span class="text-danger">*</span></label>-->
<!--                            <div class="col-sm-6">-->
<!--                                <input name="rate[]" class="rate form-control" type="text"  value="" id="rate" >-->
<!--                            </div>-->
<!---->
<!---->
<!--                        </div>-->
<!---->
<!--                    </div>-->


                <div class="form-group row">
                    <label for="check_in" class="col-sm-4 col-form-label"><?php echo display('check_in') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="check_in" autocomplete="off" class="form-control" type="text" readonly="readonly" placeholder="<?php echo display('check_in') ?>" value="<?php echo html_escape((!empty($intinfo->checkindate)?$intinfo->checkindate:null)) ?>" id="check_in" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="check_out" class="col-sm-4 col-form-label"><?php echo display('check_out') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">

                        <input name="" autocomplete="off" class="datepickerwithoutprevdates form-control "  readonly type="hidden" placeholder="<?php echo display('check_out') ?>" value="<?php echo html_escape((!empty($intinfo->checkoutdate)?$intinfo->checkoutdate:null)) ?>" id="check_out_last" >
                        <input name="check_out" autocomplete="off" class="datepickerwithoutprevdates form-control "  type="date" placeholder="<?php echo display('check_out') ?>" value="<?php echo html_escape((!empty($intinfo->checkoutdate)?$intinfo->checkoutdate:null)) ?>" id="check_out" >
                        <input name="check_out_old" autocomplete="off" class="datepickerwithoutprevdates form-control "  type="hidden" placeholder="<?php echo display('check_out') ?>" value="<?php echo html_escape((!empty($intinfo->checkoutdate)?$intinfo->checkoutdate:null)) ?>" id="" >

                      <?php foreach ($room_type as $type){?>

                        <input name="room_id[]" id="" type="hidden" autocomplete="off" class="form-control" value="<?php echo $type->roomid?>"  >
                        <input name="room_rate[]" id="" type="hidden" autocomplete="off" class="form-control" value="<?php echo $type->room_rate?>"  >


                        <?php }?>
                    </div>
                </div>
                <?php if(!empty($intinfo->bookingstatus!='1')){ ?>
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label"><?php echo display('status') ?> </label>
                    <div class="col-sm-8">
                        <select name="status" class="selectpicker form-control" data-live-search="true" id="status">
                            <option value=""><?php echo display('select') ?> <?php echo display('status') ?></option>
                            <option value="0" <?php if($intinfo->bookingstatus=='0'){ echo "selected";}?>><?php echo display('pending') ?></option>
                            <option value="1" <?php if($intinfo->bookingstatus=='1'){ echo "selected";}?>><?php echo display('cancel') ?></option>
                            <option value="2" <?php if($intinfo->bookingstatus=='2'){ echo "selected";}?>>Checked In</option>
                            <option value="4" <?php if($intinfo->bookingstatus=='4'){ echo "selected";}?>>Confirmed</option>
                            <option value="3" <?php if($intinfo->bookingstatus=='3'){ echo "selected";}?>><?php echo 'Checkout' ?></option>
                        </select>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo MOD_URL.$module;?>/assets/js/reservationEdit.js"></script>

    <script type="text/javascript">

        'use strict';

        $(document).ready(function(){

            var service = $("#service_list").val();
            var count = 1;

            var variation_id='variation_id_'+count;
            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            })

            // var date =$('#check_out').val();
            // date.setDate(date.getDate()-1);
            //
            // $('#check_out').datepicker({
            //     startDate: date
            // });

         //   var today = $('#check_out_last').val();





            $(".add_service").click(function(){


                count++;
              //  var service_id='service_id_'+count;

                $(".addService").append("<div id=\"service\" class=\"service\">\n" +
                    "                <div class=\"form-group row\">\n" +
                    "                    <label for=\"no_of_people\" class=\"col-sm-4 col-form-label\">Select Service <span class=\"text-danger\">*</span></label>\n" +
                    "                    <div class=\"col-sm-6\">\n" +
                    "                                               <select name=\"service[]\" class=\" form-control \"  data-live-search=\"true\"  id=\"service_id_" + count + "\"  onchange='select_var(" + count + ");'>\n" +
                    "                            <option value=\"\" >Select Service</option>\n" +service+
                    "                         \n" +
                    "                        </select>\n" +
                    "                    </div>\n" +

                    "\n" +
                    "                    <div  class=\" col-sm-2\">\n" +
                    "                        <a href=\"#\" id=\"remove_service\" class=\"client-add-btn btn-sm btn btn-danger-soft remove_service\" ><i class=\"fa fa-minus-circle m-r-2\"></i></a>\n" +
                    "                    </div>\n" +
                    "\n" +
                    "                </div>\n" +
                    " <div id=\"subCat_div\" style=\"\">\n" +
                    "                        <div class=\"form-group row\">\n" +
                    "                            <label for=\"no_of_people\" class=\"col-sm-4 col-form-label\">Variation & Rate <span class=\"text-danger\">*</span></label>\n" +
                    "                            <div class=\"col-sm-4\">\n" +
                    "                                <select name=\"variation_id[]\" class=\" form-control\"  data-live-search=\"true\"  id=\"variation_id_" + count + "\"  onchange='select_rate(" + count + ");'>\n" +
                    "                            <option value=\"\" >Select Variation</option>\n" +
                    "                                </select>\n" +
                    "                            </div>\n" +

                    "\n" +
                    "                            <div class=\"col-sm-2\">\n" +
                    "                                <input name=\"rate[]\" autocomplete=\"off\" class=\"rate form-control\" type=\"text\"  value=\"\"  id=\"rate_" + count + "\"  placeholder=\"Rate\" onkeyup=\"calculation()\" readonly>\n" +
                    "                            </div>\n" +
                    "\n" +
                    "                        </div>\n" +
                    "                        </div> " +
                    "\n" +
                    "                    </div>");
            });


            //  $('select').select2();


        });



        $("body").on("click",".remove_service",function(e){
            $(this).parents('.service').remove();
            //the above method will remove the user_data div
        });


        function select_var(sl) {
            var service_id = $("#service_id_"+sl).val();

           // alert(sl)

            var base_url = "<?= base_url() ?>";
            var csrf_test_name = $('[name="csrf_test_name"]').val();



            $.ajax( {
                url: base_url + "room_reservation/var_by_service",
                method: 'post',
                data: {
                    service_id:service_id,
                    csrf_test_name:csrf_test_name
                },
                cache: false,
                success: function( data ) {
                    let obj = jQuery.parseJSON(data);


                 //   console.log(obj.var_list)

                     $('#variation_id_'+sl).html(obj.var_list);

                }
            })

        }

        function select_rate(sl) {
            var var_id = $("#variation_id_"+sl).val();

            var base_url = "<?= base_url() ?>";
            var csrf_test_name = $('[name="csrf_test_name"]').val();
            var var_selected = ""



            $.ajax( {
                url: base_url + "room_reservation/rate_by_service",
                method: 'post',
                data: {
                    var_id:var_id,
                    var_selected: var_selected,
                    csrf_test_name:csrf_test_name
                },

                cache: false,
                success: function( data ) {
                    let obj = jQuery.parseJSON(data);


                 //   console.log(obj.rate)

                     $('#rate_'+sl).val(obj.rate);
                    calculation()

                }
            })



        }



        function calculation() {
            var t = 0;
            var total_price=parseFloat($('#total_price').val());
            var s_price=parseFloat($('#s_price').val());



            $(".rate").each(function () {
                isNaN(this.value) || 0 == this.value.length || (t += parseFloat(this.value))
            })


            var service_total=s_price+t;
            var grand_total=total_price+t;
            $('#service_total').val(service_total.toFixed(2,2));
            $('#grand_total').val(grand_total.toFixed(2,2));
            console.log(grand_total)

        }
    </script>