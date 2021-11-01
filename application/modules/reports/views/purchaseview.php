<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="card">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd lobidrag">
                <div class="card-header">
                    <h4><?php echo display('stock_report') ?>
                        
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo display('ingredient_name') ?></th>
                        <th><?php echo "In Qty"; ?> </th>
                        <th><?php echo "Out Qty"; ?> </th>
                        <th><?php echo "Stock"; ?> </th>
                        <th><?php echo display('price') ?> </th>
                        <th><?php echo display('total') ?> </th>

                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
                    <?php 
                                    $sl=1;


                                     $stock=0;
									$grandpr=0;
                                    foreach ($stockreport as $report) {
                                        $stock=$report->qty-$report->out_qty;
										$grandpr=($report->sumprice*$stock)+$grandpr;

										?>


                    <tr>
                        <td><?php echo  html_escape($report->product_name); ?></td>
                        <td><?php echo   html_escape((!empty($report->qty)?$report->qty." ".$report->uom_short_code:0 .$report->uom_short_code)); ?></td>
                        <td><?php echo   html_escape((!empty($report->out_qty)?$report->out_qty." ".$report->uom_short_code:0 .$report->uom_short_code)); ?></td>
                        <td><?php echo   html_escape((!empty($stock)?$stock." ".$report->uom_short_code:0 .$report->uom_short_code)); ?></td>
                        <td> <?php echo  html_escape($report->sumprice);?> </td>
                        <td> <?php echo  html_escape($report->sumprice*$stock);?> </td>

                    </tr>
                    <?php $sl++; } 
                                 ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" align="right"><b><?php echo display('grand_total') ?>:</b></td>
                        <td><?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo number_format($grandpr,2);?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                        </td>

                    </tr>
                </tfoot>
            </table>
        </div>
        </div>

    </div>
</div>
</div>
</div>