<div class="card">
    <div class="card-body">
        <form>
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <h2 class="font-weight-600"><?php echo display('booking_information') ?></h2>
                    </div>
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
                        <div class=""><?php echo html_escape($roominfo->roomtype);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('adults') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->nuofpeople);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('num_of_room') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->total_room);?></div>
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
                        <label class="font-weight-600"><?php echo display('children') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->children);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('room_no') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->room_no);?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('room_rate') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->roomrate);?></div>
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
                        <div class=""><?php echo html_escape($bookinginfo->offer_discount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('total_price') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->total_price);?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('full_guest_name') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->full_guest_name);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('special_request') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->special_request);?></div>
                    </div>
                </div>
            </div>
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
        </form>
    </div>
</div>