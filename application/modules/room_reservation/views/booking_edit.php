

<div class="card">
  <?php if($this->permission->method('room_reservation','create')->access()): ?>
  <div class="card-header">
    <h4>Update Booking <small class="float-right"> <a href="<?php echo base_url("room_reservation/room_reservation") ?>" class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i> <?php echo display('booking_list')?></a></small></h4>
  </div>
  <?php endif; ?>
  <div class="row">
    <!--  table area -->
    <div class="col-sm-12">
      <div class="card-body">

          <?php echo form_open('room_reservation/room-booking'); ?>
          <?php echo form_hidden('bookedid', (!empty($intinfo->bookedid) ? $intinfo->bookedid : null)) ?>
        
        <div class="form-group row">
          <label for="room_name" class="col-sm-2 col-form-label"><?php echo display('guest') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-4">
                <?php echo form_dropdown('guest2', $customerlist, $customerlist = $intinfo->cutomerid, 'class="selectpicker form-control" data-live-search="true" id="guest2" disabled="disabled"') ?>
                <input name="guest" type="hidden" value="<?php echo html_escape($intinfo->cutomerid) ?>"
                       id="guest">
            </div>


                <label for="room_name" style="display: none" class="col-sm-2 col-form-label change_room"><?php echo display('room_name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-3 change_room " style="display: none">


                    <!--            --><?php //echo form_dropdown(array("name" => "room_name[]"),$roomlist,'', 'class="selectpicker form-control" data-live-search="true" multiple="multiple" id="room_name"' ) ?>
                    <?php echo form_dropdown("room_name",$roomlist,1, 'class="selectpicker form-control" data-live-search="true" multiple="multiple" id="room_name"'   ) ?>

                </div>


        </div>
        <div class="form-group row">
          <label for="room_name" class="col-sm-2 col-form-label" hidden><?php echo display('no_of_people') ?> <span class="text-danger">*</span></label>
          <div class="col-sm-4" hidden>
            <input name="no_of_people" autocomplete="off" class="form-control" type="text" value="1" placeholder="<?php echo display('no_of_people') ?>" id="no_of_people" >

          </div>
          <label for="check_in" class="col-sm-2 col-form-label"><?php echo display('check_in') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-4">
                <input name="check_in" autocomplete="off" class="form-control" type="text" readonly="readonly"
                       placeholder="<?php echo display('check_in') ?>"
                       value="<?php echo html_escape((!empty($intinfo->checkindate) ? $intinfo->checkindate : null)) ?>"
                       id="check_in">
            </div>


        </div>
        
        <div class="form-group row">
          <label for="check_out" class="col-sm-2 col-form-label"><?php echo display('check_out') ?> <span class="text-danger">*</span></label>

            <div class="col-sm-4">
<!--                <input name="check_out" autocomplete="off" class=" form-control" type="date" placeholder="--><?php //echo display('check_out') ?><!--" id="check_out" onkeyup="enddate()" >-->


                <input name="" autocomplete="off" class="datepickerwithoutprevdates form-control " readonly
                       type="hidden" placeholder="<?php echo display('check_out') ?>"
                       value="<?php echo html_escape((!empty($intinfo->checkoutdate) ? $intinfo->checkoutdate : null)) ?>"
                       id="check_out_last">
                <input name="check_out" autocomplete="off" class="datepickerwithoutprevdates form-control "
                       type="date" placeholder="<?php echo display('check_out') ?>"
                       value="<?php echo html_escape((!empty($intinfo->checkoutdate) ? $intinfo->checkoutdate : null)) ?>"
                       id="check_out">
                <input name="check_out_old" autocomplete="off" class="datepickerwithoutprevdates form-control "
                       type="hidden" placeholder="<?php echo display('check_out') ?>"
                       value="<?php echo html_escape((!empty($intinfo->checkoutdate) ? $intinfo->checkoutdate : null)) ?>"
                       id="">
            </div>

          <div class="col-sm-4 change_room"  id="change_room" style="display: none">
            <button type="button" class="btn btn-success w-md m-b-5" onclick="getfreerooms()">Change Room</button>
          </div>
        </div>
          <div id="content">
              <div id="bookinginfo">
                  <?php

                  $checkin=$intinfo->checkindate;
                  $checkout=$intinfo->checkoutdate;

                  $datetime1 = date_create($checkin);


                  $datetime2 = date_create($checkout);
                  $interval = date_diff($datetime1, $datetime2);
                  ?>


                  <!--                                                echo '<pre>';print_r($rr['roominfo']->rate);exit();-->

                  <!--                                                    -->
                  <div class="col-sm-12 row" id="contents">
                      <div class="col-sm-12">
                          <div class="text-center">

                              <button type="button" class="btn btn-info text-center text-uppercase  " style="border-radius: 20px;margin: 8px;" disabled=""><?php echo html_escape($room_name);?> <?php echo html_escape($interval->format('%a'));?> <?php echo display('nights_booking_from') ?> <?php echo $checkin;?> to <?php echo $checkout;?></button>
                          </div>

                      </div>


                  </div>

                  <div class="form-group row">
                      <label for="room_name" class="col-sm-2 col-form-label">No of People </label>
                      <div class="col-sm-4">
                          <input name="no_of_people" autocomplete="off" class="form-control" type="text"
                                 readonly="readonly" placeholder="<?php echo display('no_of_people') ?>"
                                 value="<?php echo html_escape((!empty($intinfo->nuofpeople) ? $intinfo->nuofpeople : null)) ?>"
                                 id="no_of_people">
                      </div>

                      <label for="room_name" class="col-sm-2 col-form-label">Selected Room No:<span
                                  class="text-danger">*</span></label>

                      <div class="col-sm-3">
                          <input name="room_type" class="form-control" type="text" readonly="readonly"
                                 value="<?php echo html_escape($room_no) ?>">

                      </div>
                      <div class="col-sm-1">

                          <a href="#" id="change_btn" class=" btn-sm btn btn-danger" onclick="change_room()"><i
                                      class="fas fa-exchange-alt"></i></a>
                      </div>

                  </div>
                  <?php

                  $payment_date = $intinfo->payment_deadline;

                  $newDate = date('Y-m-d\TH:i', strtotime($payment_date)); ?>

                  <div class="form-group row">
                      <label for="room_name" class="col-sm-2 col-form-label">Payment Deadline </label>
                      <div class="col-sm-4">
                          <input name="payment_deadline" autocomplete="off" class="form-control" type="datetime-local"
                                 value="<?php echo $newDate ?>" id="payment_deadline">
                      </div>

                      <label for="room_name" class="col-sm-2 col-form-label">Booking Status:<span
                                  class="text-danger">*</span></label>

                      <div class="col-sm-3">
                          <select name="status" class="selectpicker form-control" data-live-search="true" id="status" onchange="refund_div(this.value)">
                              <option value=""><?php echo display('select') ?><?php echo display('status') ?></option>
                              <option value="0" <?php if ($intinfo->bookingstatus == '0') {
                                  echo "selected";
                              } ?>><?php echo display('pending') ?></option>
                              <option value="1" <?php if ($intinfo->bookingstatus == '1') {
                                  echo "selected";
                              } ?>><?php echo display('cancel') ?></option>
                              <option value="2" <?php if ($intinfo->bookingstatus == '2') {
                                  echo "selected";
                              } ?>>Checked In
                              </option>
                              <option value="4" <?php if ($intinfo->bookingstatus == '4') {
                                  echo "selected";
                              } ?>>Confirmed
                              </option>
                              <option value="3" <?php if ($intinfo->bookingstatus == '3') {
                                  echo "selected";
                              } ?>><?php echo 'Checkout' ?></option>
                          </select>

                      </div>
                  </div>
                  <div style="display: none" id="refund_div">
                      <div class="form-group row"  >
                          <label for="room_name" class="col-sm-2 col-form-label">Total Amount</label>

                          <div class="col-sm-4">
                              <input name="total_amount" class="form-control" type="number"  placeholder="Total Amount"
                                     value="<?php echo html_escape((!empty($intinfo->total_price) ? $intinfo->total_price : 0)) ?>" readonly>
                          </div>

                          <label for="room_name" class="col-sm-2 col-form-label">Refund</label>

                          <div class="col-sm-3">
                              <input name="refund_amount" class="form-control" type="number"  placeholder="Amount"
                                     value="">
                          </div>
                      </div>


                  </div>


                  <div class="form-group text-left margin_20px">
                      <button type="submit" style="border-radius: 20px" class="btn btn-info w-md m-b-5">Update</button>
                  </div>


              </div>
          </div>



          <?php foreach ($room_type as $type) { ?>

              <input name="room_id[]" id="" type="hidden" autocomplete="off" class="form-control"
                     value="<?php echo $type->roomid ?>">
              <input name="room_rate[]" id="" type="hidden" autocomplete="off" class="form-control"
                     value="<?php echo $type->room_rate ?>">


          <?php } ?>

          <input name="check_out_old" autocomplete="off" class="datepickerwithoutprevdates form-control "
                 type="hidden" placeholder="<?php echo display('check_out') ?>"
                 value="<?php echo html_escape((!empty($intinfo->checkoutdate) ? $intinfo->checkoutdate : null)) ?>"
                 id="">

          <input name="total_price" autocomplete="off" class="form-control total_price" type="hidden"
                 readonly="readonly" placeholder=""
                 value="<?php echo html_escape((!empty($intinfo->total_price) ? $intinfo->total_price : 0)) ?>"
                 id="total_price">
          <input name="grand_total" autocomplete="off" class="form-control grand_total" type="hidden"
                 readonly="readonly" placeholder=""
                 value="<?php echo html_escape((!empty($intinfo->total_price) ? $intinfo->total_price : 0)) ?>"
                 id="grand_total">
          <input name="s_price" autocomplete="off" class="form-control s_price" type="hidden"
                 readonly="readonly" placeholder=""
                 value="<?php echo html_escape((!empty($intinfo->service_total) ? $intinfo->service_total : 0)) ?>"
                 id="s_price">
          <input name="service_total" autocomplete="off" class="form-control service_total"
                 type="hidden" readonly="readonly" placeholder="" value="" id="service_total">

          <input name="booking_number" autocomplete="off" class="form-control booking_number"
                 type="hidden" readonly="readonly" placeholder="" value="<?php echo $intinfo->booking_number ?>" id="booking_number">

          <input name="paid_amount" autocomplete="off" class="form-control paid_amount"
                 type="hidden" readonly="readonly" placeholder="" value="<?php echo $intinfo->paid_amount ?>" id="paid_amount">


          <input name="date_time" autocomplete="off" class="form-control paid_amount"
                 type="hidden" readonly="readonly" placeholder="" value="<?php echo $intinfo->date_time ?>" id="date_time">
        <?php echo form_close() ?>



      </div>
    </div>
  </div>
</div>
<script src="<?php echo MOD_URL.$module;?>/assets/js/addBooking.js"></script>
<script type="text/javascript">
    $('#datepickeras').datepicker();

    // $("#change_btn").click(function () {
    //
    //     alert('Hellow')
    //     // $("#change_room").fadeToggle(1000);
    //     // $("#change_room").removeClass('d-none');
    //
    // });
    function change_room() {
        $(".change_room").fadeToggle(1000);


    }
    function refund_div(value) {

        if(value == 1){
            $("#refund_div").fadeIn(1000);
        }else{
            $("#refund_div").fadeOut(1000);
        }

    }
</script>