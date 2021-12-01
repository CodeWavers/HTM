<div class="card">
    <div class="card-body">
        <?php echo form_open('room_reservation/change_booking_status');?>

            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <h2 class="font-weight-600"><?php echo display('booking_information') ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="float-right"><a
                                href="<?php echo base_url("room_reservation/room-booking") ?>"
                                class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i>
                            <?php echo display('room_booking')?></a>
                    </small>
                </div>


            </div>
            <?php $roominfo=$this->db->select("*")->from('roomdetails')->where('roomid',$bookinginfo->roomid)->get()->row(); ?>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('booking_number') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->booking_number);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('room_name') ?></label>
                        <div class=""><?php echo html_escape($room_name);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600">No of People</label>
                        <div class=""><?php echo html_escape($bookinginfo->nuofpeople);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('num_of_room') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->totalRoom);?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('checkin') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->checkindate);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('checkout') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->checkoutdate);?></div>
                    </div>
                </div>

                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('room_no') ?></label>
                        <div class=""><?php echo html_escape($room_no);?></div>
                    </div>
                </div>

                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600">Service Total</label>
                        <div class=""><?php echo html_escape($bookinginfo->service_total);?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600">Sub Total</label>
                        <div class=""><?php echo html_escape($bookinginfo->sub_total);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('paid_amount') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->paid_amount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('discount') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->discount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('total_price') ?>(with vat and service charge)</label>
                        <div class=""><?php echo html_escape($bookinginfo->total_price);?></div>
                    </div>
                </div>
            </div>
<!--            <div class="row">-->
<!--                <div class="col-md-3 pr-md-1">-->
<!--                    <div class="form-group">-->
<!--                        <label class="font-weight-600">--><?php //echo display('full_guest_name') ?><!--</label>-->
<!--                        <div class="">--><?php //echo html_escape($bookinginfo->full_guest_name);?><!--</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-md-3 pr-md-1">-->
<!--                    <div class="form-group">-->
<!--                        <label class="font-weight-600">--><?php //echo display('special_request') ?><!--</label>-->
<!--                        <div class="">--><?php //echo html_escape($bookinginfo->special_request);?><!--</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <h2 class="font-weight-600"><?php echo display('customer_information') ?></h2>
                    </div>
                </div>
            </div>
            <?php $userinfo=$this->db->select("*")->from('customerinfo')->where('customerid',$bookinginfo->cutomerid)->get()->row(); ?>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('account_name') ?></label>
                        <div class=""><?php echo html_escape($userinfo->firstname." ".$userinfo->lastname);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('email') ?></label>
                        <div class=""><?php echo html_escape($userinfo->email);?></div>
                    </div>
                </div>
                <div class="col-md-3 r-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('address') ?></label>
                        <div class=""><?php echo html_escape($userinfo->address);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('phone') ?></label>
                        <div class=""><?php echo html_escape($userinfo->cust_phone);?></div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <h2 class="font-weight-600">Booking Status</h2>
                    </div>
                </div>
            </div>
            <div class="row">

                <?php

                            $booking_number=$bookinginfo->booking_number;
                             $bookedid=$bookinginfo->bookedid;
                            $booking_status='';
                          if($bookinginfo->bookingstatus == 0){

                              $booking_status='Pending';
                          }
                          if($bookinginfo->bookingstatus == 1){

                              $booking_status='Cancel';
                          } if($bookinginfo->bookingstatus == 2){

                              $booking_status='Checked In';
                          }
                          if($bookinginfo->bookingstatus == 3){

                              $booking_status='Checked Out';
                          }
                          if($bookinginfo->bookingstatus == 4){

                              $booking_status='Confirmed';
                          }

                ?>


                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600">Change Status</label>
                        <select name="booking_status"  class="selectpicker form-control" data-live-search="true" size="2" id="booking_status" required>

                            <option value="<?php echo $bookinginfo->bookingstatus ?>" selected><?php echo $booking_status ?></option>
                            <option value="0"><?php echo display('pending') ?></option>
                            <option value="4">Confirmed</option>
                            <option value="2">Checked In</option>
                            <option value="3">Checked Out</option>
                            <option value="1">Cancel</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1 margin_top_10px">
                    <div class="form-group">
                        <label class="font-weight-600"></label>
                        <div class="form-group text-left">

                        <input type="hidden" name="booking_number" value="<?php echo $booking_number?>"/>
                        <input type="hidden" name="room_no" value="<?php echo $room_no ?>"/>
<!--                            <a href="--><?php //echo base_url("room_reservation/change_booking_status/".$booking_number/$booking_status) ?><!--" id="" class=" btn-md btn btn-danger" >Update</a>-->

                            <button type="submit" class="btn btn-success w-md m-b-5">Update</button>

                            <a href="<?php echo base_url("room_reservation/payment-information/").$bookedid ?>" class="btn btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment">Payment</a>


                        </div>

                    </div>
                </div>
            </div>
        <?php echo form_close() ?>
    </div>
</div>