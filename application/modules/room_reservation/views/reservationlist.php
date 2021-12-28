<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 text-center">
            <?php if($this->session->userdata('msg')) { ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
                <?php echo $this->session->userdata('msg');
                                        $this->session->unset_userdata('msg');
                ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="card">
    <?php if($this->permission->method('room_reservation','create')->access()): ?>
    <div class="card-header">
        <h4><?php echo display('booking_list')?><small class="float-right"><a
            href="<?php echo base_url("room_reservation/room-booking") ?>"
            class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i>
        <?php echo display('room_booking')?></a></small></h4>
    </div>
    <?php endif; ?>
    <div id="add0" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <strong><?php echo display('room_booking');?></strong>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php echo form_open('room_reservation/room-booking');?>
                                    <?php echo form_hidden('bookedid', (!empty($intinfo->bookedid)?$bookedid->roomid:null)) ?>
                                    <div class="form-group row">
                                        <label for="room_name"
                                            class="col-sm-4 col-form-label"><?php echo display('guest') ?> <span
                                            class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <?php echo form_dropdown('guest',$customerlist,'', 'class="selectpicker form-control" data-live-search="true" id="guest"') ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_name"
                                                class="col-sm-4 col-form-label"><?php echo display('room_name') ?> <span
                                                class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <?php echo form_dropdown('room_name',$roomlist,'', 'class="selectpicker form-control" data-live-search="true" id="room_name"') ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="no_of_people"
                                                    class="col-sm-4 col-form-label"><?php echo display('no_of_people') ?> <span
                                                    class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input name="no_of_people" autocomplete="off" class="form-control"
                                                        type="text" placeholder="<?php echo display('no_of_people') ?>"
                                                        id="no_of_people">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="check_in"
                                                        class="col-sm-4 col-form-label"><?php echo display('check_in') ?> <span
                                                        class="text-danger">*</span></label>
                                                        <div class="col-sm-8">
                                                            <input name="check_in" autocomplete="off" class="datepickers form-control"
                                                            type="text" placeholder="<?php echo display('check_in') ?>"
                                                            id="check_in">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="check_out"
                                                            class="col-sm-4 col-form-label"><?php echo display('check_out') ?> <span
                                                            class="text-danger">*</span></label>
                                                            <div class="col-sm-8">
                                                                <input name="check_out" autocomplete="off" class="datepickers form-control"
                                                                type="text" placeholder="<?php echo display('check_out') ?>"
                                                                id="check_in">
                                                            </div>
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <button type="reset"
                                                            class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                                            <button type="submit"
                                                            class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
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
                        <div id="edit" class="modal fade " role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <strong><?php echo display('update');?></strong>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body editinfo">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                        <?php if($this->permission->method('room_reservation','read')->access()): ?>
                        <div class="row">
                            <!--  table area -->
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table width="100%" class="datatable table table-striped table-bordered table-hover"
                                        id="bookingdetails">
                                        <thead>
                                            <tr>
                                                <th><?php echo display('sl_no') ?></th>
                                                <th><?php echo display('booking_number') ?></th>
                                                <th><?php echo 'Customer Name' ?></th>
                                                <th><?php echo 'Phone' ?></th>
                                                <th><?php echo display('room_name') ?></th>
                                                <th><?php echo 'Room No' ?></th>
                                                <th><?php echo display('check_in') ?></th>
                                                <th><?php echo display('check_out') ?></th>
                                                <th><?php echo display('booking_date') ?></th>
                                                <th><?php echo display('booking_status') ?></th>
                                                <th><?php echo display('payment_status') ?></th>
                                                <th><?php echo display('action') ?></th>
                                            </tr>
                                        </thead>
                                        </table> <!-- /.table-responsive -->
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <script src="<?php echo MOD_URL.$module;?>/assets/js/reservationList.js"></script>