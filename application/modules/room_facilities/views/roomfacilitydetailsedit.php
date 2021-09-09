<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            
            <div class="panel-body">
                <?php echo form_open('room_facilities/room_facilitidetails/create') ?>
                <?php echo form_hidden('facilityid', (!empty($intinfo->facilityid)?$intinfo->facilityid:null)) ?>
                <div class="form-group row">
                    <label for="facilititypeyname" class="col-sm-4 col-form-label"><?php echo display('add_facility_type') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <?php echo form_dropdown('facilititypeyname',$facilitytype,$facilitytype=$intinfo->facilitytypeid, 'class="selectpicker form-control" data-live-search="true" id="facilititypeyname"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="facilityname" class="col-sm-4 col-form-label"><?php echo display('facility_name') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input name="facilityname" class="form-control" type="text" placeholder="Add <?php echo display('facility_name') ?>" id="tablename" value="<?php echo html_escape((!empty($intinfo->facilitytitle)?$intinfo->facilitytitle:null)) ?>">
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