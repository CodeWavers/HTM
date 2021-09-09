<div>
    <script src="<?php echo MOD_URL.$module;?>/assets/js/script.js"></script>

    <small class="float-right" id="print">
        <input type="button" class="btn btn-info button-print text-white" name="btnPrint" id="btnPrint" value="Print"
            onclick="printContent('printArea')" />
    </small></button>
</div>


<?php
    $total=0;

    foreach ($ab as $ca){}?>
<div id="printArea">
<table class="table table-striped"  width="100%">


    <div class="form-group text-center c_f_size_f_weight_family_varient">

        <?php echo display('attendance_report') ?>
    </div>
    <div class="row">
        <div class="col-sm-4 text-center">
            <?php echo "<img src='" . base_url().html_escape((!empty($ca->picture)?$ca->picture:null))."' width=120px; height=120px;>";?>
        </div>
        <div class="col-sm-8">

            <div class="form-group text-left c_f_size_f_wight_family">

                <?php echo display('name') ?>:<?php
        echo html_escape((!empty($ca->first_name)?$ca->first_name:null)." ".(!empty($ca->last_name)?$ca->last_name:null));?>

            </div>
            <div class="form-group text-left c_f_size_wight_family">

                ID NO: <?php
    
echo html_escape((!empty($ca->employee_id)?$ca->employee_id:null)) ;
         
         
        ?>
            </div>

            <div class="form-group text-left c_f_size_wight_family">

                <?php echo display('designation') ?>: <?php echo html_escape((!empty($ca->pos_id)?$ca->pos_id:null)); ?>
            </div>
        </div>
    </div>
</table>
<table class="table table-striped" width="100%">
    <tr>
        <th> <?php echo display('sl') ?></th>
        <th> <?php echo display('date') ?></th>
        <th> <?php echo display('checkin') ?></th>
        <th> <?php echo display('checkout') ?></th>
        <th> <?php echo display('work_hour') ?></th>
    </tr>
    <?php
    $x=1;
    foreach($query as $qr){?>
    <tr>
        <td><?php echo $x++;?></td>
        <td><?php echo html_escape($qr->date)?></td>
        <td><?php echo html_escape($qr->sign_in)?></td>
        <td><?php echo html_escape($qr->sign_out)?></td>
        <td><?php echo html_escape($qr->staytime) ?></td>
    </tr>
    <?php }?>

</table>
</div>