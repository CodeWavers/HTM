
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
         
            <div class="card-body">
            <?php echo form_open('hrm/Home/update_atn_form/'. $data->att_id) ?>

               <input name="att_id" type="hidden" value="<?php echo html_escape($data->att_id) ?>">
               
               <div class="form-group row">
                 
                <div class="col-sm-9">
                    <input type="hidden" name="employee_id" value="<?php echo html_escape($data->employee_id) ?>" class="form-control"  readonly="readonly">
                </div>
            </div>

            <div class="form-group row">
                
                <div class="col-sm-9">
                    <input name="date" class="form-control" type="hidden" value="<?php echo html_escape($data->date)?>" readonly="readonly">
                </div>
            </div> 

            <div class="form-group row">
                
                <div class="col-sm-9">
                    <input name="sign_in" class=" form-control" type="hidden"  value="<?php echo html_escape($sign_in=$data->sign_in)?>"  id="sign_in" readonly="readonly" >
                </div>
            </div>
            
            
            
            <div class="form-group row">
                
                <div class="col-sm-9">
                    <input type="hidden" name="sign_out" class="form-control"   id="adv_details" value="<?php  
                     $timezone = $this->db->select('timezone')->from('setting')->get()->row();
                  date_default_timezone_set($timezone->timezone);
                    echo date("h:i:s a", time());?>"> 
                </div>
            </div>
            
            <div class="form-group row">
             
                <div class="col-sm-9">
                    <input type="hidden" name="staytime" class="form-control"   id="staytime" value="<?php
                    $in=new DateTime($sign_in);
                    $Out=new DateTime($sign_out);
                    $interval=$in->diff($Out);
                    
                    echo $interval->format('%H:%I:%S');
                    ?>"> 
                    
                </div>
            </div>
            <center class="f_size_100_c_green"><i class="fa fa-clock-o" aria-hidden="true"></i></center>
            
            <div class="form-group text-center">
                <a href="<?php echo base_url('hrm/attendance-list') ?>" class="btn btn-danger">
                    <?php echo display('cancel')?></a> 
                    <button type="submit" class="btn btn-primary"><?php echo display('confirm_clock')?></button>
                </div>

                <?php echo form_close() ?>


            </div>  
        </div>
    </div>
</div>
