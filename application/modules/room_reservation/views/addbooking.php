

<div class="card">
  <?php if($this->permission->method('room_reservation','create')->access()): ?>
  <div class="card-header">
    <h4><?php echo display('room_booking')?> <small class="float-right"> <a href="<?php echo base_url("room_reservation/room_reservation") ?>" class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i> <?php echo display('booking_list')?></a></small></h4>
  </div>
  <?php endif; ?>
  <div class="row">
    <!--  table area -->
    <div class="col-sm-12">
      <div class="card-body">
        
        <?php echo form_open('room_reservation/room-booking');?>
        <?php echo form_hidden('bookedid', (!empty($intinfo->bookedid)?$bookedid->roomid:null)) ?>
        
        <div class="form-group row">
          <label for="room_name" class="col-sm-2 col-form-label"><?php echo display('guest') ?> <span class="text-danger">*</span></label>
          <div class="col-sm-4">
            <?php echo form_dropdown('guest',$customerlist,'', 'class="selectpicker form-control" data-live-search="true" id="guest"') ?>
          </div>
            <div class="col-sm-1">
                <button type="button"
                        class="btn btn-info-soft btn-md" data-target="#add0" data-toggle="modal"><i class="fa fa-plus-circle"
                                                                                                  aria-hidden="true"></i>
                </button>
            </div>
          <label for="room_name" class="col-sm-2 col-form-label"><?php echo display('room_name') ?> <span class="text-danger">*</span></label>
          <div class="col-sm-3">

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
            <input name="check_in" autocomplete="off" class=" form-control" type="date" placeholder="<?php echo display('check_in') ?>" id="check_in" onkeyup="enddate()" >
          </div>
        </div>
        
        <div class="form-group row">
          <label for="check_out" class="col-sm-2 col-form-label"><?php echo display('check_out') ?> <span class="text-danger">*</span></label>

            <div class="col-sm-4">
                <input name="check_out" autocomplete="off" class=" form-control" type="date" placeholder="<?php echo display('check_out') ?>" id="check_out" onkeyup="enddate()" >
            </div>

          <div class="col-sm-4">
            <button type="button" class="btn btn-success w-md m-b-5" onclick="getfreerooms()"><?php echo display('search') ?></button>
          </div>
        </div>
          <div id="content">
          <div id="bookinginfo">
          
          </div>
          </div>
        <?php echo form_close() ?>



          <div id="add0" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <strong>Add Customer</strong>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-sm-12 col-md-12">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <?php echo form_open('customer/customer_info/create');?>
                               <?php echo form_hidden('customerid', (!empty($intinfo->customerid)?$intinfo->customerid:null)) ?>
                                          <div class="form-group row">
                                              <label for="firstname" class="col-sm-2 col-form-label"><?php echo display('firstname') ?> <span class="text-danger">*</span></label>
                                              <div class="col-sm-4">
                                                  <input name="firstname" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('firstname') ?>" id="firstname" value="" required>
                                              </div>
                                              <label for="lastname" class="col-sm-2 col-form-label"><?php echo display('lastname') ?> <span class="text-danger">*</span></label>
                                              <div class="col-sm-4">
                                                  <input name="lastname" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('lastname') ?>" id="lastname" value="" required>
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="email" class="col-sm-2 col-form-label"><?php echo display('email') ?></label>
                                              <div class="col-sm-4">
                                                  <input name="email" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('email') ?>" id="email" value="" required>
                                              </div>
                                              <label for="phone" class="col-sm-2 col-form-label"><?php echo display('phone') ?><span class="text-danger">*</span> </label>
                                              <div class="col-sm-4">
                                                  <input name="phone" autocomplete="off" class="form-control" type="number" placeholder="<?php echo display('phone') ?>" id="phone" value="" required>
                                                  <small class="form-text text-muted"><?php echo display('phone_message') ?></small>
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="dob" class="col-sm-2 col-form-label"><?php echo display('dob') ?></label>
                                              <div class="col-sm-4">
                                                  <input name="dob" autocomplete="off" class=" form-control" type="date" placeholder="<?php echo display('dob') ?>" id="dob" value="">
                                              </div>
                                              <label for="profession" class="col-sm-2 col-form-label"><?php echo display('profession') ?> </label>
                                              <div class="col-sm-4">
                                                  <input name="profession" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('profession') ?>" id="profession" value="">
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="phone" class="col-sm-2 col-form-label"><?php echo display('nationality') ?> <span class="text-danger">*</span> </label>
                                              <div class="col-sm-4">
                                                  <div class="form-check form-check-inline">
                                                      <input type="radio" class="form-check-input" name="nationaliti" id="materialInline1" value="native" checked="">
                                                      <label class="form-check-label"  for="materialInline1"><?php echo display('native') ?></label>
                                                  </div>
                                                  <div class="form-check form-check-inline">
                                                      <input type="radio" class="form-check-input" name="nationaliti" id="materialInline2" value="foreigner">
                                                      <label class="form-check-label"  for="materialInline2"><?php echo display('foreigner') ?></label>
                                                  </div>
                                              </div>
                                              <label for="national_id" class="col-sm-2 col-form-label">NID/Passport</label>
                                              <div class="col-sm-4">
                                                  <input name="national_id" autocomplete="off" class="form-control" type="number" placeholder="NID/Passport" id="national_id" value="">
                                              </div>
                                          </div>
                                          <span id="foreignerinfo" class="d_none">
                    <div class="form-group row">
                        <label for="nationalitycon" class="col-sm-2 col-form-label"><?php echo display('nationality') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input name="nationalitycon" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('nationality') ?>" id="nationalitycon" value="">
                        </div>
                        <label for="passport_no" class="col-sm-2 col-form-label"><?php echo display('passport_no') ?> <span class="text-danger">*</span> </label>
                        <div class="col-sm-4">
                            <input name="passport_no" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('passport_no') ?>" id="passport_no" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="visa_reg_no" class="col-sm-2 col-form-label"><?php echo display('visa_reg_no') ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input name="visa_reg_no" autocomplete="off" class="form-control" type="text" placeholder="<?php echo display('visa_reg_no') ?>" id="visa_reg_no" value="">
                        </div>
                        <label for="purpose" class="col-sm-2 col-form-label"><?php echo display('purpose') ?> <span class="text-danger">*</span> </label>
                        <div class="col-sm-4">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="purpose" id="materialInline3" value="Tourist">
                                <label class="form-check-label"  for="materialInline3"><?php echo display('tourist') ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="purpose" id="materialInline4" value="Business">
                                <label class="form-check-label"  for="materialInline4"><?php echo display('business') ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="purpose" id="materialInline5" value="Official">
                                <label class="form-check-label"  for="materialInline5"><?php echo display('official') ?></label>
                            </div>
                        </div>
                    </div>
                </span>
                                          <div class="form-group row">
                                              <label for="address" class="col-sm-2 col-form-label"><?php echo display('address') ?> </label>
                                              <div class="col-sm-10">
                                                  <textarea name="address" cols="30" rows="3" autocomplete="off" class="form-control" placeholder="<?php echo display('address') ?>"></textarea>
                                              </div>
                                          </div>



                                          <div class="form-group text-right">
                                              <button type="reset"
                                                      class="btn btn-primary w-md m-b-5" ><?php echo display('reset') ?></button>
                                              <input type="submit"
                                                      class="btn btn-success w-md m-b-5" name="instant" value="<?php echo display('ad') ?>">
                                          </div>
                                          <?php echo form_close() ?>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                  </div>
              </div>
          </div>
        <!-- /.table-responsive -->
      </div>
    </div>
  </div>
</div>
<script src="<?php echo MOD_URL.$module;?>/assets/js/addBooking.js"></script>
<script>
    $('#datepickeras').datepicker();
</script>